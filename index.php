<?php

require __DIR__ . '/bootstrap.php';

use CoffeeCode\Router\Router;

$router = new Router("http://127.0.0.1");

$router->namespace('App\\Controller');

$router->get('/', 'HomeController:index', 'home.index');
$router->get("/test", "TestController:index", 'test.index');
$router->post('/salvar-tabelas', 'HomeController:store', 'home.store'); // recebe json

$router->get("/professores", "ProfessorController:show", "professores.show");
$router->post("/professores", "ProfessorController:store", "professores.store");
$router->post("/professores/delete", "ProfessorController:delete", "professores.delete");
$router->post("/professores/tempos/count", "ProfessorController:countTempos", "professores.countTempos"); // recebe json

$router->get("/turmas", "TurmaController:show", "turmas.show");
$router->post("/turmas", "TurmaController:store", "turmas.store");
$router->post("/turmas/delete", "TurmaController:delete", "turmas.delete");

$router->get("/salas", "SalaController:show", "sala.show");
$router->post("/salas", "SalaController:store", "sala.store");
$router->post("/salas/delete", "SalaController:delete", "sala.delete");

$router->get("/planilha", "ExportarPlanilhaController:show", "exportarPlanilha.show");

/**
 * This method executes the routes
 */
$router->dispatch();

/**
 * Handling errors
 */
if ($router->error()) {
    header('HTTP/1.1 404 Not Found');
    echo 'Error code: ' . $router->error();
    echo 'Message: \n' . json_encode($router->__debugInfo());
    die();
}
