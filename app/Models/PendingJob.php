<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingJob extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','batch_id','name'];
}
