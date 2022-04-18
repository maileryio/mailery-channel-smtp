<?php

declare(strict_types=1);

use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Channel\Smtp\Controller\DefaultController;

return [
    Group::create('/channel')
        ->routes(
            Route::get('/smtp/view/{id:\d+}')
                ->name('/channel/smtp/view')
                ->action([DefaultController::class, 'view']),
            Route::methods(['GET', 'POST'], '/smtp/create')
                ->action([DefaultController::class, 'create'])
                ->name('/channel/smtp/create'),
            Route::methods(['GET', 'POST'], '/smtp/edit/{id:\d+}')
                ->action([DefaultController::class, 'edit'])
                ->name('/channel/smtp/edit'),
            Route::delete('/default/smtp/{id:\d+}')
                ->action([DefaultController::class, 'delete'])
                ->name('/channel/smtp/delete'),
        )
];
