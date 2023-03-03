<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// load library
use App\Lib\Csql;
use App\Lib\Cupload;
use App\Lib\Cfilter;
use App\Lib\Cview;

// model
use App\Models\mItem;
use App\Models\mKategori;
use App\Models\mSupplier;
use App\Models\mPelanggan;
use App\Models\mItemMasuk;
use App\Models\mItemKeluar;

class aDashboard extends Controller
{
	public function __construct()
    {
        $this->middleware('ceklogin');
    }

    public function index()
    {
		// nama title
    	$data['pagename'] = "Dashboard";

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("admin/dashboard")."'>Dashboard</a></li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['headname'] = "Halaman Dashboard";
		$data['description'] = "berikut ini adalah halaman dashboard";

		// jumlah data
		$data['jml_item'] = mItem::where('statusitem', '=', 1)->count();
		$data['jml_kategori'] = mKategori::where('statuskategori', '=', 1)->count();
		$data['jml_supplier'] = mSupplier::where('statussupplier', '=', 1)->count();
		$data['jml_pelanggan'] = mPelanggan::where('statuspelanggan', '=', 1)->count();
		$data['jml_item_masuk'] = mItemMasuk::count();
		$data['jml_item_keluar'] = mItemKeluar::count();

        return view('dashboard/dashboardmenu', $data);
    }
}
?>
