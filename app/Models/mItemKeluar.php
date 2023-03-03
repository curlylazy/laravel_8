<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mItemKeluar extends Model
{
	protected $table = 'tbl_itemkeluarhd';
	protected $primaryKey = 'kodeitemkeluar';
	protected $keyType = 'string';
	public $incrementing = false;

	const CREATED_AT = 'dateadditemkeluar';
    const UPDATED_AT = 'dateupditemkeluar';

    use HasFactory;
}
