<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'user_id',
        'group_id',
        'recipient',
        'content',
        'delivered_at',
    ];
}
