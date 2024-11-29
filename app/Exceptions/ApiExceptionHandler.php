<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
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
          //  Log::info($request->headers);
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
        $validationErrors = $this->getValidationExceptionErrors($e);

        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($validationErrors !== null) {
            $response['fails'] = $validationErrors;
        }

        return new JsonResponse($response, $code);
    }

    protected function getExceptionCode($e)
    {
        return match (true) {
            $e instanceof HttpException => $e->getStatusCode(),
            $e instanceof AuthenticationException => 401,
            $e instanceof ValidationException => 422,
            $e instanceof AccessDeniedHttpException => 403,
            $e instanceof MethodNotAllowedException => 405,
            $e instanceof TooManyRequestsHttpException => 429,
            $e instanceof NotFoundHttpException => 404,
            default => 500,
        };
    }

    protected function getExceptionMessage($e)
    {
        return match (true) {
            $e instanceof HttpException, $e instanceof JWTException => $e->getMessage(),
            $e instanceof ValidationException => 'Validation failed',
            $e instanceof NotFoundHttpException => 'Not Found.',
            $e instanceof AuthenticationException => 'Unauthenticated.',
            $e instanceof AccessDeniedHttpException => 'Access denied. You do not have permission to access this resource.',
            $e instanceof MethodNotAllowedException => 'The HTTP method used is not allowed for this request.',
            $e instanceof TooManyRequestsHttpException => 'Too many requests. Please try again later.',
            default => 'Internal Server Error.',
        };
    }

    protected function getValidationExceptionErrors($e): ?array
    {
        return $e instanceof ValidationException ? collect($e->errors())->flatten()->all() : null;
    }

}
