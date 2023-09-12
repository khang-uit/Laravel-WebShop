<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public $timestamps = false; //set time to false
    protected $fillable = [
    	'name'
    ];
    protected $primaryKey = 'id';
 	protected $table = 'roles';

 	public function users(){
		return $this->belongsToMany('App\Models\User', 'App\Models\RolesUser','roles_id', 'user_id');
 	}
}
