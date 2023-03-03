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

class aItem extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "tbl_item";
    	$this->prefix = "item";
    	$this->iconpage = "fa fa-tag";
    	$this->pagename = "Item";

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
		$data['rows'] = mItem::select('*')
						->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
						// ->where("tbl_item.statusitem", "=", 1)
						->get();

        return view("$this->prefix/list", $data);
    }

    public function stoklist(Request $request)
    {
		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>Item Stok</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Item Stok";

		// passing function ke view
		$data['rows'] = mItem::select('*')
						->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
						->where("tbl_item.statusitem", "=", 1)
						->get();

        return view("$this->prefix/stok_list", $data);
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

        $data['rows'] = mItem::select('*')
                        ->where('kodeitem', '=', $id)
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
			$kodeitem = Csql::generateKodeMax("kodeitem", "ITM", $this->baseTable);
			$gambar = Cupload::UploadGambar('gambar', '', $request);

			$data = new mItem;
			$data->kodeitem = $kodeitem;
			$data->kodekategori = Cfilter::FilterString($request->input('kodekategori'));
			$data->namaitem = Cfilter::FilterString($request->input('namaitem'));
			$data->satuan = Cfilter::FilterString($request->input('satuan'));
			$data->keterangan = Cfilter::FilterString($request->input('keterangan'));
			$data->stok = Cfilter::FilterInt($request->input('stok'));
			$data->stokminimum = Cfilter::FilterInt($request->input('stokminimum'));
			$data->gambar = Cfilter::FilterString($gambar);
			$data->statusitem = Cfilter::FilterInt($request->input('statusitem'));
			$data->save();

            DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Tambah Data : <b>".$request->input('namaitem')."</b>");
			return redirect()->action([aItem::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItem::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function actedit(Request $request)
    {
        // Update Data

		DB::beginTransaction();

		try {

			$id = Cfilter::FilterString($request->input('kodeitem'));
			$gambar_old = Csql::cariData2("gambar", "kodeitem", $id, $this->baseTable);
            $gambar = Cupload::UploadGambar('gambar', $gambar_old, $request);

			$data = mItem::find($id);
			$data->kodekategori = Cfilter::FilterString($request->input('kodekategori'));
			$data->namaitem = Cfilter::FilterString($request->input('namaitem'));
			$data->satuan = Cfilter::FilterString($request->input('satuan'));
			$data->keterangan = Cfilter::FilterString($request->input('keterangan'));
			$data->stok = Cfilter::FilterInt($request->input('stok'));
			$data->stokminimum = Cfilter::FilterInt($request->input('stokminimum'));
			$data->gambar = Cfilter::FilterString($gambar);
			$data->statusitem = Cfilter::FilterInt($request->input('statusitem'));
			$data->save();

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Update Data : <b>".$request->input('namaitem')."</b>");
			return redirect()->action([aItem::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Update Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItem::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function acthapus($id)
    {
		DB::beginTransaction();

        try
        {
			$data = mItem::find($id);
			$data->statusitem = Cfilter::FilterInt(0);
			$data->save();

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Hapus Data : <b>".$id."</b>");
			return redirect()->action([aItem::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Hapus Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItem::class, 'list'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

}
?>
