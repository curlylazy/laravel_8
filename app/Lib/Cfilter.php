<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class Cfilter
{
	public static function cekLogin()
    {
        $data['pagename'] = "asdasdas";
        redirect()->route('login');
    }

	public static function FilterInt($value)
	{
		$res = "";
		$res = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
		return $res;
	}

	public static function FilterString($value)
	{
		$res = "";
		$res = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		return $res;
	}

	public static function FilterDate($date)
	{
		$res = empty($date) ? null : Carbon::parse($date);
		return $res;
	}

}
