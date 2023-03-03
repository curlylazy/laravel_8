<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Faker\Factory as Faker;
use App\Lib\Csql;


class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try
		{
            $prefix = "SUP";
            $tblname = "tbl_supplier";
            $primary = "kodesupplier";
            $faker = Faker::create('id_ID');
            $faker_us = Faker::create('en_US');

            for($i = 1; $i <= 50; $i++)
            {
                DB::table($tblname)->insert([
                    'kodesupplier' => Csql::generateKodeMax($primary, $prefix, $tblname),
                    'namasupplier' => $faker_us->company,
                    'noteleponsupplier' => $faker->phoneNumber,
                    'alamatsupplier' => $faker->address,
                    'statussupplier' => 1,
                    'dateaddsupplier' => date("Y-m-d H:i:s"),
                    'dateupdsupplier' => date("Y-m-d H:i:s"),
                ]);
            }

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            echo $ex->getMessage();
        }
    }
}
