<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin_menu extends Model
{
    protected $guarded = [];
    use SoftDeletes;
}
