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

// model
use App\Models\mUser;

class aAdmin extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "tbl_user";
    	$this->prefix = "admin";
    	$this->pagename = "Admin";

    	// cek apakah sudah login atau belum
    	$this->middleware('ceklogin');
    }

    public function list(Request $request)
    {
		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>User</li>";
		$breadcrumb []= "<li class='breadcrumb-item active'>List</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "User";

		// passing function ke view
		$data['rows'] = mUser::select('*')
						// ->where("statususer", "=", 1)
						->get();

        return view("$this->prefix/list", $data);
    }

	public function tambah()
    {
		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'>Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("$this->prefix/list")."'>User</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'>Tambah</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = "Tambah User";
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

        $data['rows'] = mUser::select('*')
                        ->where('kodeuser', '=', $id)
                        ->first();

        $data['password'] = Crypt::decryptString($data['rows']->password);

        return view("$this->prefix/tambah", $data);
    }

    public function acttambah(Request $request)
    {

		// pass request
		$data['request'] = $request;

		DB::beginTransaction();

		try
		{
			$username = Cfilter::FilterString($request->input('username'));

			$cekdata = mUser::where('username', '=', $username)->exists();
            if ($cekdata)
            {
				$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>useradmin</b> sudah ada.");
				return redirect()->action([aAdmin::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
			}

			$kodeuser = Csql::generateKodeMax("kodeuser", "USR", $this->baseTable);

			$data = new mUser;
			$data->kodeuser = $kodeuser;
			$data->username = Cfilter::FilterString($request->input('username'));
			$data->password = Crypt::encryptString($request->input('password'));
			$data->nama = Cfilter::FilterString($request->input('nama'));
			$data->email = Cfilter::FilterString($request->input('email'));
			$data->nohp = Cfilter::FilterString($request->input('nohp'));
			$data->alamat = Cfilter::FilterString($request->input('alamat'));
			$data->akses = Cfilter::FilterString("MANAJEMEN");
			$data->jk = Cfilter::FilterString($request->input('jk'));
			$data->statususer = Cfilter::FilterInt($request->input('statususer'));
			$data->save();

            DB::commit();

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aAdmin::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}

		// jika berhasil
		$this->pesaninfo = Cview::pesanSukses("Berhasil Tambah Data : <b>".$request->input('nama')."</b>");
		return redirect()->action([aAdmin::class, 'list'])->with('pesaninfo', $this->pesaninfo);

    }

    public function actedit(Request $request)
    {
        // Update Data

		DB::beginTransaction();

		try {

            $id = Cfilter::FilterString($request->input('kodeuser'));
            $username = Cfilter::FilterString($request->input('username'));
			$username_old = Cfilter::FilterString($request->input('username_old'));

			if($username != $username_old)
			{
				$cekdata = mUser::where('username', '=', $username)->exists();
				if ($cekdata)
				{
					$this->pesaninfo = Cview::pesanGagal("Kesalahan Tambah Data : <b>username</b> sudah ada.");
					return redirect()->action([aAdmin::class, 'tambah'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
				}
			}

			$data = mUser::find($id);
			$data->username = Cfilter::FilterString($request->input('username'));
			$data->password = Crypt::encryptString($request->input('password'));
			$data->nama = Cfilter::FilterString($request->input('nama'));
			$data->email = Cfilter::FilterString($request->input('email'));
			$data->nohp = Cfilter::FilterString($request->input('nohp'));
			$data->alamat = Cfilter::FilterString($request->input('alamat'));
			$data->jk = Cfilter::FilterString($request->input('jk'));
			$data->statususer = Cfilter::FilterInt($request->input('statususer'));
			$data->save();

            DB::commit();

            // jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Update Data : <b>".$request->input('nama')."</b>");
			return redirect()->action([aAdmin::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Update Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aAdmin::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}

    }

    public function acthapus($id)
    {
		DB::beginTransaction();

        try
        {
            $data = mUser::find($id);
			$data->statususer = Cfilter::FilterInt(0);
			$data->save();

		    DB::commit();

		    // jika berhasil
			$this->pesaninfo = Cview::pesanSukses("Berhasil Hapus Data : <b>".$id."</b>");
			return redirect()->action([aAdmin::class, 'list'])->with('pesaninfo', $this->pesaninfo);

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Hapus Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aAdmin::class, 'list'])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}


    }

}
?>
