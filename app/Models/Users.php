<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {
    protected $connection = 'mysql1';
    protected $table = 'users';
}
