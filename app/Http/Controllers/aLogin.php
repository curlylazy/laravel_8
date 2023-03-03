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

class aLogin extends Controller
{
	public function __construct()
    {
		// page data
		$this->pesaninfo = "";
    	$this->baseTable = "tbl_admin";
    	$this->prefix = "login";
    	$this->iconpage = "fa fa-users";
    	$this->pagename = "Admin";
    }

    public function login(Request $request)
    {
		// Judul Halaman
		$data['prefix'] = $this->prefix;
		$data['pagename'] = $this->pagename;
		$data['description'] = "tampilkan data <b>$this->prefix</b>";

        return view("admin/$this->prefix/login", $data);
    }

    public function actlogin(Request $request)
    {
		$useradmin = Cfilter::FilterString($request->input('useradmin'));
		$password = Cfilter::FilterString($request->input('password'));

		$sql = "SELECT * FROM tbl_admin
				WHERE ( tbl_admin.useradmin = '$useradmin') AND (statusadmin = '1')
				";

		$rows = DB::select(DB::raw($sql));

		if(empty($rows[0]->useradmin))
		{
			$this->pesaninfo = "<p style='font-size: 11pt; color: #ffd7d7;'>useradmin atau Password anda salah.</p>";
			return redirect()->action('aLogin@login')->with('pesaninfo', $this->pesaninfo);
		}
		else
		{
			// ambil nilai passwordnya
			// kemudian decript
			$password_dec = Crypt::decryptString($rows[0]->password);
			if($password != $password_dec)
			{
				$this->pesaninfo = "<p style='font-size: 11pt; color: #ffd7d7;'>useradmin atau Password anda salah.</p>";
				return redirect()->action('aLogin@login')->with('pesaninfo', $this->pesaninfo);
			}

			session([
				'kodeadmin' => $rows[0]->kodeadmin,
				'useradmin' => $rows[0]->useradmin,
				'namaadmin' => $rows[0]->namaadmin,
				'akses' => $rows[0]->akses,
				'waktu' => date('Y-m-d H:i'),
			]);

			return redirect()->action('aHome@dashboard');
		}
    }

	public function actlogout(Request $request)
    {
		if ($request->session()->has('user_admin'))
		{
			// Csql::addlog("LOGOUT", "LOGOUT", "SUKSES", "");
			$request->session()->flush();
		}

		return redirect()->action('aLogin@login');
    }

}
?>
