<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description',
    ];

    // Coluna do tipo Carbon
    protected $dates = [
        'deleted_at'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
