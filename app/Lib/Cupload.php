<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Cupload
{
	public static function UploadGambar($gambar, $gambarlama, $request)
	{
		ini_set('memory_limit','256M');

		// $filename = $gambarlama;

		// if ($request->hasFile($gambar))
		// {
		// 	// cek apakah ada file gambar, kemudian hapus
		// 	if(!empty($gambarlama))
		// 	{
		// 		$exists = Storage::disk('public')->exists("pic/$gambarlama");
		// 		if($exists)
		// 		{
		// 			Storage::disk('public')->delete("pic/$gambarlama");
		// 		}
		// 	}

		// 	// upload gambar yang baru
		// 	$extension 	= $request->file($gambar)->extension();
		// 	$filename 	= "pic_".date("YmdHis").".".$extension;
		// 	$path 		= $request->file($gambar)->storeAs('pic', $filename, 'upload_to_public');
		// }

		// return $filename;



		$filename = $gambarlama;

		if ($request->hasFile($gambar))
		{
			// cek apakah ada file gambar, kemudian hapus
			if(!empty($gambarlama))
			{
				$exists = Storage::disk('public')->exists("pic/$gambarlama");
				if($exists)
				{
					Storage::disk('public')->delete("pic/$gambarlama");
				}
			}

			$file = $request->file($gambar);
			$filename = "pic_".time()."_".$file->getClientOriginalName();

			$tujuan_upload = 'uploads/pic';
			$file->move($tujuan_upload, $filename);
		}

		return $filename;
	}

	public static function UploadDoc($doc, $doclama, $request)
	{
		ini_set('memory_limit','256M');

		$filename = $doclama;

		if($request->hasFile($doc))
    	{
    		$file = $request->file($doc);

    		$filegetname = strtolower($file->getClientOriginalName());
    		$filegetname = preg_replace('/\s+/','',$filegetname);

	    	$tujuan_upload = 'uploads/doc';
			$file->move($tujuan_upload, $filegetname);

			$filename = $filegetname;
    	}

		return $filename;
	}

}
