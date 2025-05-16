<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'id_cliente',
        'id_vendedor', 
        'metodo_pago',
        'impuesto',
        'neto',
        'total',
        'productos'
    ];

     // Relación con el cliente
     public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    
    public function vendedor() {
        return $this->belongsTo(User::class, 'id_vendedor');
    }
}
