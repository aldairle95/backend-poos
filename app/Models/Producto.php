<?php

namespace App\Models;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'id_categoria',
        'stock',
        'preciocompra',
        'precioventa',
        'imagen'
    ];

    public function categoria(){
        return $this->belongsTo(Categoria::class,'id_categoria');
    }
}
