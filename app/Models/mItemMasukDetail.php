<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mItemMasukDetail extends Model
{
	protected $table = 'tbl_itemmasukdt';
	protected $primaryKey = 'kodeitemmasukdt';
	protected $keyType = 'string';

	public $incrementing = false;
	public $timestamps = false;

    use HasFactory;
}
