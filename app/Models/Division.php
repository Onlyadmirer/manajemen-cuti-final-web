<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    // PASTIKAN 'manager_id' ADA DI SINI
    protected $fillable = [
        'name',
        'description',
        'manager_id', 
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}