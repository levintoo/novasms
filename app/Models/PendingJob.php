<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingJob extends Model
{
    protected $fillable = ['user_id','batch_id','name'];
}
