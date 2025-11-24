<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacaFormato extends Model
{
    protected $table = 'placa_formatos';

    protected $fillable = [
        'pais',
        'code',
        'regex',
        'ejemplo',
        'bandera_icon',
    ];
}
