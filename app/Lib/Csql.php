<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Lib\Cfilter;

class Csql
{
	public static function cekLogin()
	{
		if(empty(session('nip')))
		{
			redirect()->action('cLogin@login');
		}
	}

	public static function addDayswithdate($date, $days)
	{
	    $date = strtotime("+".$days." days", strtotime($date));
	    return  date("Y-m-d", $date);
	}

    public static function generateFakeID($prefix, $numkode)
    {
        // parsing kode
        $nilaikode = substr($numkode, strlen($prefix));
        $kode = (int) $nilaikode;
        $kode = $kode + $numkode;
        $hasilkode = $prefix.str_pad($kode, 4, "0", STR_PAD_LEFT);

        return $hasilkode;
    }

    public static function generateKodeNum($prefixkode, $table)
    {
        $iRes = "";

        $jmldata = DB::table($table)->count();

        if($jmldata == "" || $jmldata == null || $jmldata == 0)
        {
            $kode = $prefixkode."-1";
        }
        else
        {
            $kode = $prefixkode."-".($jmldata + 1);
        }

        $iRes = $kode;

        return $iRes;
    }

    public static function generateKodeTime($prefixkode, $uniqnum = 0)
    {
        $iRes = "";

        $timestamp = time();

        if($uniqnum > 0)
        {
            $timestamp = $timestamp + $uniqnum;
        }

        $kode = $prefixkode."-".$timestamp;

        $iRes = $kode;

        return $iRes;
    }

	public static function generateKode($prefix, $table)
    {

		// ambil max data counter
    	$datakode = DB::table("tbl_counter")
					->where("tabel", $table)
					->max('counter');

    	if($datakode == "")
    	{
    		$hasilkode = $prefix."0001";

			// simpan ke tbl_counter
			DB::table("tbl_counter")->insert([
		    	'tabel' => $table,
				'counter' => $hasilkode
			]);
    	}

    	else
    	{
			// parsing kode
    		$nilaikode = substr($datakode, strlen($prefix));
    		$kode = (int) $nilaikode;
			$kode = $kode + 1;
			$hasilkode = $prefix.str_pad($kode, 4, "0", STR_PAD_LEFT);

			// update data counter
			DB::table("tbl_counter")
            ->where('tabel', "=", $table)
            ->update
            ([
                'counter' => $hasilkode,
            ]);
    	}

    	return $hasilkode;

    }

    public static function generateKodeMax($kode, $param, $table)
	{

		if($kode =="" || $param == "" || $table == "")
		{
			echo "Tidak dapat menggenerate data Kode Otomatis";
			return;
		}

		$autonum = "";

        $nomer = DB::table($table)
					->max($kode);

		$autonum = $nomer;

		# Cek Parameter
		if($autonum == "")
		{
			$autonum = $param."00001";
		}
		else
		{
			$autonum = (int) substr($autonum, strlen($param), 6);
			$autonum++;
			$autonum =  $param.sprintf("%05s", $autonum);
		}

		return $autonum;
	}

	// public static function cariData($table, $field, $value)
    // {
    // 	$hasil = DB::table($table)->where($field, $value)->first();

    //     if(empty($data->$hasil))
    //     {
    //         $hasil = "";
    //         return;
    //     }

    //     return $hasil;
    // }

	public static function cariData2($get, $field, $value, $tabel)
    {
    	$iRes = "";
        $data = DB::table($tabel)
                ->select($get)
                ->where($field, '=', $value)
                ->first();

        if(empty($data->$get))
        {
        	$iRes = "";
        	return $iRes;
        }

		$iRes = $data->$get;
    	return $iRes;

    // 	$iRes = "";
    //     $sql = "SELECT * FROM $tabel
				// WHERE $field = '$value'";

    //     $results = DB::select(DB::raw($sql));

    //     if(empty($results[0]->$get))
    //     {
    //     	$iRes = "";
    //     	return $iRes;
    //     }

    //     $iRes = $results[0]->$get;
    //     return $iRes;

    }

    public static function cekPeriodePenilaian($periode, $kodepegawai)
    {
    	$periode = date('Y-m', strtotime($periode));
    	$sql = "SELECT count(*) as jml FROM tbl_penilaian
				WHERE (DATE_FORMAT(periode, '%Y-%m') = '$periode'
				AND kodepegawai = '$kodepegawai')";

        $results = DB::select(DB::raw($sql));

        return $results[0]->jml;
    }

    public static function cekSisaCuti($tahun, $nip)
    {
    	$sql = "SELECT SUM(DATEDIFF(tanggalselesai, tanggalmulai)) as jml FROM tbl_permohonan_cuti
				WHERE (DATE_FORMAT(tanggalmulai, '%Y') = '$tahun'
				AND nip = '$nip' AND statuspermohonan = 'DITERIMA')";

        $results = DB::select(DB::raw($sql));

        $jumlah = $results[0]->jml;
        $sisa_cuti = 20 - $jumlah;

        return $sisa_cuti;
    }

    public static function cekJumlahCuti($tahun, $nip)
    {
    	$sql = "SELECT SUM(DATEDIFF(tanggalselesai, tanggalmulai)) as jml FROM tbl_permohonan_cuti
				WHERE (DATE_FORMAT(tanggalmulai, '%Y') = '$tahun'
				AND nip = '$nip' AND statuspermohonan = 'DITERIMA')";

        $results = DB::select(DB::raw($sql));

        $jumlah = $results[0]->jml;

        return $jumlah;
    }

    public static function SelisihTanggal($datefrom, $dateto)
    {
  //   	$ts1 = strtotime($date1);
		// $ts2 = strtotime($date2);

		// $seconds_diff = $ts2 - $ts1;

		$date1 = date_create($datefrom);
		$date2 = date_create($dateto);
		$diff = date_diff($date1, $date2);

		return $diff->format('%a');
    }

    public static function DropDown($value, $display, $tabel)
    {
        $rows = DB::table($tabel)
                ->select('*')
                ->get();

        $temp = "";

        $temp .= "<option value=''>Pilih..</option>";

        foreach ($rows as $row)
        {
			$temp .= "<option value='".$row->$value."'>".$row->$display."</option>";
		}

        return $temp;
    }

    public static function DropDownJenis()
    {
        $temp = "";
        $temp .= "<option value=''>Pilih..</option>";
        $temp .= "<option value='FISIK'>Fisik</option>";
        $temp .= "<option value='NON FISIK'>Non Fisik</option>";

        return $temp;
    }

    public static function DropDownSatuan()
    {
        $temp = "";
        $temp .= "<option value=''>Pilih..</option>";
        $temp .= "<option value='PCS'>PCS</option>";
        $temp .= "<option value='DUS'>DUS</option>";
        $temp .= "<option value='BOX'>BOX</option>";
        $temp .= "<option value='LUSIN'>LUSIN</option>";

        return $temp;
    }
    public static function DropDownStatusAktif()
    {
        $temp = "";
        $temp .= "<option value=''>Pilih..</option>";
        $temp .= "<option value='1'>Aktif</option>";
        $temp .= "<option value='0'>Tidak Aktif</option>";

        return $temp;
    }

    public static function DropDownStatusPelanggan()
    {
        $temp = "";
        $temp .= "<option value=''>Pilih..</option>";
        $temp .= "<option value='0'>Pending</option>";
        $temp .= "<option value='1'>Aktif</option>";
        $temp .= "<option value='3'>Ditolak</option>";

        return $temp;
    }

    public static function DropDownStatus($value, $display, $tabel, $statusfield)
    {
        $rows = DB::table($tabel)
                ->select('*')
                ->where($statusfield, "=", 1)
                ->get();

        $temp = "";

        $temp .= "<option value=''>Pilih..</option>";

        foreach ($rows as $row)
        {
            $temp .= "<option value='".$row->$value."'>".$row->$display."</option>";
        }

        return $temp;
    }

    public static function DropDownRestoran($id_user_client)
    {
        $rows = DB::table('restoran')
                ->select('*')
                ->where('status_aktif', "=", 1)
                ->where('id_user_client', "=", $id_user_client)
                ->get();

        $temp = "";

        $temp .= "<option value=''>Pilih Data..</option>";

        foreach ($rows as $row)
        {
			$temp .= "<option value='".$row->id_restoran."'>".$row->nama_restoran."</option>";
		}

        return $temp;
    }

    public static function DropDownPegawai()
    {
        $rows = DB::table('tbl_pegawai')
                ->select('*')
                ->where('nip', '<>', session('nip'))
                ->get();

        $temp = "";

        foreach ($rows as $row)
        {
			$temp .= "<option value='".$row->nip."'>".$row->namauser." (".$row->nip.")</option>";
		}

        return $temp;
    }

	public static function addlog($tabel, $aksi, $kode)
    {
		DB::table("tbl_log")->insert([[
            'user' => Cfilter::FilterString(session('kodeadmin')),
            'tabel' => Cfilter::FilterString($tabel),
            'aksi' => Cfilter::FilterString($aksi),
            'kode' => Cfilter::FilterString($kode),
            'datelog' => Cfilter::FilterString(date("Y-m-d H:i")),
        ]]);
    }

    public static function getJumlahKonsultan($periode)
    {
        $sql = "SELECT COUNT(*) as total FROM user_konsultan
                WHERE DATE_FORMAT(created_at, '%Y-%m') = '$periode' AND status_aktif = 1";

        $results = DB::select(DB::raw($sql));

        if(empty($results[0]->total))
        {
            $iRes = 0;
        }
        else
        {
            $iRes = $results[0]->total;
        }

        return $iRes;
    }


    public static function getTotalOrder($kodebrand, $tahun, $bulan)
    {
        // cari data pending
        $Sql = "SELECT SUM(tbl_order.total) as totalall FROM tbl_order
                WHERE (DATE_FORMAT(tbl_order.tglorder, '%Y') = '$tahun') AND (tbl_order.kodebrand = '$kodebrand')
                AND (tbl_order.statusorder IN (1, 3, 4, 5, 6))
                ";

        if(!empty($bulan))
        {
            $Sql .= " AND (DATE_FORMAT(tbl_order.tglorder, '%m') = '$bulan') ";
        }

        $row = DB::select(DB::raw($Sql));

        if(empty($row[0]->totalall))
        {
            $iRes = 0;
        }
        else
        {
            $iRes = $row[0]->totalall;
        }

        return $iRes;
    }

    public static function getDetailOrder($kodeorder)
    {
        $iRes = "";
        $Sql = "SELECT * FROM tbl_order_detail
                INNER JOIN tbl_jenis_barang ON (tbl_jenis_barang.kodejenisbarang = tbl_order_detail.kodejenisbarang)
                INNER JOIN tbl_pengepul ON (tbl_pengepul.kodepengepul = tbl_order_detail.kodepengepul)
                WHERE (tbl_order_detail.kodeorder = '$kodeorder')";

        $rows = DB::select(DB::raw($Sql));

        $no = 1;
        $arr = [];
        foreach ($rows as $row)
        {

            $total = ($row->qty * $row->harga);

            $arr[] = "
            <tr>
                <td>$no</td>
                <td>$row->namajenisbarang</td>
                <td>$row->namapengepul</td>
                <td>$row->qty</td>
                <td>".number_format($row->harga)."</td>
                <td>".number_format($total)."</td>
                <td>$row->namabarang</td>
                <td>$row->warna</td>
                <td>$row->tglambil</td>
                <td>$row->tglsetor</td>
                <td>$row->size</td>
            </tr>
            ";

            $no++;
        }

        $iRes = join($arr);
        return $iRes;
    }

    public static function UpdateStok($kodeitem, $jumlah)
    {
        $stokLama = intval(self::cariData2("stok", "kodeitem", $kodeitem, "tbl_item"));
        $stokBaru = $stokLama + $jumlah;

        DB::table("tbl_item")
        ->where('kodeitem', "=", $kodeitem)
        ->update
        ([
            'stok' => $stokBaru,
        ]);

        return $stokBaru;
    }

    public static function UpdateStokUpd($kodetransaksi, $jenis, $jmlNew)
    {
        if($jenis == "ITEMMASUK")
        {
            $kodeitem = self::cariData2("kodeitem", "kodeitemmasukdt", $kodetransaksi, "tbl_itemmasukdt");
            $jumlahOld = self::cariData2("jumlah", "kodeitemmasukdt", $kodetransaksi, "tbl_itemmasukdt");

            $stokLama = self::cariData2("stok", "kodeitem", $kodeitem, "tbl_item");
            $stokBaru = $stokLama + $jmlNew - $jumlahOld;
        }

        elseif($jenis == "ITEMKELUAR")
        {
            $kodeitem = self::cariData2("kodeitem", "kodeitemkeluardt", $kodetransaksi, "tbl_itemkeluardt");
            $jumlahOld = self::cariData2("jumlah", "kodeitemkeluardt", $kodetransaksi, "tbl_itemkeluardt");

            $stokLama = self::cariData2("stok", "kodeitem", $kodeitem, "tbl_item");
            $stokBaru = $stokLama + $jumlahOld - $jmlNew;
        }

        DB::table("tbl_item")
        ->where('kodeitem', "=", $kodeitem)
        ->update
        ([
            'stok' => $stokBaru,
        ]);

        return $stokBaru;
    }

    public static function cekStatusAktif($status)
    {
        if($status == 0)
        {
            $res = "Tidak Aktif";
        }
        else
        {
            $res = "Aktif";
        }

        return $res;
    }
}
