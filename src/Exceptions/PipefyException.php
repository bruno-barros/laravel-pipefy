<?php namespace EduardoAVargas\Pipefy\Exceptions;
/**
 * Invalid Request Exception
 *
 * Thrown when a request is invalid or missing required fields.
 */
use Exception;
use http\Env\Response;

class PipefyException extends \Exception
{
    public function report(Exception $exception)
    {
        dd($exception);
        if ($exception instanceof Undefined) {
            return response('Erro');
        }

        parent::report($exception);
    }
    public function render($request, Exception $exception)
    {
        dd($exception);
        if ($exception instanceof CustomException) {
            return response()->view('errors.custom', [], 500);
        }

        return parent::render($request, $exception);
    }
}