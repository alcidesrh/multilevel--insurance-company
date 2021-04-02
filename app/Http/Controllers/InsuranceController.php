<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Trail\SearchText;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Insurance\InsuranceCar;
use Alcidesrh\Generic\GenericResource;
use App\Models\Insurance\InsuranceHome;
use App\Models\Insurance\InsuranceLive;
use App\Models\Insurance\InsuranceObama;
use App\Mail\Insurance\CarInsuranceEmail;
use App\Mail\Insurance\HomeInsuranceEmail;
use App\Models\Insurance\InsuranceBusiness;
use App\Http\Resources\EditInsuranceResource;
use App\Mail\Insurance\BusinessInsuranceEmail;
use App\Mail\Insurance\ObamaCareInsuranceEmail;
use Alcidesrh\Generic\GenericResourceCollection;
use App\Http\Resources\Insurance\InsuranceListResource;
use App\Http\Resources\Insurance\CarInsuranceListResource;
use App\Http\Resources\Insurance\ObamaCareInsuranceResource;

class InsuranceController
{
    use SearchText;

    public function listQuery(Request $request, &$query, $table)
    {        
        $user = Auth::user();        

        if ($agents = $request->agents) {
            $query->where(function ($query) use ($agents, $table) {
                foreach (\explode(',', $agents) as $id) {
                    $query->orWhere("$table.user_id", \intval($id));
                }

            });
        }

        if ($request->from || $request->to) {
            $this->searchDateInterval($query, $request->from, $request->to, $table);
        }

        if ($clientName = $request->name) {

            $query->select("$table.*")->join('client', 'client.id', '=', "$table.client_id");
            
            $this->searchTerm($query, $clientName, ['name', 'last_name'], 'client');
        }

        if ($user->is('agency')) {
            $query->join('users', 'users.id', '=', "$table.user_id")->where(function ($query) use ($user, $table) {
                $query->orWhere("$table.user_id", $user->id)
                    ->orWhere('users.root_id', $user->id);
            })->distinct();

            $sql = $query->toSql();
        }
        else if(!$user->is('admin')) $query->where('user_id', $user->id);
    }

    public function listObamaCareInsurance(Request $request)
    {
        $user = Auth::user();

        $query = InsuranceObama::select('insurance_product.*');

        $this->listQuery($request, $query, 'insurance_product');

        if ($request->type == 'new') {
            $query->where(function ($query) {
                $query->where('insurance_product.insurance', 'Nuevo seguro')
                    ->orWhereNull('insurance_product.insurance');
            });
        } else if ($request->type == 'renew') {
            $query->where('insurance_product.insurance', 'Renovación');
        } else {
            $query->where('insurance_product.insurance', 'Cambio de vida');
        }

        if ($company = $request->company) {
            $this->searchTerm($query, $company, ['company'], 'insurance_product');
        }
        if ($plan = $request->plan) {
            $this->searchTerm($query, $plan, ['plan'], 'insurance_product');
        }
       
        if ($request->state) {

            $query->select('insurance_product.*')->join('client', 'client.id', '=', 'insurance_product.client_id');

            if ($state = $request->state) {
                $this->searchTerm($query, $state, ['state'], 'client');
            }
        }

        if($request->pdf){
            $pdf = PDF::loadView('pdf.insurance.obama-list', ['data' => $query->paginate($request->get('perPage'))]);
            return $pdf->stream();
        }
                
        return ObamaCareInsuranceResource::collection($query->paginate($request->get('perPage')));
    }

    public function saveObamaCareInsurance(Request $request)
    {

        try {
            $input = $request->all();

            $input['user_id'] = (Auth::user())->id;

            $client = $request->client;
            $client['insurance'] = 'obama-care';

            if (isset($request->client['id'])) {
                $client = DB::table('client')->updateOrInsert(['id' => $request->client['id'] ?? null], $client);
                $input['client_id'] = $request->client['id'];
            } else {
                $client = DB::table('client')->insertGetId($client);
                $input['client_id'] = $client;
            }

            if ($dependents = \json_decode($request->dependents)) {
                foreach ($dependents as $value) {
                    $value->client_id = $input['client_id'];
                    if (isset($value->id)) {
                        DB::table('dependent')->updateOrInsert(['id' => $value->id ?? null], (array) $value);
                    } else {
                        DB::table('dependent')->insertGetId((array) $value);
                    }
                }
            }

            $files = $input['files'] ?? null;
            $removedFiles = $input['removedFiles'] ?? null;

            unset($input['client'], $input['files'], $input['dependents'], $input['removedFiles']);

            $item = $request->id ? InsuranceObama::updateOrCreate(['id' => $request->id], $input) : InsuranceObama::create($input);

            if (isset($removedFiles)) {
                if ($removedFiles == 'all') {
                    $item->files()->delete();
                } else {
                    File::whereIn('id', $removedFiles)->delete();
                }

            }

            if (!empty($files)) {
                foreach ($files as $key => $value) {
                    $file = new File();
                    $file->setData($value)->save();
                    $item->files()->save($file);
                }
            }

            try {//appobama@dnfinsurance.com
                Mail::to($request->id ? ['appobama@dnfinsurance.com', 'aplications@aygsalas.com'] : ['appobama@dnfinsurance.com', 'aplications@aygsalas.com'])->send(new ObamaCareInsuranceEmail($item, $request->id));
            } catch (\Exception $e) {

                return response($e->getMessage(), 500);
            }

        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }

        return response('Se ha creado la solicitud.', 200);
    }

    public function editObamaCareInsurance($id)
    {
        return new EditInsuranceResource(InsuranceObama::find($id));
    }

    public function deleteObamaCareInsurance($id)
    {

        return InsuranceObama::find($id)->delete();
    }

    public function listCarInsurance(Request $request)
    {
        $query = InsuranceCar::select("insurance_car.*");

        $this->listQuery($request, $query, 'insurance_car');

        if($request->pdf){
            $pdf = PDF::loadView('pdf.insurance.list', ['data' => $query->paginate($request->get('perPage'))]);
            return $pdf->stream();
        }

        return CarInsuranceListResource::collection($query->paginate($request->get('perPage')));
    }

    public function saveCarInsurance(Request $request)
    {

        try {
            $input = $request->all();
            $input['user_id'] = (Auth::user())->id;

            $client = $request->client;
            $client['insurance'] = 'car';

            if (isset($request->client['id'])) {
                $client = DB::table('client')->updateOrInsert(['id' => $request->client['id'] ?? null], $client);
                $input['client_id'] = $request->client['id'];
            } else {
                $client = DB::table('client')->insertGetId($client);
                $input['client_id'] = $client;
            }

            $files = $input['files'] ?? null;
            $removedFiles = $input['removedFiles'] ?? null;
            
            unset($input['client'], $input['files'], $input['removedFiles']);

            $item = $request->id ? InsuranceCar::updateOrCreate(['id' => $request->id], $input) : InsuranceCar::create($input);

            if (isset($removedFiles)) {
                if ($removedFiles == 'all') {
                    $item->files()->delete();
                } else {
                    File::whereIn('id', $removedFiles)->delete();
                }

            }

            if (!empty($files)) {
                foreach ($files as $key => $value) {
                    $file = new File();
                    $file->setData($value)->save();
                    $item->files()->save($file);
                }
            }

            try {
                Mail::to($request->id ? 'info@sebandainsurance.com' : 'info@sebandainsurance.com')->send(new CarInsuranceEmail($item));
            } catch (\Exception $e) {

                return response($e->getMessage(), 500);
            }

        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
        $action = $request->id ? 'editado' : 'creado';
        return response("Se ha $action la solicitud.", 200);
    }

    public function editCarInsurance($id)
    {
        return new EditInsuranceResource(InsuranceCar::find($id));
    }

    public function deleteCarInsurance($id)
    {
        return InsuranceCar::find($id)->delete();
    }

    public function listHomeInsurance(Request $request)
    {
        $query = InsuranceHome::select("insurance_home.*");

        $this->listQuery($request, $query, 'insurance_home');

        if($request->pdf){
            $pdf = PDF::loadView('pdf.insurance.list', ['data' => $query->paginate($request->get('perPage'))]);
            return $pdf->stream();
        }

        return InsuranceListResource::collection($query->paginate($request->get('perPage')));
    }

    public function saveHomeInsurance(Request $request)
    {

        try {
            $input = $request->all();
            $input['user_id'] = (Auth::user())->id;

            $client = $request->client;
            $client['insurance'] = 'home';

            if (isset($request->client['id'])) {
                $client = DB::table('client')->updateOrInsert(['id' => $request->client['id'] ?? null], $client);
                $input['client_id'] = $request->client['id'];
            } else {
                $client = DB::table('client')->insertGetId($client);
                $input['client_id'] = $client;
            }

            $files = $input['files'] ?? null;
            $removedFiles = $input['removedFiles'] ?? null;
            
            unset($input['client'], $input['files'], $input['removedFiles']);

            $item = InsuranceHome::updateOrCreate(['id' => $request->id], $input);

            if (isset($removedFiles)) {
                if ($removedFiles == 'all') {
                    $item->files()->delete();
                } else {
                    File::whereIn('id', $removedFiles)->delete();
                }
            }
            if (!empty($files)) {
                foreach ($files as $key => $value) {
                    $file = new File();
                    $file->setData($value)->save();
                    $item->files()->save($file);
                }
            }

            try {//info@sebandainsurance.com
                Mail::to($request->id ? 'info@sebandainsurance.com' : 'info@sebandainsurance.com')->send(new HomeInsuranceEmail($item));
            } catch (\Exception $e) {

                return response($e->getMessage(), 500);
            }

        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
        
        $action = $request->id ? 'editado' : 'creado';
        return response("Se ha $action la solicitud.", 200);
    }

    public function editHomeInsurance($id)
    {
        return new EditInsuranceResource(InsuranceHome::find($id));
    }

    public function pdf($type, $id, $show = false)
    {
        if($type == 1){
            $pdf = PDF::loadView('pdf.insurance.obama-care', ['data' => InsuranceObama::find($id)]);
            return $show ? $pdf->stream() : $pdf->download('Obama Care Insurance.pdf');
        }
        else if($type == 2){
            $pdf = PDF::loadView('pdf.insurance.car', ['data' => InsuranceCar::find($id)]);
            return $show ? $pdf->stream() : $pdf->download('Car Insurance.pdf');
        }
        else if($type == 3){
            $pdf = PDF::loadView('pdf.insurance.home', ['data' => InsuranceHome::find($id)]);
            return $show ? $pdf->stream() : $pdf->download('Home Insurance.pdf');
        }
        else if($type == 4){
            $pdf = PDF::loadView('pdf.insurance.business', ['data' => InsuranceBusiness::find($id)]);
            return $show ? $pdf->stream() : $pdf->download('Business Insurance.pdf');
        }
    }

    public function deleteHomeInsurance($id)
    {
        return InsuranceHome::find($id)->delete();
    }

    public function listBusinessInsurance(Request $request)
    {
        $query = InsuranceBusiness::select("insurance_business.*");

        $this->listQuery($request, $query, 'insurance_business');

        if($request->pdf){
            $pdf = PDF::loadView('pdf.insurance.list', ['data' => $query->paginate($request->get('perPage'))]);
            return $pdf->stream();
        }

        return InsuranceListResource::collection($query->paginate($request->get('perPage')));
    }

    public function saveBusinessInsurance(Request $request)
    {

        try {
            $input = $request->all();
            $input['user_id'] = (Auth::user())->id;

            $client = $request->client;
            $client['insurance'] = 'home';

            if (isset($request->client['id'])) {
                $client = DB::table('client')->updateOrInsert(['id' => $request->client['id'] ?? null], $client);
                $input['client_id'] = $request->client['id'];
            } else {
                $client = DB::table('client')->insertGetId($client);
                $input['client_id'] = $client;
            }

            $files = $input['files'] ?? null;
            $removedFiles = $input['removedFiles'] ?? null;
            
            unset($input['client'], $input['files'], $input['removedFiles']);

            $item = InsuranceBusiness::updateOrCreate(['id' => $request->id], $input);

            if (isset($removedFiles)) {
                if ($removedFiles == 'all') {
                    $item->files()->delete();
                } else {
                    File::whereIn('id', $removedFiles)->delete();
                }
            }
            if (!empty($files)) {
                foreach ($files as $key => $value) {
                    $file = new File();
                    $file->setData($value)->save();
                    $item->files()->save($file);
                }
            }

            // try {//info@sebandainsurance.com
            //     Mail::to($request->id ? 'info@sebandainsurance.com' : 'info@sebandainsurance.com')->send(new BusinessInsuranceEmail($item));
            // } catch (\Exception $e) {

            //     return response($e->getMessage(), 500);
            // }

        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
        
        $action = $request->id ? 'editado' : 'creado';
        return response("Se ha $action la solicitud.", 200);
    }

    public function editBusinessInsurance($id)
    {
        return new EditInsuranceResource(InsuranceBusiness::find($id));
    }

    public function deleteBusinessInsurance($id)
    {
        return InsuranceBusiness::find($id)->delete();
    }

    public function listLiveInsurance(Request $request)
    {
        $query = InsuranceCar::select("insurance_car.*");

        $this->listQuery($request, $query, 'insurance_car');

        if($request->pdf){
            $pdf = PDF::loadView('pdf.insurance.list', ['data' => $query->paginate($request->get('perPage'))]);
            return $pdf->stream();
        }

        return CarInsuranceListResource::collection($query->paginate($request->get('perPage')));
    }

    public function saveLiveInsurance(Request $request)
    {

        try {
            $input = $request->all();

            $files = $input['files'] ?? null;
            
            $input = \json_decode($input['data'], true);
               
            $input['user_id'] = (Auth::user())->id;

            $client = $input['client'];
            $client['insurance'] = 'live';

            if (isset($client['id'])) {
                $client = DB::table('client')->updateOrInsert(['id' => $client['id'] ?? null], $client);
                $input['client_id'] = $client['id'];
            } else {
                $client = DB::table('client')->insertGetId($client);
                $input['client_id'] = $client;
            }

            
            $removedFiles = $input['removedFiles'] ?? null;
            $family = $input['family'] ?? null;
            $beneficiaries = $input['beneficiaries'] ?? null;
            $beneficiaries2 = $input['beneficiaries2'] ?? null;
            
            unset($input['client'], $input['removedFiles'], $input['family'], $input['beneficiaries'], $input['beneficiaries2']);

            $item = isset($input['id']) ? InsuranceLive::updateOrCreate(['id' => $input['id']], $input) : InsuranceLive::create($input);

            if (!empty($family)) {
                foreach ($family as $key => $value) {
                    if($key == 'brothers' || $key == 'sisters'){
                        foreach ($value as $data){
                         $data['insurance_live_id'] = $item->id;
                         $data['member'] = $key;
                         DB::table('insurance_live_family')->updateOrInsert(['id' => $data['id'] ?? null], $data);
                        }
                    }
                    else{
                        $value['insurance_live_id'] = $item->id;
                         $value['member'] = $key;
                         DB::table('insurance_live_family')->updateOrInsert(['id' => $value['id'] ?? null], $value);
                    }
                }
            }
            if (!empty($beneficiaries)) {
                foreach ($beneficiaries as $value) {
                        $value['insurance_live_id'] = $item->id;
                        $value['type'] = 1;
                        DB::table('insurance_live_beneficiary')->updateOrInsert(['id' => $value['id'] ?? null], $value);
                    
                }
            }
            if (!empty($beneficiaries2)) {
                foreach ($beneficiaries2 as $value) {
                        $value['insurance_live_id'] = $item->id;
                        $value['type'] = 2;
                        DB::table('insurance_live_beneficiary')->updateOrInsert(['id' => $value['id'] ?? null], $value);
                    
                }
            }

            if (isset($removedFiles)) {
                if ($removedFiles == 'all') {
                    $item->files()->delete();
                } else {
                    File::whereIn('id', $removedFiles)->delete();
                }

            }

            if (!empty($files)) {
                foreach ($files as $key => $value) {
                    $file = new File();
                    $file->setData($value)->save();
                    $item->files()->save($file);
                }
            }

            try {
                Mail::to($request->id ? 'info@sebandainsurance.com' : 'info@sebandainsurance.com')->send(new CarInsuranceEmail($item));
            } catch (\Exception $e) {

                return response($e->getMessage(), 500);
            }

        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
        $action = $request->id ? 'editado' : 'creado';
        return response("Se ha $action la solicitud.", 200);
    }

    public function editLiveInsurance($id)
    {
        return new EditInsuranceResource(InsuranceCar::find($id));
    }

    public function deleteLiveInsurance($id)
    {
        return InsuranceCar::find($id)->delete();
    }
}
