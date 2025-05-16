<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::paginate(20);
        return response()->json($clientes);
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
        $datosCliente = $request->validate([
            'nombre'=>'required',
            'documento'=>'required',
            'email'=>'required|email',
            'telefono'=>'required',
            'direccion'=>'required',
            'fecha_nacimiento'=>'required|date'
        ]);

        $Cliente = new Cliente ($datosCliente);
        $Cliente->save();
        
        return response()->json(['mensaje' => 'Cliente creado correctamente'], 201);
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
       $Cliente = Cliente::findOrFail($id);

       $Cliente->nombre = $request->nombre;
       $Cliente->documento = $request->documento;
       $Cliente->email = $request->email;
       $Cliente->telefono = $request->telefono;
       $Cliente->direccion = $request->direccion;
       $Cliente->fecha_nacimiento = $request->fecha_nacimiento;

       $Cliente->save(); 

       return response()->json([
        'message' => 'Producto actualizado correctamente',
        'cliente' => $Cliente
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
        $cliente = Cliente::find($id);

        if(!$cliente){
            return response()->json([ 'message' => 'cliente no encontrado']);
        }

        $cliente->delete();

        return response()->json([ 'message' => '$cliente eliminado con exito']);
    }
}
