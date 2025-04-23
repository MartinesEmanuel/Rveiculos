<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Caminho padrão após login (caso use autenticação).
     */
    public const HOME = '/home';

    /**
     * Inicializa as rotas do aplicativo.
     */
    public function boot(): void
    {
        // Log para depuração (pode remover em produção)
        \Log::info('RouteServiceProvider carregado!');

        $this->routes(function () {
            // Rotas da API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Rotas web (Blade, controllers web, etc)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}