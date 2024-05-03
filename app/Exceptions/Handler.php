<?php

namespace App\Exceptions;

use Exception;

use App\Exceptions\InvalidRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        // Reservado para no reportar otras excepciones
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof InvalidRequestException) {
            return $e->render($request, $e);
        }

        return parent::render($request, $e);
    }
}