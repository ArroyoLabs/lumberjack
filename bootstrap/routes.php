<?php
// Application Routes

$app->get('/', function ($request, $response, $args) {
    // Render index view
    return $this->theme->render($response, 'slim.phtml', $args);
});

// Web Controller

$app->any('/event[/{action}]', \app\controllers\EventController::class)
    ->setName('eventaction');

$app->any('/event/{action}/[{param}]', \app\controllers\EventController::class)
    ->setName('eventactionparam');

$app->any('/log[/{action}]', \app\controllers\LogController::class)
    ->setName('logaction');

$app->any('/log/{action}/[{param}]', \app\controllers\LogController::class)
    ->setName('logactionparams');

