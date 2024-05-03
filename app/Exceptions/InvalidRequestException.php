<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class InvalidRequestException extends Exception
{
    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            //En caso de que retorne un error 404
            return response()->json([
                'status' => false,
                'message' => 'La ruta solicitada no fue encontrada.'
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
           //En caso de que retorne un error 405 Method not Allowed
            return response()->json([
                'status' => false,
                'message' => 'Método HTTP no permitido para esta ruta.'
            ], 405);
        }

        return parent::render($request, $exception);
    }
}