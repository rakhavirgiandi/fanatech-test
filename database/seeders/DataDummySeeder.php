<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DataDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('inventories')->truncate();
        DB::table('sales')->truncate();
        DB::table('sales_details')->truncate();
        DB::table('purchases')->truncate();
        DB::table('purchase_details')->truncate();
        DB::table('roles')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                "name" => "Super Admin",
                "email" => "superadmin@example.com",
                "password" => Hash::make('super_admin_secret'),
                "role" => "super-admin"
            ],
            [
                "name" => "Manager",
                "email" => "manager@example.com",
                "password" => Hash::make('manager_secret'),
                "role" => "manager"
            ],
            [
                "name" => "Sales",
                "email" => "sales@example.com",
                "password" => Hash::make('sales_secret'),
                "role" => "sales"
            ],
            [
                "name" => "Sales Second",
                "email" => "sales.second@example.com",
                "password" => Hash::make('sales_second_secret'),
                "role" => "sales"
            ],
            [
                "name" => "Purchase",
                "email" => "purchase@example.com",
                "password" => Hash::make('purchase_secret'),
                "role" => "purchase"
            ],
            [
                "name" => "Purchase Second",
                "email" => "purchase.second@example.com",
                "password" => Hash::make('purchase_second_secret'),
                "role" => "purchase"
            ]
        ];

        $inventrory_names = ["Mouse", "Monitor", "Earphone", "Joystick" , "Keyboard", "Cooling Pad", "Processor Core i5 Gen 7", "Processor Core i3 Gen 11", "Processor AMD Ryzen 5 8500G 3.5GHz", "RAM 8 GB DDR 3", "RAM 8 GB DDR 2", "RAM 12 GB DDR 3"];

        try {
            DB::beginTransaction();

            foreach ($inventrory_names as $key => $value) {
                $inventrory = new Inventory;

                $inventrory->name = $value;
                $inventrory->code = Str::random(12);
                $inventrory->price = rand(1000, 1000000);
                $inventrory->stock = rand(0, 100);

                $inventrory->save();
            }

            foreach ($users as $key => $value) {
                $user = new User;
                $user->name = $value['name'];
                $user->email = $value['email'];
                $user->password = $value['password'];

                $role = Role::firstOrCreate(["name" => $value['role']]);
                $user->assignRole($role);
                $user->save();
            }

            $startDate = Carbon::create(2020, 1, 1);
            $endDate = Carbon::create(2024, 12, 31);

            for ($i=0; $i < 35 ; $i++) { 
                $user = User::with('roles')->get()->filter(
                    fn ($user) => $user->roles->whereNotIn('name', 'super-admin')->toArray()
                )->random()->id;
                $sales = new Sales;
                $sales->date = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
                $sales->number = $sales->newUniqueId();
                $sales->user()->associate($user);

                $sales->save();

                $inventrory = Inventory::all()->random();

                $sales_details = new SalesDetail;
                
                $qty = rand(1, $inventrory->stock);
                $sales_details->qty = $qty;
                
                $sales_details->sales()->associate($sales);
                $sales_details->inventory()->associate($inventrory);

                $sales_details->save();

            }

            for ($i=0; $i < 35 ; $i++) { 
                $user = User::with('roles')->get()->filter(
                    fn ($user) => $user->roles->whereNotIn('name', 'super-admin')->toArray()
                )->random()->id;
                $purchase = new Purchase;
                $purchase->date = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
                $purchase->number = $purchase->newUniqueId();
                $purchase->user()->associate($user);

                $purchase->save();

                $inventrory = Inventory::all()->random();

                $purchase_detail = new PurchaseDetail;
                
                $qty = rand(1, $inventrory->stock);
                $purchase_detail->qty = $qty;
                $purchase_detail->price = $inventrory->price * $qty;
                
                $purchase_detail->purchase()->associate($purchase);
                $purchase_detail->inventory()->associate($inventrory);

                $purchase_detail->save();

            }

            DB::commit();
            print('class DummyDataSeeder has been successfully executed!');
        } catch (\Throwable $th) {
            print('error: '. $th->getMessage());
            DB::rollback();
        }
    }
}
