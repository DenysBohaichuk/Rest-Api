<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|RedirectResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->is('api')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException($request, Throwable $e)
    {
        $code = $this->getExceptionCode($e);
        $message = $this->getExceptionMessage($e);
        $errors = $this->getExceptionErrors($e);

        $response = [
            'code' => $code,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['error']['errors'] = $errors;
        }

        return new JsonResponse($response, $code);
    }

    protected function getExceptionCode($e)
    {
        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        } elseif ($e instanceof ValidationException) {
            return 422;
        } elseif ($e instanceof NotFoundHttpException) {
            return 404;
        }

        return 500;
    }

    protected function getExceptionMessage($e)
    {
        if ($e instanceof HttpException) {
            return $e->getMessage();
        } elseif ($e instanceof ValidationException) {
            return collect($e->errors())->flatten()->all();
        } elseif ($e instanceof NotFoundHttpException) {
            return 'Resource Not Found';
        }

        return 'Internal Server Error';
    }

    protected function getExceptionErrors($e)
    {
        return $e instanceof ValidationException ? $e->errors() : null;
    }

}
