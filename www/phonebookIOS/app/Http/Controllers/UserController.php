<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	public function store(Request $request)
	{

		Log::debug("Metodo Store del UserController");
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'password' => 'required',
			'password2' => 'required',
			'email' => 'required|email',
			'device_name' => 'required'
		]);

		if ($request->password != $request->password2) {
			Log::debug("Metodo Store del UserController - Las contraseñas no coinciden");
			return response()->json(['message' => 'Las contraseñas no coinciden'], 422);
		}

		if ($validator->fails()) {
			Log::debug("Metodo Store del UserController - Datos incompletos");
			return response()->json(['message' => 'Incomplete data1'], 422);
		}
		$user = User::where('email', $request->email)
			->first();

		if (!$user) {
			$user = User::create([
				'name' => $request->name,
				'password' => bcrypt($request->password),
				'email' => $request->email,
			]);
			Log::debug("Metodo Store del UserController - Usuario creado con exito [" . $user->name . "]");
			return response()->json([
				'message' => 'Usuario creado con exito',
				'token' => $user->createToken($request->device_name)->plainTextToken,
			], 201);
		}
		Log::debug("Metodo Store del UserController - Usuario debe cambiar las credenciales");
		return response()->json([
			'message' => 'Error en las credenciales'
		], 400);
	}
}
