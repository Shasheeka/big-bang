<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'subdomain','dbname', 'dbhost', 'dbusername', 'dbpassword', 'dbport', 'status', 'url'
    ];

   public function user()
   {
        return $this->hasOne('App\User');
   }
}