<?php

namespace Mailery\Channel\Email\Provider;

use Psr\Container\ContainerInterface;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Channel\Email\Controller\DefaultController;

final class RouteCollectorServiceProvider extends ServiceProvider
{
    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function register(ContainerInterface $container): void
    {
        /** @var RouteCollectorInterface $collector */
        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create('/channel')
                ->routes(
                        Route::get('/email/view/{id:\d+}')
                        ->name('/channel/email/view')
                        ->action([DefaultController::class, 'view']),
                    Route::methods(['GET', 'POST'], '/email/create')
                        ->action([DefaultController::class, 'create'])
                        ->name('/channel/email/create'),
                    Route::methods(['GET', 'POST'], '/email/edit/{id:\d+}')
                        ->action([DefaultController::class, 'edit'])
                        ->name('/channel/email/edit'),
                    Route::delete('/default/email/{id:\d+}')
                        ->action([DefaultController::class, 'delete'])
                        ->name('/channel/email/delete'),
                )
        );
    }
}
