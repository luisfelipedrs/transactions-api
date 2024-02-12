<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Controller\UserController;
use App\Controller\TransactionController;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

// user
Router::addGroup('/api/v1/users', function () {
    Router::post('', [UserController::class, 'createUser']);
    Router::get('/{id}', [UserController::class, 'findUserById']);
    Router::post('/{id}/deposit', [UserController::class, 'handleDeposit']);
});

// transaction
Router::addGroup('/api/v1/transactions', function () {
    Router::post('', [TransactionController::class, 'makeTransaction']);
});