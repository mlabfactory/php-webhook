<?php declare(strict_types=1);
namespace Mlab\Webhook\Exceptions;

class HandlerExceptions implements \Illuminate\Contracts\Debug\ExceptionHandler
{
    public function report(\Throwable $e): void
    {
        // Implement the logic to report the exception
    }

    public function render($request, \Throwable $e)
    {
        // Implement the logic to render the exception into an HTTP response
    }

    public function renderForConsole($output, \Throwable $e): void
    {
        // Implement the logic to render the exception for the console
    }

    public function shouldReport(\Throwable $e): bool
    {
        // Implement the logic to determine if the exception should be reported
        return true;
    }

}