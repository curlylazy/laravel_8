<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mUser extends Model
{
	protected $table = 'tbl_user';
	protected $primaryKey = 'kodeuser';
	protected $keyType = 'string';
	public $incrementing = false;

	protected $casts = [
	   'statususer' => 'boolean'
	];

	const CREATED_AT = 'dateadduser';
    const UPDATED_AT = 'dateupduser';

    use HasFactory;
}
