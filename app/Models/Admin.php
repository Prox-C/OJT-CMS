<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['faculty_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}