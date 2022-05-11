<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'source_id',
        'target_id',
        'initial_balance_source',
        'initial_balance_target',
        'transferred_value'
    ];

    // Coluna do tipo Carbon
    protected $dates = [
        'deleted_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function target()
    {
        $this->belongsTo(Account::class, 'target_id');
    }

    public function souce()
    {
        $this->belongsTo(Account::class, 'source_id');
    }

}
