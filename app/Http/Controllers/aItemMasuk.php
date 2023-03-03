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
use App\Models\mItemMasuk;
use App\Models\mItemMasukDetail;

class aItemMasuk extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "tbl_itemmasukhd";
    	$this->prefix = "itemmasuk";
    	$this->iconpage = "fa fa-tag";
    	$this->pagename = "Daftar Item Masuk";

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
		$data['rows'] = mItemMasuk::select('*')
                        ->join('tbl_user', 'tbl_user.kodeuser', '=', 'tbl_itemmasukhd.kodeuser')
                        ->join('tbl_supplier', 'tbl_supplier.kodesupplier', '=', 'tbl_itemmasukhd.kodesupplier')
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

        $data['rows_item'] = mItem::select('tbl_item.kodeitem', 'tbl_item.namaitem', 'tbl_item.satuan', 'tbl_kategori.namakategori')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where("tbl_item.statusitem", "=", 1)
				->get();

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Tambah ". $this->pagename;
		$data['aksi'] = "acttambah";

		// paramerter error
		$data['pesaninfo'] = "";
		$data['iserror'] = false;

		$data['noRow'] = 0;

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

		$data['noRow'] = mItemMasukDetail::where('kodeitemmasuk', '=', $id)->count();

		$data['rows_item'] = mItem::select('tbl_item.kodeitem', 'tbl_item.namaitem', 'tbl_item.satuan', 'tbl_kategori.namakategori')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where("tbl_item.statusitem", "=", 1)
				->get();

        $data['rows'] = mItemMasuk::select('*')
                        ->where('kodeitemmasuk', '=', $id)
                        ->first();

		$data['rows_detail'] = mItemMasukDetail::select('*')
						->join('tbl_item', 'tbl_item.kodeitem', '=', 'tbl_itemmasukdt.kodeitem')
						->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
						->where("tbl_itemmasukdt.kodeitemmasuk", "=", $id)
						->get();

        return view("$this->prefix/tambah", $data);
    }

    public function acttambah(Request $request)
    {

		// pass request
		$data['request'] = $request;

		DB::beginTransaction();

		try
		{
			$kodeitemmasuk = Csql::generateKodeMax("kodeitemmasuk", "IM", $this->baseTable);

			$data = new mItemMasuk;
			$data->kodeitemmasuk = $kodeitemmasuk;
			$data->kodesupplier = Cfilter::FilterString($request->input('kodesupplier'));
			$data->kodeuser = Cfilter::FilterString($request->input('kodeuser'));
			$data->tanggalitemmasuk = Cfilter::FilterString($request->input('tanggalitemmasuk'));
			$data->keteranganitemmasuk = Cfilter::FilterString($request->input('keteranganitemmasuk'));
			$data->save();

            $jml = Cfilter::FilterInt($request->input('jml'));
            for($i=1;$i<=$jml;$i++)
            {
				$kodeitem = Cfilter::FilterString($request->input("kodeitem_$i"));
				$jml = Cfilter::FilterInt($request->input("jumlah_$i"));

				if(!empty($kodeitem))
				{
					$data = new mItemMasukDetail;
					$data->kodeitemmasukdt = \uniqid();
					$data->kodeitemmasuk = $kodeitemmasuk;
					$data->kodeitem = $kodeitem;
					$data->jumlah = $jml;
					$data->save();

					Csql::UpdateStok($kodeitem, $jml);
				}
            }

            DB::commit();

            // jika berhasil
            $this->pesaninfo = Cview::pesanSukses("Berhasil Tambah Data : <b>".$kodeitemmasuk."</b>");
            return redirect()->action([aItemMasuk::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItemMasuk::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function actedit(Request $request)
    {
        // Update Data

		DB::beginTransaction();

		try {

			$id = Cfilter::FilterString($request->input('kodeitemmasuk'));

			$data = mItemMasuk::find($id);
			$data->kodesupplier = Cfilter::FilterString($request->input('kodesupplier'));
			$data->tanggalitemmasuk = Cfilter::FilterString($request->input('tanggalitemmasuk'));
			$data->keteranganitemmasuk = Cfilter::FilterString($request->input('keteranganitemmasuk'));
			$data->save();

			$jml = Cfilter::FilterInt($request->input('jml'));
            for($i=1;$i<=$jml;$i++)
            {
				$status = Cfilter::FilterString($request->input("status_$i"));
				$kodeitemmasukdt = Cfilter::FilterString($request->input("kodeitemmasukdt_$i"));
				$kodeitem = Cfilter::FilterString($request->input("kodeitem_$i"));
				$jumlah = Cfilter::FilterInt($request->input("jumlah_$i"));

				if(!empty($kodeitem))
				{
					if($status == "**new")
					{
						$data = new mItemMasukDetail;
						$data->kodeitemmasukdt = \uniqid();
						$data->kodeitemmasuk = $id;
						$data->kodeitem = $kodeitem;
						$data->jumlah = $jumlah;
						$data->save();

						Csql::UpdateStok($kodeitem, $jumlah);
					}
					else
					{
						Csql::UpdateStokUpd($kodeitemmasukdt, "ITEMMASUK", $jumlah);

						$data = mItemMasukDetail::find($kodeitemmasukdt);
						$data->jumlah = $jumlah;
						$data->save();
					}
				}
            }

			// hapus data yg dipilih
			$jmldel = Cfilter::FilterInt($request->input('jmldel'));
            for($i=1;$i<=$jmldel;$i++)
            {
				$kodeitemmasukdt = Cfilter::FilterString($request->input("del_kodeitemmasukdt_$i"));
				$kodeitem = Cfilter::FilterString($request->input("del_kodeitem_$i"));
				$jumlah = Cfilter::FilterInt($request->input("del_jumlah_$i"));

				Csql::UpdateStok($kodeitem, $jumlah * -1);

				$data = mItemMasukDetail::where('kodeitemmasukdt', $kodeitemmasukdt);
				$data->delete();
			}

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Update Data : <b>".$id."</b>");
			return redirect()->action([aItemMasuk::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			// echo $ex->getMessage();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Update Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItemMasuk::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function acthapus($id)
    {
		DB::beginTransaction();

        try
        {
            // kembalikan stok seperti semula
            $rows_detail = mItemMasukDetail::select('*')
                ->join('tbl_item', 'tbl_item.kodeitem', '=', 'tbl_itemmasukdt.kodeitem')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where("tbl_itemmasukdt.kodeitemmasuk", "=", $id)
                ->get();

            foreach ($rows_detail as $row)
            {
                Csql::UpdateStok($row->kodeitem, $row->jumlah * - 1);

            }

			mItemMasukDetail::where('kodeitemmasuk', $id)->delete();
			mItemMasuk::where('kodeitemmasuk', $id)->delete();

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Hapus Data : <b>".$id."</b>");
			return redirect()->action([aItemMasuk::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Hapus Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItemMasuk::class, 'list'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

}
?>
