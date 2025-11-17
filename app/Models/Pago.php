<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = "pago";

    protected $fillable = ["monto", "fecha", "ticket_id"];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
