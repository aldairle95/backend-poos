<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->has('buscar') && !empty($request->buscar)) {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%$buscar%")
                  ->orWhere('codigo', 'like', "%$buscar%");
        }
    
        $productos = $query->paginate(20);
    
        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datosProducto = $request->validate([
            'nombre'=> 'required|string|unique:productos,nombre',
            'descripcion' => 'required',
            'preciocompra' => 'required|numeric|min:0',
            'precioventa' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_categoria' => 'required|exists:categorias,id'
        ]);

          // Si pasa la validación, podés continuar con el guardado
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('productos'), $nombreImagen);
        }else{
            $nombreImagen = 'anonymous.png';
                    // Copiamos la imagen por defecto a la carpeta productos
            $rutaOrigen = public_path('defaults/' . $nombreImagen);
            $rutaDestino = public_path('productos/' . $nombreImagen);

            // Solo copiamos si no existe aún en productos
            if (!file_exists($rutaDestino)) {
                \File::copy($rutaOrigen, $rutaDestino);
            }
            
        }

        $id_categoria = $request->input('id_categoria');
        $count = Producto::where('id_categoria', $id_categoria)->count() + 1;
    
        $codigo = $id_categoria . str_pad($count, 2, '0', STR_PAD_LEFT);

        //crear producto
        $producto = new Producto($datosProducto);
        $producto->imagen = 'productos/' . $nombreImagen;
        $producto->codigo = $codigo;
        $producto->save();

    
        return response()->json(['mensaje' => 'Producto creado correctamente'], 201);

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
        $producto = Producto::findOrFail($id);

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->id_categoria = $request->id_categoria;
        $producto->stock = $request->stock;
        $producto->preciocompra = $request->preciocompra;
        $producto->precioventa = $request->precioventa;
    
        // Si viene imagen nueva
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagen;
        }
    
        $producto->save();
    
        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'producto' => $producto
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if(!$producto){
            return response()->json([ 'message' => 'Producto no encontrado']);
        }

        $producto->delete();

        return response()->json([ 'message' => 'producto eliminado con exito']);
    }
}
