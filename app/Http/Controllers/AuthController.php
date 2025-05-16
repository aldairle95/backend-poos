<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //index
    public function index(){
        $usuarios = User::with('roles')->get(); // 👈 'roles' en plural y con ->get()
     return response()->json($usuarios);
    }
    // Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'estado'=> true,
        ]);

        $rol = Role::findById($request->role_id);
        $user->assignRole($rol);

        return response()->json(['message' => 'Usuario registrado con éxito']);
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        $user = Auth::user();
            // Verificar si el usuario está activo (estado = true)
        if (!$user->estado) {
            Auth::logout(); // Por seguridad
            return response()->json(['message' => 'Usuario inactivo. Contacta al administrador.'], 403);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // Cargar roles y permisos
        $user->load('roles', 'permissions');
    
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getPermissionNames()
            ]
        ]);
    }
    

    // Cierre de sesión
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    // Obtener usuario autenticado
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function actualizarEstado($id){
       
        $usuario = User::findOrFail($id);
        $usuario->estado = !$usuario->estado;
        $usuario->save();
    
        return response()->json(['estado' => $usuario->estado]);
    }
}