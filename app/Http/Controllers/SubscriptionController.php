<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payment;
use App\Trail\SearchText;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Util\SquarePayment;
use Illuminate\Support\Facades\Auth;
use Alcidesrh\Generic\GenericResource;
use App\Http\Resources\SubscriptionListResource;

class SubscriptionController extends Controller
{
    use SearchText;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function list(Request $request) {
        $query = Subscription::query();

        if ($search = $request->search) {
            $this->searchTerm($query, $search, ['title', 'description']);
        }
        return SubscriptionListResource::collection($query->paginate($request->get('perPage')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return new GenericResource(Subscription::find($request->id), [
            'id',
            'title',
            'description',
            'price',
            'active',
            'type',
            ['role' => ['id']],
            'duration',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $input = $request->all();

        if (!\is_array($input['role'])) {
            $input['role_id'] = $input['role'];
        }

        if ($id = $request->id) {
            $item = Subscription::find($id);
            $item->update($input);
        } else {
            $item = Subscription::create($input);
        }

        return new SubscriptionListResource($item);
    }

    public function renew(Request $request)
    {
        $user = Auth::user();

        $subcription = Subscription::find($request->subscription);

        $client = new SquarePayment($request->nonce);

        $paymentResponse = $client->proceedPayment($subcription->price);

        if (isset($paymentResponse['error'])) {
            return \json_encode(['error' => $paymentResponse['error']['category'] . ': ' . $paymentResponse['error']['detail']]);
        }

        $paymentResponse['user_id'] = $user->id;
        $paymentResponse['user_target'] = $user->id;
        $paymentResponse['type'] = 'Renovar subscripción';
        $paymentResponse['action'] = 'user-subscription';

        Payment::create($paymentResponse);
        
        $user->subscription_id = $subcription->id;
        $user->subscription_renew = (new Carbon())->addMonths($subcription->duration);
        $user->save();

        return \json_encode(['success' => 'Pago efectuado satifactoriamente']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Subscription::find($id)->delete();
    }
}
