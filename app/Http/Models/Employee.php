<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
     const FIELDS = [
         "first_name" => 'First Name',
         "last_name" => 'Last Name',
         "email" => 'Email',
         "address_1" => 'Address 1',
         "address_2" => 'Address 2',
         "zip" => 'Zip',
         "city" => 'City',
         "country" => 'Country',
        ];
    const TABLE_COLUMNS = [
        "first_name" => 'First Name',
        "last_name" => 'Last Name',
        "email" => 'Email',
        "address_1" => 'Address 1',
    ];

    protected $table = 'employee';
    protected $fillable = ['first_name','last_name','email','address_1','address_2','zip','city','country'];
    public $timestamps  = false;
}
