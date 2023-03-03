<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Faker\Factory as Faker;
use App\Lib\Csql;


class KategoriSeeder extends Seeder
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
            $prefix = "KAT";
            $tblname = "tbl_kategori";
            $primary = "kodekategori";

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Sabun",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Lilin",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Handuk Besar",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Handuk Kecil",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Shampo",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Dupa",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);

            DB::table($tblname)->insert([
                'kodekategori' => Csql::generateKodeMax($primary, $prefix, $tblname),
                'namakategori' => "Parfum",
                'statuskategori' => 1,
                'dateaddkategori' => date("Y-m-d H:i:s"),
                'dateupdkategori' => date("Y-m-d H:i:s"),
            ]);


            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            echo $ex->getMessage();
        }
    }
}
