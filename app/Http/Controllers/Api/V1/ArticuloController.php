<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Articulo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;
use Illuminate\Support\Facades\Validator;

class ArticuloController extends Controller
{
    public function index()
    {
        try {
        //Para visualizar todos los datos
        $articulo = Articulo::all();
       // return response()->json($articulo);
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $articulo
        ]);
        } catch (\Exception $e) {
            // Captura cualquier excepción general y devuelve una respuesta JSON adecuada
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al obtener los artículos.'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si los datos están presentes en el cuerpo JSON
        try {
            if ($request->has('cod_articulo') && $request->has('nombre') && $request->has('precio') && $request->has('stock') && $request->has('deposito') && $request->has('tipo_articulo')) {
                // Datos recibidos del cuerpo JSON
                $cod_articulo = $request->input('cod_articulo');
                $nombre = $request->input('nombre');
                $precio = $request->input('precio');
                $stock = $request->input('stock');
                $deposito = $request->input('deposito');
                $tipo_articulo = $request->input('tipo_articulo');
    
            $existingArticulo = Articulo::where('cod_articulo', $request->cod_articulo)
                                         ->where('deposito', $request->deposito)
                                         ->first();

            if ($existingArticulo) {
                $existingArticulo->stock += $request->stock;
                $existingArticulo->save();
            } else {
                $articulo = new Articulo;
                $articulo->fill($request->all());
                $articulo->save();
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
        // Obtener el valor de 'cod_articulo' de los parámetros de consulta
        $cod_articulo = $request->query('cod_articulo');
        $deposito = $request->query('deposito');

        if (!$cod_articulo || empty($cod_articulo)) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => 'El parámetro "cod_articulo" es obligatorio.'
            ]);
        }
        // Verificar si el parámetro 'deposito' está presente y no es nulo/vacío
        if ($deposito && !empty($deposito)) {
            // Si el parámetro 'deposito' está presente y no es nulo/vacío, realizar alguna acción con él
            $articulo = Articulo::where('cod_articulo', $cod_articulo)
                                ->where('deposito', $deposito)
                                ->first();
        } else {
            // Si el parámetro 'deposito' no está presente, consultar sin tener en cuenta el depósito
            $articulo = Articulo::where('cod_articulo', $cod_articulo)->first();
        }
            // Verificar si se encontró el artículo
            if ($articulo) {
                // Retornar una respuesta JSON con el artículo encontrado
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'data' => $articulo
                ]);
            } else {
                // Retornar una respuesta JSON indicando que no se encontró el artículo
                return response()->json([
                    'status' => false,
                    'code' => 204,
                    'message' => "No se encontró ningún artículo con el código $cod_articulo"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al obtener el artículo.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $cod_articulo = $request->query('cod_articulo');
            $deposito = $request->query('deposito');

            // Verificar si cod_articulo y deposito están presentes y no son nulos/vacíos
            if (!$cod_articulo || !$deposito || empty($cod_articulo) || empty($deposito)) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'Los parámetros "cod_articulo" y "deposito" son obligatorios.'
                ]);
            }

            // Verificar si el parámetro 'deposito' está presente y no es nulo/vacío
            if ($deposito && !empty($deposito)) {
                // Si el parámetro 'deposito' está presente y no es nulo/vacío, realizar alguna acción con él
                $articulo = Articulo::where('cod_articulo', $cod_articulo)
                                    ->where('deposito', $deposito)
                                    ->first();

                                    ;
            } else {
                // Si el parámetro 'deposito' no está presente, consultar sin tener en cuenta el depósito
                $articulo = Articulo::where('cod_articulo', $cod_articulo)->first();
            }

            if (!$articulo) {
                return response()->json([
                    'status' => false,
                    'code' => 404,
                    'message' => 'El artículo ingresado no fue encontrado.'
                ]);
            } else {


            // Obtener el stock actual del artículo
            $stock_actual = $articulo->stock;

            // Sumar el nuevo stock al stock actual
            $nuevo_stock = $stock_actual + $request->input('stock');

            // Verificamos si el stock nuevo es igual al stock anterior
            if ($nuevo_stock != $stock_actual) {
                // Si no es igual, Actualizmos todos los campos del artículo con los valores proporcionados en la solicitud
                $articulo->update([
                    'cod_articulo' => $request->input('cod_articulo'),
                    'nombre' => $request->input('nombre'),
                    'precio' => $request->input('precio'),
                    'stock' => $nuevo_stock, // Se actualiza el stock solo si es diferente al stock actual
                    'deposito' => $request->input('deposito'),
                    'tipo_articulo' => $request->input('tipo_articulo')
                ]);
            } else {
                // Si el nuevo stock es igual al stock actual, no es necesario actualizar el stock
                // Actualizar los demás campos del artículo sin modificar el stock
                $articulo->update([
                    'cod_articulo' => $request->input('cod_articulo'),
                    'nombre' => $request->input('nombre'),
                    'precio' => $request->input('precio'),
                    'deposito' => $request->input('deposito'),
                    'tipo_articulo' => $request->input('tipo_articulo')
                ]);
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'El artículo fue actualizado correctamente.',
                'data' => $articulo,
            ]);
        }   
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al querer editar el artículo.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            // Obtener los parámetros de la consulta
            $cod_articulo = $request->query('cod_articulo');
            $deposito = $request->query('deposito');
    
            // Verificar si cod_articulo y deposito no son nulos/vacíos
            if (empty($cod_articulo) || empty($deposito)) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'Los parámetros "cod_articulo" y "deposito" son obligatorios.'
                ]);
            }
            // Obtener el artículo
            $articulo = Articulo::where('cod_articulo', $cod_articulo)
                                ->where('deposito', $deposito)
                                ->first();
                                
            if (empty($articulo)) {
                // Si no trae ningun dato, enviamos un json declarando que no hay nada que borrar
                return response()->json([
                    'status' => true,
                    'code' => 404,
                    'message' => 'No se ha encontrado articulo para eliminar'
                ]);
            } else {
                $articulo->delete();
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => 'El artículo fue eliminado correctamente.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Ocurrió un error al querer eliminar el artículo.'
            ]);
        }
    }
}
