<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    protected $table = "pisos";

    protected $fillable = ["numero"];

    public function espacios()
    {
        return $this->hasMany(Espacio::class, "piso_id");
    }
}
