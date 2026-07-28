<?php

namespace App\Providers;

use App\Contracts\OperationalHealthChecker;
use App\Contracts\OperationalTelemetry;
use App\Contracts\ProjectDocumentInspector;
use App\Services\OperationalTelemetry\LaravelOperationalHealthChecker;
use App\Services\OperationalTelemetry\LaravelOperationalTelemetry;
use App\Services\ProjectDocuments\ClamAvProjectDocumentInspector;
use App\Services\ProjectDocuments\TelemetryProjectDocumentInspector;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OperationalTelemetry::class, LaravelOperationalTelemetry::class);

        $this->app->singleton(OperationalHealthChecker::class, function ($app): OperationalHealthChecker {
            $config = config('project_documents.clamav');

            return new LaravelOperationalHealthChecker(
                database: $app->make('db'),
                filesystems: $app->make('filesystem'),
                cache: $app->make('cache'),
                scannerHost: (string) $config['host'],
                scannerPort: (int) $config['port'],
                scannerTimeout: (float) $config['connect_timeout'],
            );
        });

        $this->app->singleton(ProjectDocumentInspector::class, function (): ProjectDocumentInspector {
            $config = config('project_documents.clamav');

            $clamAv = new ClamAvProjectDocumentInspector(
                host: (string) $config['host'],
                port: (int) $config['port'],
                connectTimeout: (float) $config['connect_timeout'],
                readTimeout: (int) $config['read_timeout'],
                chunkBytes: (int) $config['chunk_bytes'],
                maximumBytes: (int) $config['maximum_bytes'],
            );

            return new TelemetryProjectDocumentInspector(
                $clamAv,
                $this->app->make(OperationalTelemetry::class),
            );
        });

        //        $this->app->bind('path.public', function() {
        //        return realpath(base_path().'/../public_html/');
        //        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootstrapFour();

        Schema::defaultStringLength(191);

    }
}
