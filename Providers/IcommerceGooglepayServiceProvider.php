<?php

namespace Modules\Icommercegooglepay\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Icommercegooglepay\Events\Handlers\RegisterIcommerceGooglepaySidebar;

class IcommerceGooglepayServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterIcommerceGooglepaySidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('googlepayconfigs', array_dot(trans('icommercegooglepay::googlepayconfigs')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('Icommercegooglepay', 'permissions');
        $this->publishConfig('Icommercegooglepay', 'settings');
        $this->publishConfig('Icommercegooglepay', 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Icommercegooglepay\Repositories\GooglepayConfigRepository',
            function () {
                $repository = new \Modules\Icommercegooglepay\Repositories\Eloquent\EloquentGooglepayConfigRepository(new \Modules\Icommercegooglepay\Entities\Googlepayconfig());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Icommercegooglepay\Repositories\Cache\CacheGooglepayConfigDecorator($repository);
            }
        );
// add bindings

    }
}
