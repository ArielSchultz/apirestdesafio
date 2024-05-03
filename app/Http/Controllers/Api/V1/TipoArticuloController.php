<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TipoArticulo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Para visualizar todos los datos
        try {
            $tipoArticulo = TipoArticulo::all();
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $tipoArticulo
            ]);
            } catch (\Exception $e) {
                // Captura cualquier excepción general y devuelve una respuesta JSON adecuada
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'message' => 'Ocurrió un error al obtener los tipos de articulos.'
                ]);
            }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // crear un nuevo registro
        try {
            if ($request->has('nombre')) {
                // Datos recibidos del cuerpo JSON
                $nombre = $request->input('nombre');

                $existingTipoArticulo = TipoArticulo::where('nombre', $request->nombre)->first();

            if ($existingTipoArticulo) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => "Ya existe registro con este nombre"
                ]);
            } else {
                $tipoArticulo = new TipoArticulo;
                $tipoArticulo->fill($request->all());
                $tipoArticulo->save();
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
        // Obtener el valor de 'tipoArticulo' de los parámetros de consulta
        $id_tipoArticulo = $request->query('tipoArticulo');

        //Verificamos que exista el parametro tipoArticulo
        if (!$id_tipoArticulo) {
            return response()->json([
                'status' => false,
                'message' => 'El parámetro "tipoArticulo" es requerido.'
            ], 400);
        // si se proporcionó un 'id_tipoArticulo' en la consulta enviamos
        } else {
            // Buscar el tipo de articulo en la base de datos por 'id_tipoArticulo'
            $tipoArticulo = TipoArticulo::where('id',  $id_tipoArticulo)->first();

            // Verificar si se encontró el tipo de articulo
            if ($tipoArticulo) {
                // Retornar una respuesta JSON con el tipo de articulo encontrado
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'data' => $tipoArticulo
                ]);
            } else {
                // Retornar una respuesta JSON indicando que no se encontró el tipo de articulo
                return response()->json([
                    'status' => false,
                    'code' => 204,
                    'message' => "No se encontró ningún tipo de articulo con el código $id_tipoArticulo"
                ]);
            }
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'code' => 500,
            'message' => 'Ocurrió un error al obtener el tipo de articulo.'
        ]);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
   //editar un registro
    try {
        $id_tipoArticulo = $request->query('tipoArticulo');
        if (!$id_tipoArticulo) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => 'El parámetro "tipoArticulo" es obligatorio.'
            ]);
        }
        $tipoArticulo = TipoArticulo::where('id', $id_tipoArticulo)->first();

        if (!$tipoArticulo) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'El tipo de articulo ingresado no fue encontrado.'
            ]);
        } else {
        // Podremos editar los datos del registro
            $tipoArticulo->update([
                'nombre' => $request->input('nombre'),
            ]);

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'El tipo de articulo fue actualizado correctamente.',
                'data' => $tipoArticulo,
            ]); 
        }   
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'code' => 500,
            'message' => 'Ocurrió un error al querer editar el tipo de articulo.'
        ]);
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        try {
            $id_tipoArticulo = $request->query('tipoArticulo');
            if (!$id_tipoArticulo) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'El parámetro "tipoArticulo" es obligatorio.'
                ]);
            }
            $tipoArticulo = TipoArticulo::where('id', $id_tipoArticulo)->first();
            //Verificar si existe el tipo de articulo
            if (!$tipoArticulo) {
                return response()->json([
                    'status' => false,
                    'code' => 404,
                    'message' => 'El tipo de artiuclo ingresado no fue encontrado o ya fue eliminado'
                ]);
            } else {
            //Si encuentra el tipo de articulo, se podra eliminar
            $tipoArticulo->delete();

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'El tipo de articulo fue eliminado correctamente.'
            ]);
        }   
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al querer eliminar el tipo de articulo.'
            ]);
        }
    }
}
