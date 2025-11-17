<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = "ticket";

    protected $fillable = [
        "placa",
        "espacio_id",
        "usuario_id",
        "horaIngreso",
        "horaSalida",
        "estado",
    ];

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, "usuario_id");
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, "ticket_id");
    }
}
