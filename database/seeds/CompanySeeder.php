<?php

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create(['role_id' => 1, 'email' => 'admin@admin.com', 'password' => Hash::make('admin')]);

        // factory(Company::class, 5)->create()->each(function ($company) {

        //     factory(User::class, 1)->create(['role_id' => 2, 'company_id' => $company->id])->each(function ($user) use ($company) {

        //         foreach ([3, 4] as $role) {

        //             factory(User::class, 5)->create(['role_id' => $role, 'company_id' => $company->id, 'parent_id' => $user->id])->each(function ($user) use ($company) {

        //                 foreach ([3, 4] as $role) {

        //                     factory(User::class, 5)->create(['role_id' => $role, 'company_id' => $company->id, 'parent_id' => $user->id]);
        //                 }

        //             });
        //         }
        //     });
        // });
    }
}
