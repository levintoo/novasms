<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function messages() {
        return $this->hasMany(Message::class);
    }
    public function contacts() {
        return $this->hasMany(Contact::class);
    }
}
