<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mKategori extends Model
{
	protected $table = 'tbl_kategori';
	protected $primaryKey = 'kodekategori';
	protected $keyType = 'string';
	public $incrementing = false;

	protected $casts = [
	   'statuskategori' => 'boolean'
	];

	const CREATED_AT = 'dateaddkategori';
    const UPDATED_AT = 'dateupdkategori';

    use HasFactory;
}
