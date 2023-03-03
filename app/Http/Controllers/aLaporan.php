<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

// load library
use App\Lib\Csql;
use App\Lib\Cupload;
use App\Lib\Cfilter;
use App\Lib\Cview;

use PDF;

// model
use App\Models\mItem;
use App\Models\mItemMasuk;
use App\Models\mItemKeluar;
use App\Models\mPelanggan;
use App\Models\mSupplier;

class aLaporan extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "";
    	$this->prefix = "laporan";
    	$this->pagename = "Laporan";
    }

    // ================= STOK ITEM ===================================
    public function stokitem(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
		$data['katakunci'] = $katakunci;

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Laporan Stok Item</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Laporan Stok Item";

		// passing function ke view
		$rows = mItem::select('*')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where('tbl_item.statusitem', '=', 1)
				->where(function($query) use($katakunci) {
                    $query->where('tbl_item.kodeitem', 'like', '%'.$katakunci.'%')
                        ->orWhere('tbl_item.namaitem', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

        return view("$this->prefix/stokitem", $data);
    }

    public function grafik_stokitem(Request $request)
    {
        $bentuk = Cfilter::FilterString($request->input('bentuk'));
    	$katakunci = Cfilter::FilterString($request->input('katakunci'));

        if(empty($bentuk))
		{
			$bentuk = 'line';
		}

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Grafik Stok Item</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Grafik Stok Item";

		// passing function ke view
		$rows = mItem::select('*')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where('tbl_item.statusitem', '=', 1)
		        ->get();

        $categories = [];
        $series = [];
        $piecategories = [];

        foreach ($rows as $row)
        {
            $categories[] = "'$row->namaitem'";
            $series[] = $row->stok;

            // khusus pie
            $piecategories[] = "{name: '$row->namaitem', y: $row->stok }";
        }

        $data['rows'] = $rows;
        $data['katakunci'] = $katakunci;
        $data['bentuk'] = $bentuk;

        $data['categories'] = join(",", $categories);
        $data['series'] = join(",", $series);
        $data['piecategories'] = join(",", $piecategories);

        return view("$this->prefix/grafik_stokitem", $data);
    }

    public function cetak_stokitem(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));

    	$data['judul'] = "Laporan Stok Item";

    	$data['keterangan'] = "";

    	if(!empty($katakunci))
    	{
    		$data['keterangan'] = "katakunci : $katakunci <br />";
    	}

    	$data['keterangan'] .= "laporan cetak stok item UD. BUNGAN JEPUN.";

    	// passing function ke view
		$rows = mItem::select('*')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where('tbl_item.statusitem', '=', 1)
				->where(function($query) use($katakunci) {
                    $query->where('tbl_item.kodeitem', 'like', '%'.$katakunci.'%')
                        ->orWhere('tbl_item.namaitem', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

    	$pdf = PDF::loadView('laporan/cetak_stokitem', $data)
               ->setPaper('a4', 'landscape');

		return $pdf->stream();
    }



    // ================= ITEM MASUK ===================================
    public function itemmasuk(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
        $tglorder_dari = Cfilter::FilterString($request->input('tglorder_dari'));
		$tglorder_sampai = Cfilter::FilterString($request->input('tglorder_sampai'));

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Laporan Item Masuk</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Laporan Item Masuk";

        if(empty($tglorder_dari))
		{
			$tglorder_dari = date("Y-m-01");
		}

		if(empty($tglorder_sampai))
		{
			$tglorder_sampai = date("Y-m-t");
		}

		// passing function ke view
		$rows = mItemMasuk::select('*')
                ->join('tbl_user', 'tbl_user.kodeuser', '=', 'tbl_itemmasukhd.kodeuser')
                ->join('tbl_supplier', 'tbl_supplier.kodesupplier', '=', 'tbl_itemmasukhd.kodesupplier')
                ->whereBetween('tbl_itemmasukhd.tanggalitemmasuk', [$tglorder_dari, $tglorder_sampai])
				->where(function($query) use($katakunci) {
                    $query->where('tbl_supplier.namasupplier', 'like', '%'.$katakunci.'%')
                        ->orWhere('tbl_itemmasukhd.kodeitemmasuk', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;
        $data['katakunci'] = $katakunci;
        $data['tglorder_dari'] = $tglorder_dari;
        $data['tglorder_sampai'] = $tglorder_sampai;

        return view("$this->prefix/itemmasuk", $data);
    }

    public function grafik_itemmasuk(Request $request)
    {
        $tahun = Cfilter::FilterString($request->input('tahun'));
        $bentuk = Cfilter::FilterString($request->input('bentuk'));

        if(empty($bentuk))
		{
			$bentuk = 'line';
		}

        if(empty($tahun))
		{
			$tahun = date("Y");
		}

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Grafik Item Masuk</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Grafik Item Masuk";

		// passing function ke view
		$rows = mItemMasuk::select(DB::raw("DATE_FORMAT(tanggalitemmasuk, '%M') as bulan, COUNT(*) as totalall"))
                ->whereYear('tanggalitemmasuk', $tahun)
                ->groupBy(DB::raw("DATE_FORMAT(tanggalitemmasuk, '%Y-%M')"))
                ->orderByRaw("tanggalitemmasuk ASC")
		        ->get();

        $categories = [];
        $series = [];
        $piecategories = [];

        foreach ($rows as $row)
        {
            $categories[] = "'$row->bulan'";
            $series[] = $row->totalall;

            // khusus pie
            $piecategories[] = "{name: '$row->bulan', y: $row->totalall }";
        }

        $data['tahun'] = $tahun;
		$data['bentuk'] = $bentuk;

        $data['categories'] = join(",", $categories);
        $data['series'] = join(",", $series);
        $data['piecategories'] = join(",", $piecategories);

        return view("$this->prefix/grafik_itemmasuk", $data);
    }

    public function cetak_itemmasuk(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
        $tglorder_dari = Cfilter::FilterString($request->input('tglorder_dari'));
		$tglorder_sampai = Cfilter::FilterString($request->input('tglorder_sampai'));

    	$data['judul'] = "Laporan Item Masuk";

    	$data['keterangan'] = "";

    	if(!empty($katakunci))
    	{
    		$data['keterangan'] = "katakunci : $katakunci <br />";
    	}

        $data['keterangan'] .= "tanggal item masuk $tglorder_dari s/d $tglorder_sampai";

    	// passing function ke view
		$rows = mItemMasuk::select('*')
                ->join('tbl_user', 'tbl_user.kodeuser', '=', 'tbl_itemmasukhd.kodeuser')
                ->join('tbl_supplier', 'tbl_supplier.kodesupplier', '=', 'tbl_itemmasukhd.kodesupplier')
                ->whereBetween('tbl_itemmasukhd.tanggalitemmasuk', [$tglorder_dari, $tglorder_sampai])
				->where(function($query) use($katakunci) {
                    $query->where('tbl_supplier.namasupplier', 'like', '%'.$katakunci.'%')
                        ->orWhere('tbl_itemmasukhd.kodeitemmasuk', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

    	$pdf = PDF::loadView('laporan/cetak_itemmasuk', $data)
               ->setPaper('a4', 'landscape');

		return $pdf->stream();
    }


    // ================= ITEM KELUAR ===================================
    public function itemkeluar(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
        $tglorder_dari = Cfilter::FilterString($request->input('tglorder_dari'));
		$tglorder_sampai = Cfilter::FilterString($request->input('tglorder_sampai'));

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Laporan Item Keluar</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Laporan Item Keluar";

        if(empty($tglorder_dari))
		{
			$tglorder_dari = date("Y-m-01");
		}

		if(empty($tglorder_sampai))
		{
			$tglorder_sampai = date("Y-m-t");
		}

		// passing function ke view
		$rows = mItemKeluar::select('*')
                ->join('tbl_user', 'tbl_user.kodeuser', '=', 'tbl_itemkeluarhd.kodeuser')
                ->join('tbl_pelanggan', 'tbl_pelanggan.kodepelanggan', '=', 'tbl_itemkeluarhd.kodepelanggan')
                ->whereBetween('tbl_itemkeluarhd.tanggalitemkeluar', [$tglorder_dari, $tglorder_sampai])
				->where(function($query) use($katakunci) {
                    $query->where('tbl_pelanggan.namapelanggan', 'like', '%'.$katakunci.'%')
                        ->orWhere('tbl_itemkeluarhd.kodeitemkeluar', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;
        $data['katakunci'] = $katakunci;
        $data['tglorder_dari'] = $tglorder_dari;
        $data['tglorder_sampai'] = $tglorder_sampai;

        return view("$this->prefix/itemkeluar", $data);
    }

    public function grafik_itemkeluar(Request $request)
    {
        $tahun = Cfilter::FilterString($request->input('tahun'));
        $bentuk = Cfilter::FilterString($request->input('bentuk'));

        if(empty($bentuk))
		{
			$bentuk = 'line';
		}

        if(empty($tahun))
		{
			$tahun = date("Y");
		}

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Grafik Item Keluar</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Grafik Item Keluar";

		// passing function ke view
		$rows = mItemKeluar::select(DB::raw("DATE_FORMAT(tanggalitemkeluar, '%M') as bulan, tanggalitemkeluar, COUNT(tanggalitemkeluar) as totalall"))
                ->whereYear('tanggalitemkeluar', $tahun)
                ->groupBy(DB::raw("DATE_FORMAT(tanggalitemkeluar, '%Y-%M')"))
                ->orderByRaw("tanggalitemkeluar ASC")
		        ->get();

        $categories = [];
        $series = [];
        $piecategories = [];

        foreach ($rows as $row)
        {
            $categories[] = "'$row->bulan'";
            $series[] = $row->totalall;

            // khusus pie
            $piecategories[] = "{name: '$row->bulan', y: $row->totalall }";
        }

        $data['tahun'] = $tahun;
		$data['bentuk'] = $bentuk;

        $data['categories'] = join(",", $categories);
        $data['series'] = join(",", $series);
        $data['piecategories'] = join(",", $piecategories);

        return view("$this->prefix/grafik_itemkeluar", $data);
    }

    public function cetak_itemkeluar(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
        $tglorder_dari = Cfilter::FilterString($request->input('tglorder_dari'));
		$tglorder_sampai = Cfilter::FilterString($request->input('tglorder_sampai'));

    	$data['judul'] = "Laporan Item Keluar";

    	$data['keterangan'] = "";

    	if(!empty($katakunci))
    	{
    		$data['keterangan'] = "katakunci : $katakunci <br />";
    	}

        $data['keterangan'] .= "tanggal item keluar $tglorder_dari s/d $tglorder_sampai";

    	// passing function ke view
		$rows = mItemKeluar::select('*')
                ->join('tbl_user', 'tbl_user.kodeuser', '=', 'tbl_itemkeluarhd.kodeuser')
                ->join('tbl_pelanggan', 'tbl_pelanggan.kodepelanggan', '=', 'tbl_itemkeluarhd.kodepelanggan')
                ->whereBetween('tbl_itemkeluarhd.tanggalitemkeluar', [$tglorder_dari, $tglorder_sampai])
				->where(function($query) use($katakunci) {
                    $query->where('tbl_pelanggan.namapelanggan', 'like', '%'.$katakunci.'%')
                        ->orWhere('tbl_itemkeluarhd.kodeitemkeluar', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

    	$pdf = PDF::loadView('laporan/cetak_itemkeluar', $data)
               ->setPaper('a4', 'landscape');

		return $pdf->stream();
    }


    // ================= PELANGGAN ===================================
    public function pelanggan(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
		$data['katakunci'] = $katakunci;

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Laporan Pelanggan</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Laporan Pelanggan";

		// passing function ke view
		$rows = mPelanggan::select('*')
                ->where('statuspelanggan', '=', 1)
				->where(function($query) use($katakunci) {
                    $query->where('kodepelanggan', 'like', '%'.$katakunci.'%')
                        ->orWhere('namapelanggan', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

        return view("$this->prefix/pelanggan", $data);
    }

    public function cetak_pelanggan(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));

    	$data['judul'] = "Laporan Pelanggan";

    	$data['keterangan'] = "";

    	if(!empty($katakunci))
    	{
    		$data['keterangan'] = "katakunci : $katakunci <br />";
    	}

    	$data['keterangan'] .= "laporan cetak pelanggan UD. BUNGAN JEPUN.";

    	// passing function ke view
		$rows = mPelanggan::select('*')
                ->where('statuspelanggan', '=', 1)
				->where(function($query) use($katakunci) {
                    $query->where('kodepelanggan', 'like', '%'.$katakunci.'%')
                        ->orWhere('namapelanggan', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

    	$pdf = PDF::loadView('laporan/cetak_pelanggan', $data)
               ->setPaper('a4', 'landscape');

		return $pdf->stream();
    }

    // ================= SUPPLIER ===================================
    public function supplier(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));
		$data['katakunci'] = $katakunci;

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Laporan Supplier</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Laporan Supplier";

		// passing function ke view
		$rows = mSupplier::select('*')
                ->where('statussupplier', '=', 1)
				->where(function($query) use($katakunci) {
                    $query->where('kodesupplier', 'like', '%'.$katakunci.'%')
                        ->orWhere('namasupplier', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

        return view("$this->prefix/supplier", $data);
    }

    public function cetak_supplier(Request $request)
    {

    	$katakunci = Cfilter::FilterString($request->input('katakunci'));

    	$data['judul'] = "Laporan Supplier";

    	$data['keterangan'] = "";

    	if(!empty($katakunci))
    	{
    		$data['keterangan'] = "katakunci : $katakunci <br />";
    	}

    	$data['keterangan'] .= "laporan cetak supplier UD. BUNGAN JEPUN.";

    	// passing function ke view
		$rows = mSupplier::select('*')
                ->where('statussupplier', '=', 1)
				->where(function($query) use($katakunci) {
                    $query->where('kodesupplier', 'like', '%'.$katakunci.'%')
                        ->orWhere('namasupplier', 'like', '%'.$katakunci.'%');
				})
		        ->get();

        $data['rows'] = $rows;

    	$pdf = PDF::loadView('laporan/cetak_supplier', $data)
               ->setPaper('a4', 'landscape');

		return $pdf->stream();
    }

}
?>
