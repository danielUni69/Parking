<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    protected $table = "espacio";

    protected $fillable = ["piso_id", "tipo_espacio_id", "codigo", "estado"];

    public function piso()
    {
        return $this->belongsTo(Piso::class);
    }

    public function tipoEspacio()
    {
        return $this->belongsTo(TipoEspacio::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, "espacio_id");
    }
    public function ticketActivo()
    {
        return $this->hasOne(Ticket::class)->where("estado", "activo");
    }
}
