<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_position');
    }
}
