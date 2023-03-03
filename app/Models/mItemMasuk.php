<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mItemMasuk extends Model
{
	protected $table = 'tbl_itemmasukhd';
	protected $primaryKey = 'kodeitemmasuk';
	protected $keyType = 'string';
	public $incrementing = false;

	const CREATED_AT = 'dateadditemmasuk';
    const UPDATED_AT = 'dateupditemmasuk';

    use HasFactory;
}
