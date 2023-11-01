<?php

namespace App\Exceptions;

use App\Enums\ErrorTypeEnum;
use App\Helpers\ValidationHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');

            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    protected function handleApiException($request, Throwable $e): JsonResponse
    {
        $e = $this->prepareException($e);

        if ($e instanceof HttpResponseException) {
            $e = $e->getResponse();
        }

        if ($e instanceof AuthenticationException) {
            $e = $this->unauthenticated($request, $e);
        }

        return $this->apiResponse($e);
    }

    protected function apiResponse($e): JsonResponse
    {
        $data = [
            'message' => $e->original['message'] ?? $e->getMessage()
        ];

        if (method_exists($e, 'getStatusCode')) {
            $status = $e->getStatusCode();
        } else if ($e instanceof ValidationException) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $data['type'] = match ($status) {
            Response::HTTP_BAD_REQUEST => ErrorTypeEnum::BAD_REQUEST,
            Response::HTTP_UNAUTHORIZED => ErrorTypeEnum::UNAUTHORIZED,
            Response::HTTP_FORBIDDEN => ErrorTypeEnum::FORBIDDEN,
            Response::HTTP_NOT_FOUND => ErrorTypeEnum::NOT_FOUND,
            Response::HTTP_METHOD_NOT_ALLOWED => ErrorTypeEnum::METHOD_NOT_ALLOWED,
            Response::HTTP_UNPROCESSABLE_ENTITY => ValidationHelper::getErrorType($e->validator->failed()),
            default => ErrorTypeEnum::SERVER_ERROR,
        };

        return response()->json($data, $status);
    }
}
