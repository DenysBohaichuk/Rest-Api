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
                'success' => true,
                ...$originalContent,
            ];
        } else {
            $formattedResponse = [
                'success' => false,
                'message' => $originalContent['message'] ?? $this->defaultErrorMessage,
            ];

            if (isset($originalContent['fails'])) {
                $formattedResponse['fails'] = $originalContent['fails'];
            }
        }

        $response->setData($formattedResponse);

        return $response;
    }}
