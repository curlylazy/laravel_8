<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Faker\Factory as Faker;
use App\Lib\Csql;


class UserSeeder extends Seeder
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
            $prefix = "USR";
            $tblname = "tbl_user";
            $primary = "kodeuser";
            $faker = Faker::create('id_ID');

            // ADMIN
            DB::table($tblname)->insert([
                'kodeuser' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'username' => "admin",
                'password' => Crypt::encryptString("12345"),
                'nama' => $faker->name,
                'email' => $faker->email,
                'nohp' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'akses' => "ADMIN",
                'jk' => "L",
                'statususer' => 1,
                'dateadduser' => date("Y-m-d H:i:s"),
                'dateupduser' => date("Y-m-d H:i:s"),
            ]);

            for($i = 1; $i <= 5; $i++)
            {
                if ($i % 2 == 0)
                {
                    $gender = "male";
                    $jk = "L";
                } else {
                    $gender = "female";
                    $jk = "P";
                }

                DB::table($tblname)->insert([
                    'kodeuser' => Csql::generateKodeMax($primary, $prefix, $tblname),
                    'username' => strtolower($faker->firstName),
                    'password' => Crypt::encryptString("12345"),
                    'nama' => $faker->name($gender = $gender),
                    'email' => $faker->email,
                    'nohp' => $faker->phoneNumber,
                    'alamat' => $faker->address,
                    'akses' => "MANAJEMEN",
                    'jk' => $jk,
                    'statususer' => 1,
                    'dateadduser' => date("Y-m-d H:i:s"),
                    'dateupduser' => date("Y-m-d H:i:s"),
                ]);
            }

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            echo $ex->getMessage();
        }
    }
}
