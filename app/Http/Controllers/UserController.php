<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    public function pruebas(Request $request) {
        return "Accion de pruebas de USER-CONTROLLER";
    }

    public function register(Request $request) {

        // Recoger los datos del usuario por post
        $json = $request->input('json', null);
        $params = json_decode($json); //objeto
        $params_array = json_decode($json, true); //array        

        if (!empty($params) && !empty($params_array)) {

            // Limpiar datos
            $params_array = array_map('trim', $params_array);

            // Validar datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|alpha',
                        'email' => 'required|email|unique:users',
                        'password' => 'required',
            ]);

            if ($validate->fails()) {
                // La validacion ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El Usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            } else {
                //Cifrar la contraseña
                $pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost' => 4]);

                // crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';

                // Guardar el usuario
                $user->save();

                // Validacion pasada correctamente
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El Usuario  se ha creado correctamente',
                    'user' => $user
                );
            }
        } else {
            // La validacion ha fallado
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos',
                'errors' => $validate->errors()
            );
        }


        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        return "Accion de login de Usuarios";
    }

}
