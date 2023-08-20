<?php

namespace App\Exceptions;

use App\Enums\ErrorTypeEnum;
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

        if ($e instanceof ValidationException) {
            $e = $this->convertValidationExceptionToResponse($e, $request);
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
        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        switch ($status) {
            case Response::HTTP_BAD_REQUEST:
                $data['type'] = ErrorTypeEnum::BAD_REQUEST;
                break;

            case Response::HTTP_UNAUTHORIZED:
                $data['type'] = ErrorTypeEnum::UNAUTHORIZED;
                break;

            case Response::HTTP_FORBIDDEN:
                $data['type'] = ErrorTypeEnum::FORBIDDEN;
                break;

            case Response::HTTP_NOT_FOUND:
                $data['type'] = ErrorTypeEnum::NOT_FOUND;
                break;

            case Response::HTTP_METHOD_NOT_ALLOWED:
                $data['type'] = ErrorTypeEnum::METHOD_NOT_ALLOWED;
                break;

            case Response::HTTP_UNPROCESSABLE_ENTITY:
                $data['type'] = ErrorTypeEnum::VALIDATION_ERROR->value;
                $data['errors'] = $e->original['errors'];

                break;

            default:
                $data['type'] = ErrorTypeEnum::SERVER_ERROR;
        }

        return response()->json($data, $status);
    }
}
