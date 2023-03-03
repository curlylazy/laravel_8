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
use App\Models\mPelanggan;

class aPelanggan extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "tbl_pelanggan";
    	$this->prefix = "pelanggan";
    	$this->iconpage = "fa fa-tag";
    	$this->pagename = "Daftar Pelanggan";

    	// cek apakah sudah login atau belum
    	$this->middleware('ceklogin');
    }

    public function list(Request $request)
    {
		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>$this->pagename</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = $this->pagename;

		// passing function ke view
		$data['rows'] = mPelanggan::select('*')
						// ->where("statuspelanggan", "=", 1)
						->get();

        return view("$this->prefix/list", $data);
    }

	public function tambah()
    {
		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("$this->prefix/list")."'>$this->pagename</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'>Tambah</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Tambah ". $this->pagename;
		$data['aksi'] = "acttambah";

		// paramerter error
		$data['pesaninfo'] = "";
		$data['iserror'] = false;

        return view("$this->prefix/tambah", $data);
    }

    public function edit($id)
    {
		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'> Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("$this->prefix/list")."'>$this->pagename</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'>Edit</li>";
		$breadcrumb []= "<li class='breadcrumb-item'><b>$id</b></li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = $this->pagename;
		$data['aksi'] = "actedit";

		// paramerter error
		$data['pesaninfo'] = "";
		$data['iserror'] = false;

        $data['rows'] = mPelanggan::select('*')
                        ->where('kodepelanggan', '=', $id)
                        ->first();

        return view("$this->prefix/tambah", $data);
    }

    public function acttambah(Request $request)
    {

		// pass request
		$data['request'] = $request;

		DB::beginTransaction();

		try
		{
			$kodepelanggan = Csql::generateKodeMax("kodepelanggan", "PEL", $this->baseTable);

			$data = new mPelanggan;
			$data->kodepelanggan = $kodepelanggan;
			$data->namapelanggan = Cfilter::FilterString($request->input('namapelanggan'));
			$data->noteleponpelanggan = Cfilter::FilterString($request->input('noteleponpelanggan'));
			$data->alamatpelanggan = Cfilter::FilterString($request->input('alamatpelanggan'));
			$data->statuspelanggan = Cfilter::FilterInt($request->input('statuspelanggan'));
			$data->save();

            DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Tambah Data : <b>".$request->input('namapelanggan')."</b>");
			return redirect()->action([aPelanggan::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aPelanggan::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function actedit(Request $request)
    {
        // Update Data

		DB::beginTransaction();

		try {

			$id = Cfilter::FilterString($request->input('kodepelanggan'));

			$data = mPelanggan::find($id);
			$data->namapelanggan = Cfilter::FilterString($request->input('namapelanggan'));
			$data->noteleponpelanggan = Cfilter::FilterString($request->input('noteleponpelanggan'));
			$data->alamatpelanggan = Cfilter::FilterString($request->input('alamatpelanggan'));
			$data->statuspelanggan = Cfilter::FilterInt($request->input('statuspelanggan'));
			$data->save();

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Update Data : <b>".$request->input('namapelanggan')."</b>");
			return redirect()->action([aPelanggan::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Update Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aPelanggan::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function acthapus($id)
    {
		DB::beginTransaction();

        try
        {
			$data = mPelanggan::find($id);
			$data->statuspelanggan = Cfilter::FilterInt(0);
			$data->save();

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Hapus Data : <b>".$id."</b>");
			return redirect()->action([aPelanggan::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Hapus Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aPelanggan::class, 'list'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

}
?>
