<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'flights_orders';
    protected $fillable = [
        'user_id',
        'solicitante',
        'destino',
        'data_ida',
        'data_volta',
        'status_codigo',
        'status',
    ];

    function request_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
