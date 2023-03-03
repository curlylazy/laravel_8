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

use App\Http\Controllers\aAuth;
use App\Http\Controllers\aDashboard;

class aAuth extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesan = "";
    	$this->baseTable = "tbl_user";
    	$this->prefix = "auth";
    	$this->pagename = "Auth";

    	// cek apakah sudah login atau belum
    	// $this->middleware('ceklogin');
    }

    public function login(Request $request)
    {
        return view("$this->prefix/login");
    }

    public function profile()
    {
    	$this->middleware('ceklogin');

    	$id = session('kodeuser');

		// breadcrumb
		$breadcrumb = array();
		$breadcrumb []= "<li class='breadcrumb-item'><a href='".url("dashboard")."'> Dashboard</a></li>";
		$breadcrumb []= "<li class='breadcrumb-item'>Profile</li>";
		$data['breadcrumb'] = join($breadcrumb);

		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = 'Profile User';
		$data['aksi'] = "actedit";

		// paramerter error
		$data['pesaninfo'] = "";
		$data['iserror'] = false;

        $data['rows'] = DB::table('tbl_user')
                        ->where('kodeuser', '=', $id)
                        ->first();

        $data['password'] = Crypt::decryptString($data['rows']->password);

        return view("$this->prefix/profile", $data);
    }

    public function actupdateprofile(Request $request)
    {
        // Update Data

		DB::beginTransaction();

		try {

            $id = session('kodeuser');
            $username = Cfilter::FilterString($request->input('username'));
			$username_old = Cfilter::FilterString($request->input('username_old'));

			if($username != $username_old)
			{
				// cek apakah ada username yang sama
				$cekusername = Csql::cariData2("username", "username", $username, "tbl_user");
				if($cekusername != "")
				{
					$this->pesaninfo = Cview::pesanGagal("Kesalahan Edit Data : <b>username</b> sudah ada.");
					return redirect()->action([aUser::class, 'edit'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
				}
			}

			// update user
			DB::table("tbl_user")
	            ->where('kodeuser', "=", $id)
	            ->update
	            ([
		            'username' => Cfilter::FilterString($request->input('username')),
                    'nama' => Cfilter::FilterString($request->input('nama')),
                    'akses' => Cfilter::FilterString($request->input('akses')),
                    'password' => Crypt::encryptString($request->input('password')),
                    'dateupduser' => Cfilter::FilterString(date("Y-m-d H:i")),
	            ]);

	        session([
				'username' => Cfilter::FilterString($request->input('username')),
				'nama' => Cfilter::FilterString($request->input('nama'))
			]);

            DB::commit();

		} catch (\Exception $ex) {
		    DB::rollback();
			$this->pesaninfo = Cview::pesanGagal("Kesalahan Update Profile Data : <b>".$ex->getMessage()."</b>");
			return redirect()->action([aAuth::class, 'profile'], ['id' => $id])->with('pesaninfo', $this->pesaninfo)->with('erroract', true)->withInput();
		}

		// jika berhasil
		$this->pesaninfo = Cview::pesanSukses("Berhasil Update Profile Data : <b>".$request->input('nama')."</b>");
		return redirect()->action([aAuth::class, 'profile'])->with('pesaninfo', $this->pesaninfo);

    }

    public function actlogout(Request $request)
    {
    	$request->session()->flush();
    	return redirect()->action([aAuth::class, 'login']);
    }

	public function actlogin(Request $request)
    {
		$username = Cfilter::FilterString($request->input('username'));
		$password = Cfilter::FilterString($request->input('password'));

		$sql = "SELECT * FROM tbl_user
				WHERE ( tbl_user.username = '$username')
				";

		$rows = DB::select(DB::raw($sql));

		if(empty($rows[0]->username))
		{
			$this->pesaninfo = "<p style='font-size: 11pt; color: #ffd7d7;'>username atau password salah.</p>";
			return redirect()->action([aAuth::class, 'login'])->with('pesaninfo', $this->pesaninfo);
		}

		else
		{
			$password_dec = Crypt::decryptString($rows[0]->password);
			if($password != $password_dec)
			{
				$this->pesaninfo = "<p style='font-size: 11pt; color: #ffd7d7;'>username atau [password] anda salah.</p>";
				return redirect()->action([aAuth::class, 'login'])->with('pesaninfo', $this->pesaninfo);
			}

			session([
				'kodeuser' => $rows[0]->kodeuser,
				'username' => $rows[0]->username,
				'nama' => $rows[0]->nama,
				'akses' => $rows[0]->akses,
				'waktu' => date('Y-m-d H:i'),
			]);

			return redirect()->action([aDashboard::class, 'index']);
		}
    }



}
?>
