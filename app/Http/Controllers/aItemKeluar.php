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
use App\Models\mItemKeluar;
use App\Models\mItemKeluarDetail;

class aItemKeluar extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "tbl_itemkeluarhd";
    	$this->prefix = "itemkeluar";
    	$this->iconpage = "fa fa-tag";
    	$this->pagename = "Daftar Item Keluar";

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
		$data['rows'] = mItemKeluar::select('*')
                        ->join('tbl_user', 'tbl_user.kodeuser', '=', 'tbl_itemkeluarhd.kodeuser')
                        ->join('tbl_pelanggan', 'tbl_pelanggan.kodepelanggan', '=', 'tbl_itemkeluarhd.kodepelanggan')
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

		$data['noRow'] = mItemKeluarDetail::where('kodeitemkeluar', '=', $id)->count();

		$data['rows_item'] = mItem::select('tbl_item.kodeitem', 'tbl_item.namaitem', 'tbl_item.satuan', 'tbl_kategori.namakategori')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where("tbl_item.statusitem", "=", 1)
				->get();

        $data['rows'] = mItemKeluar::select('*')
                        ->where('kodeitemkeluar', '=', $id)
                        ->first();

		$data['rows_detail'] = mItemKeluarDetail::select('*')
						->join('tbl_item', 'tbl_item.kodeitem', '=', 'tbl_itemkeluardt.kodeitem')
						->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
						->where("tbl_itemkeluardt.kodeitemkeluar", "=", $id)
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
			$kodeitemkeluar = Csql::generateKodeMax("kodeitemkeluar", "IK", $this->baseTable);

			$data = new mItemKeluar;
			$data->kodeitemkeluar = $kodeitemkeluar;
			$data->kodepelanggan = Cfilter::FilterString($request->input('kodepelanggan'));
			$data->kodeuser = Cfilter::FilterString($request->input('kodeuser'));
			$data->tanggalitemkeluar = Cfilter::FilterString($request->input('tanggalitemkeluar'));
			$data->keteranganitemkeluar = Cfilter::FilterString($request->input('keteranganitemkeluar'));
			$data->save();

            // pengecekan stok item keluar
            $jml = Cfilter::FilterInt($request->input('jml'));

            for($i=1;$i<=$jml;$i++)
            {
                $kodeitem = Cfilter::FilterString($request->input("kodeitem_$i"));
                $row_item = mItem::where('kodeitem', '=', $kodeitem)->first();
                $stok = $row_item->stok;
                $jumlah = Cfilter::FilterInt($request->input("jumlah_$i"));

                if($jumlah > $stok)
                {
                    $this->pesaninfo = Cview::pesanGagal("Stok item [".$row_item->namaitem."] tidak mencukupi..");
			        return redirect()->action([aItemKeluar::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
                }
            }

            for($i=1;$i<=$jml;$i++)
            {
				$kodeitem = Cfilter::FilterString($request->input("kodeitem_$i"));
				$jumlah = Cfilter::FilterInt($request->input("jumlah_$i"));

				if(!empty($kodeitem))
				{
					$data = new mItemKeluarDetail;
					$data->kodeitemkeluardt = \uniqid();
					$data->kodeitemkeluar = $kodeitemkeluar;
					$data->kodeitem = $kodeitem;
					$data->jumlah = $jumlah;
					$data->save();

					Csql::UpdateStok($kodeitem, $jumlah * -1);
				}
            }

            DB::commit();

            // jika berhasil
            $this->pesaninfo = Cview::pesanSukses("Berhasil Tambah Data : <b>".$kodeitemkeluar."</b>");
            return redirect()->action([aItemKeluar::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItemKeluar::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function actedit(Request $request)
    {
        // Update Data

		DB::beginTransaction();

		try {

			$id = Cfilter::FilterString($request->input('kodeitemkeluar'));

			$data = mItemKeluar::find($id);
			$data->kodepelanggan = Cfilter::FilterString($request->input('kodepelanggan'));
			$data->tanggalitemkeluar = Cfilter::FilterString($request->input('tanggalitemkeluar'));
			$data->keteranganitemkeluar = Cfilter::FilterString($request->input('keteranganitemkeluar'));
			$data->save();

			$jml = Cfilter::FilterInt($request->input('jml'));

            for($i=1;$i<=$jml;$i++)
            {
                $kodeitem = Cfilter::FilterString($request->input("kodeitem_$i"));
                $row_item = mItem::where('kodeitem', '=', $kodeitem)->first();
                $stok = $row_item->stok;
                $jumlah = Cfilter::FilterInt($request->input("jumlah_$i"));
                $status = Cfilter::FilterString($request->input("status_$i"));

                if($status == "**new" || $status == "**upd")
                {
                    if($jumlah > $stok)
                    {
                        $this->pesaninfo = Cview::pesanGagal("Stok item [".$row_item->namaitem."] tidak mencukupi..");
                        return redirect()->action([aItemKeluar::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
                    }
                }
            }


            for($i=1;$i<=$jml;$i++)
            {
				$status = Cfilter::FilterString($request->input("status_$i"));
				$kodeitemkeluardt = Cfilter::FilterString($request->input("kodeitemkeluardt_$i"));
				$kodeitem = Cfilter::FilterString($request->input("kodeitem_$i"));
				$jumlah = Cfilter::FilterInt($request->input("jumlah_$i"));

				if(!empty($kodeitem))
				{
					if($status == "**new")
					{
						$data = new mItemKeluarDetail;
						$data->kodeitemkeluardt = \uniqid();
						$data->kodeitemkeluar = $id;
						$data->kodeitem = $kodeitem;
						$data->jumlah = $jumlah;
						$data->save();

						Csql::UpdateStok($kodeitem, $jumlah * -1);
					}
					else
					{
						Csql::UpdateStokUpd($kodeitemkeluardt, "ITEMKELUAR", $jumlah);

						$data = mItemKeluarDetail::find($kodeitemkeluardt);
						$data->jumlah = $jumlah;
						$data->save();
					}
				}
            }

			// hapus data yg dipilih
			$jmldel = Cfilter::FilterInt($request->input('jmldel'));
            for($i=1;$i<=$jmldel;$i++)
            {
				$kodeitemkeluardt = Cfilter::FilterString($request->input("del_kodeitemkeluardt_$i"));
				$kodeitem = Cfilter::FilterString($request->input("del_kodeitem_$i"));
				$jumlah = Cfilter::FilterInt($request->input("del_jumlah_$i"));

				Csql::UpdateStok($kodeitem, $jumlah);

				$data = mItemKeluarDetail::where('kodeitemkeluardt', $kodeitemkeluardt);
				$data->delete();
			}

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Update Data : <b>".$id."</b>");
			return redirect()->action([aItemKeluar::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			// echo $ex->getMessage();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Update Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItemKeluar::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

    public function acthapus($id)
    {
		DB::beginTransaction();

        try
        {
            // kembalikan stok seperti semula
            $rows_detail = mItemKeluarDetail::select('*')
                ->join('tbl_item', 'tbl_item.kodeitem', '=', 'tbl_itemkeluardt.kodeitem')
                ->join('tbl_kategori', 'tbl_kategori.kodekategori', '=', 'tbl_item.kodekategori')
                ->where("tbl_itemkeluardt.kodeitemkeluar", "=", $id)
                ->get();

            foreach ($rows_detail as $row)
            {
                Csql::UpdateStok($row->kodeitem, $row->jumlah * 1);
            }

			mItemKeluarDetail::where('kodeitemkeluar', $id)->delete();
			mItemKeluar::where('kodeitemkeluar', $id)->delete();

		    DB::commit();

			// jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Hapus Data : <b>".$id."</b>");
			return redirect()->action([aItemKeluar::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Hapus Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aItemKeluar::class, 'list'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}
    }

}
?>
