<?php


namespace TendoPay\Integration\SaltEdge;

use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;
use TransactionService;

class SaltEdgeServiceProvider extends ServiceProvider
{
    /**
     * Loads the configuration file.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../../config/saltedge.php', 'saltedge');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AccountService::class, function (Application $app) {
            return new AccountService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(CategoryService::class, function (Application $app) {
            /** @var | $app */
            return new CategoryService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(CustomerService::class, function (Application $app) {
            return new CustomerService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(TransactionService::class, function (Application $app) {
            return new TransactionService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(ProviderService::class, function (Application $app) {
            return new ProviderService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(MerchantService::class, function (Application $app) {
            return new ProviderService($app->get(EndpointCaller::class));
        });

        $this->app->singleton(SaltEdgeConnect::class, function (Application $app) {
            return new SaltEdgeConnect($app->get(EndpointCaller::class));
        });

        $this->app->singleton(EndpointCaller::class, function (Application $app) {
            return new EndpointCaller(
                new Client(),
                config("saltedge.url"),
                config("saltedge.app_id"),
                config("saltedge.secret")
            );
        });
    }
}
