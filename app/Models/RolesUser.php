<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesUser extends Model
{
    public $timestamps = false; //set time to false
    protected $fillable = [
    	'admin_id','role_id'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'users_roles';
}


