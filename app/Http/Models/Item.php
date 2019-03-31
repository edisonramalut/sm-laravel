<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public static $fields = ["id", "first_name", "last_name", "email", "username"];
}
