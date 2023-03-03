<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mPelanggan extends Model
{
	protected $table = 'tbl_pelanggan';
	protected $primaryKey = 'kodepelanggan';
	protected $keyType = 'string';
	public $incrementing = false;

	protected $casts = [
	   'statuspelanggan' => 'boolean'
	];

	const CREATED_AT = 'dateaddpelanggan';
    const UPDATED_AT = 'dateupdpelanggan';

    use HasFactory;
}
