<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra serviços da aplicação.
     */
    public function register(): void
    {
        // Exemplo: Registrar bindings de interfaces para implementações
        // $this->app->bind(Interface::class, Implementacao::class);

        // Exemplo: Registrar providers de terceiros se necessário
        // $this->app->register(\App\Providers\OutroProvider::class);
    }

    /**
     * Inicializa serviços da aplicação.
     */
    public function boot(): void
    {
        // Define o tamanho padrão das strings em migrations para evitar erros com MySQL antigos
        Schema::defaultStringLength(191);

        // Força HTTPS em produção
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Exemplo: Compartilhar dados globais com todas as views (caso use Blade)
        // view()->share('appName', config('app.name'));

        // Exemplo: Registrar macros ou helpers globais
        // Collection::macro('toUpper', function () {
        //     return $this->map(fn ($value) => strtoupper($value));
        // });
    }
}
