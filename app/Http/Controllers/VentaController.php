<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'vendedor'])->get();

        return response()->json($ventas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.total' => 'required|numeric|min:0',
            'metodoPago' => 'required|string',
            'impuesto' => 'required|numeric|min:0',
            'neto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $ultimoCodigo = Venta::max('codigo');
            $codigoGenerado = $ultimoCodigo ? intval($ultimoCodigo) + 1 : 100001;

             // Descontar stock de los productos vendidos
        foreach ($validated['productos'] as $item) {
            $producto = Producto::findOrFail($item['id']);

            if ($producto->stock < $item['cantidad']) {
                throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
            }

            $producto->stock -= $item['cantidad'];
            $producto->ventas += $item['cantidad']; // 👈 Aumentar el total de ventas
            $producto->save();
        }

            // Crear la venta guardando los productos como JSON
            $venta = Venta::create([
                'id_cliente' => $validated['cliente_id'],
                'id_vendedor' => auth()->id(),
                'metodo_pago' => $validated['metodoPago'],
                'impuesto' => $validated['impuesto'],
                'neto' => $validated['neto'],
                'total' => $validated['total'],
                'productos' => json_encode($validated['productos']),  // se guarda como array y Laravel lo convierte a JSON
                'codigo' => $codigoGenerado,
            ]);

            $cliente = Cliente::find($validated['cliente_id']);
            $cliente->compras = $cliente->compras + 1;
            $cliente->save();

            DB::commit();

            return response()->json([
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerProximoCodigo()
    {
        $ultimoCodigo = Venta::max('codigo');
        $nuevoCodigo = $ultimoCodigo ? intval($ultimoCodigo) + 1 : 100001;

        return response()->json([
            'codigo' => $nuevoCodigo
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
