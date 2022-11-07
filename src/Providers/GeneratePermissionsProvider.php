<?php
namespace ZakariaJawas\PermissionsGenerator\Providers;
use Illuminate\Support\ServiceProvider;

class GeneratePermissionsProvider extends ServiceProvider {

    

    public function boot() {

        $this->commands([
            \ZakariaJawas\PermissionsGenerator\Commands\GeneratePermissions::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/permissionsgenerator.php' =>  config_path('permissionsgenerator.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/permissionsgenerator.php', 'permissionsgenerator'
        );
    }
}
?>