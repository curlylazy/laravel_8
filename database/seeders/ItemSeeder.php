<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Faker\Factory as Faker;
use App\Lib\Csql;


class ItemSeeder extends Seeder
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
            $prefix = "ITM";
            $tblname = "tbl_item";
            $primary = "kodeitem";
            $faker = Faker::create('en_US');

            for($i = 1; $i <= 25; $i++)
            {
                $row_kategori = DB::table('tbl_kategori')->inRandomOrder()->first();
                DB::table($tblname)->insert([
                    'kodeitem' => Csql::generateKodeMax($primary, $prefix, $tblname),
                    'kodekategori' => $row_kategori->kodekategori,
                    'satuan' => 'PCS',
                    'namaitem' => $row_kategori->namakategori.' '.$faker->streetName(),
                    'stok' => $faker->numberBetween(20, 60),
                    'stokminimum' => $faker->numberBetween(10, 20),
                    'statusitem' => 1,
                    'keterangan' => "",
                    'gambar' => "",
                    'dateadditem' => date("Y-m-d H:i:s"),
                    'dateupditem' => date("Y-m-d H:i:s"),
                ]);
            }
            
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            echo $ex->getMessage();
        }
    }
}
