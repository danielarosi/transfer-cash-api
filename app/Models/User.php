<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'fullname',
        'cpf_cnpj',
        'email',
        'password',
        'user_types_id'
    ];

    // Coluna do tipo Carbon
    protected $dates = [
        'deleted_at'
    ];

    protected $hidden = [
        'password',
        'deleted_at'
    ];

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, "user_id");
    }
}
