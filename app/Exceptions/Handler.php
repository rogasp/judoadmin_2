<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\ViewException;
use Stancl\Tenancy\Exceptions\TenantDatabaseDoesNotExistException;
use Throwable;

class Handler extends ExceptionHandler
{
    // Other methods...

    public function render($request, Throwable $e)
    {
        Log::info('Exception caught in Handler: ' . get_class($e));
        if (
            $e instanceof TenantDatabaseDoesNotExistException ||
            (tenant() && !tenant('ready') && $e instanceof QueryException) ||
            (tenant() && !tenant('ready') && $e instanceof ViewException && $e->getPrevious() instanceof QueryException)
        ) {
            return response()->view('errors.building');
        }

        return parent::render($request, $e);
    }
}
