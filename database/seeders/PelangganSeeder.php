<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Faker\Factory as Faker;
use App\Lib\Csql;


class PelangganSeeder extends Seeder
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
            $prefix = "PEL";
            $tblname = "tbl_pelanggan";
            $primary = "kodepelanggan";
            $faker = Faker::create('id_ID');
            $faker_us = Faker::create('en_US');

            for($i = 1; $i <= 50; $i++)
            {
                DB::table($tblname)->insert([
                    'kodepelanggan' => Csql::generateKodeMax($primary, $prefix, $tblname),
                    'namapelanggan' => $faker_us->company,
                    'noteleponpelanggan' => $faker->phoneNumber,
                    'alamatpelanggan' => $faker->address,
                    'statuspelanggan' => 1,
                    'dateaddpelanggan' => date("Y-m-d H:i:s"),
                    'dateupdpelanggan' => date("Y-m-d H:i:s"),
                ]);
            }

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            echo $ex->getMessage();
        }
    }
}
