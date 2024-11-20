<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FormatApiResponse
{
    private int $defaultErrorStatus = 500;
    private string $defaultErrorMessage = 'Internal Server Error';

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            $response = new JsonResponse($response);
        }

        $originalContent = $response->getOriginalContent();

        if ($response->isSuccessful()) {
            $formattedResponse = [
                'status' => true,
                'data' => $originalContent,
            ];
        } else {
            $formattedResponse = [
                'status' => false,
                'error' => [
                    'code' => $response->getStatusCode() ?? $this->defaultErrorStatus,
                    'message' => $originalContent['message'] ?? $this->defaultErrorMessage,
                ],
            ];
        }

        $response->setData($formattedResponse);

        return $response;
    }}
