<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => [
                ['id' => 1, 'name' => 'Producto 1', 'price' => 10],
                ['id' => 2, 'name' => 'Producto 2', 'price' => 20],
            ]
        ]);
    }
}
