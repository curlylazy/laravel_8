<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mSupplier extends Model
{
	protected $table = 'tbl_supplier';
	protected $primaryKey = 'kodesupplier';
	protected $keyType = 'string';
	public $incrementing = false;

	protected $casts = [
	   'statussupplier' => 'boolean'
	];

	const CREATED_AT = 'dateaddsupplier';
    const UPDATED_AT = 'dateupdsupplier';

    use HasFactory;
}
