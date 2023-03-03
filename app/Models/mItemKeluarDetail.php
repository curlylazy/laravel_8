<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mItemKeluarDetail extends Model
{
	protected $table = 'tbl_itemkeluardt';
	protected $primaryKey = 'kodeitemkeluardt';
	protected $keyType = 'string';

	public $incrementing = false;
	public $timestamps = false;

    use HasFactory;
}
