<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEspacio extends Model
{
    protected $table = "tipo_espacios";

    protected $fillable = ["nombre", "descripcion", "tarifa_hora"];

    public function espacios()
    {
        return $this->hasMany(Espacio::class, "tipo_espacio_id");
    }
}
