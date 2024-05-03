<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Deposito;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;

class DepositoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        //Para visualizar todos los datos
        $deposito = Deposito::all();
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $deposito
        ]);
        } catch (\Exception $e) {
            // Captura cualquier excepción general y devuelve una respuesta JSON adecuada
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al obtener los depositos.'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->has('nombre')) {
                // Datos recibidos del cuerpo JSON
                $nombre = $request->input('nombre');
    
                $existingdeposito = Deposito::where('nombre', $request->nombre)->first();

            if ($existingdeposito) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => "Ya existe registro con este nombre"
                ]);
            } else {
                $deposito = new Deposito;
                $deposito->fill($request->all());
                $deposito->save();
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Se ha cargado/actualizado correctamente el registro"
            ]);
        }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => "Error al cargar/actualizar datos"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
        // Obtener el valor de 'deposito' de los parámetros de consulta
        $id_deposito = $request->query('deposito');

        //Verificamos que exista el parametro deposito
        if (!$id_deposito) {
            return response()->json([
                'status' => false,
                'message' => 'El parámetro "deposito" es requerido.'
            ], 400);
        // si se proporcionó un 'id_deposito' en la consulta enviamos
        } else {
            // Buscar el deposito en la base de datos por 'id_deposito'
            $deposito = Deposito::where('id', $id_deposito)->first();

            // Verificar si se encontró el deposito
            if ($deposito) {
                // Retornar una respuesta JSON con el deposito encontrado
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'data' => $deposito
                ]);
            } else {
                // Retornar una respuesta JSON indicando que no se encontró el deposito
                return response()->json([
                    'status' => false,
                    'code' => 204,
                    'message' => "No se encontró ningún deposito con el código $id_deposito"
                ]);
            }
        }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al obtener el deposito.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $iddeposito = $request->query('deposito');
            if (!$iddeposito) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'El parámetro "deposito" es obligatorio.'
                ]);
            }

            $deposito = Deposito::where('id', $iddeposito)->first();

            if (!$deposito) {
                return response()->json([
                    'status' => false,
                    'code' => 404,
                    'message' => 'El deposito ingresado no fue encontrado.'
                ]);
            } else {
            // Podremos editar los datos del registro
            $deposito->update([
                'nombre' => $request->input('nombre'),
            ]);

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'El deposito fue actualizado correctamente.',
                'data' => $deposito,
            ]);
        }   
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al querer editar el deposito.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $iddeposito = $request->query('deposito');
            if (!$iddeposito) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'El parámetro "deposito" es obligatorio.'
                ]);
            }

            $deposito = Deposito::where('id', $iddeposito)->first();
            //Verificar si existe el deposito
            if (!$deposito) {
                return response()->json([
                    'status' => false,
                    'code' => 404,
                    'message' => 'El deposito ingresado no fue encontrado o ya fue eliminado'
                ]);
            } else {
            //Si encuentra el deposito, se podra eliminar
            $deposito->delete();

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'El deposito fue eliminado correctamente.'
            ]);
        }   
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al querer eliminar el deposito.'
            ]);
        }
    }
}
