<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUsario extends Model
{
    protected $table = "tipo_usuario";

    protected $fillable = ["tipo"];
}
