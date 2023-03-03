<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mItem extends Model
{
	protected $table = 'tbl_item';
	protected $primaryKey = 'kodeitem';
	protected $keyType = 'string';
	public $incrementing = false;

	protected $casts = [
	   'statusitem' => 'boolean'
	];

	const CREATED_AT = 'dateadditem';
    const UPDATED_AT = 'dateupditem';

    use HasFactory;
}
