<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Jangan log exception ini.
     */
    protected $dontReport = [
        //
    ];

    /**
     * Jangan flash input untuk exception ini.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Bisa tambahkan logging ke Sentry/Bugsnag di sini
        });
    }

    /**
     * Render exception — redirect ke halaman error yang sesuai.
     */
    public function render($request, Throwable $e)
    {
        // Jika request JSON (API), kembalikan JSON
        if ($request->expectsJson()) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Data tidak valid.',
                    'errors'  => $e->errors(),
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            if ($e instanceof HttpException) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'HTTP Error ' . $e->getStatusCode(),
                ], $e->getStatusCode());
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Redirect ke login yang sesuai saat unauthenticated.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Cek dari mana request berasal untuk redirect yang tepat
        $url = $request->url();
        if (str_contains($url, '/admin')) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (str_contains($url, '/karyawan')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login sebagai karyawan terlebih dahulu.');
        }

        return redirect()->route('login');
    }
}
