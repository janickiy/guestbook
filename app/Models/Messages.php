<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model {
    public $timestamps = false;
    protected $connection = 'mysql1';
    protected $table = 'message';
}
