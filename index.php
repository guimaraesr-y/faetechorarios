<?php

require __DIR__ . '/bootstrap.php';

use CoffeeCode\Router\Router;

$router = new Router("http://127.0.0.1");

$router->namespace('App\\Controller');

// home routes
$router->get('/', 'HomeController:index', 'home.index');
$router->get('/view-sala', 'ViewSalaController:index', 'viewsala.index');
$router->post('/salvar-tabela', 'HomeController:store', 'home.store'); // recebe json

// periodos letivos routes
$router->get("/periodos", "PeriodoLetivoController:index", "periodoletivo.index");
$router->post("/periodos", "PeriodoLetivoController:store", "periodoletivo.store");

// professores routes
$router->get("/professores", "ProfessorController:show", "professores.show");
$router->post("/professores", "ProfessorController:store", "professores.store");
$router->post("/professores/delete", "ProfessorController:delete", "professores.delete");
$router->post("/professores/matricula/delete", "ProfessorController:deleteMatricula", "professores.matriculas.delete");
$router->post("/professores/tempos/count", "ProfessorController:countTempos", "professores.countTempos"); // recebe json

// turmas routes
$router->get("/turmas", "TurmaController:show", "turmas.show");
$router->post("/turmas", "TurmaController:store", "turmas.store");
$router->post("/turmas/delete", "TurmaController:delete", "turmas.delete");

// salas routes
$router->get("/salas", "SalaController:show", "sala.show");
$router->post("/salas", "SalaController:store", "sala.store");
$router->post("/salas/delete", "SalaController:delete", "sala.delete");

// cursos routes
$router->get("/cursos", "CursoController:show", "curso.show");
$router->post("/cursos", "CursoController:store", "curso.store");
$router->post("/cursos/delete", "CursoController:delete", "curso.delete");

// disciplinas routes
$router->get("/disciplinas", "DisciplinaController:show", "disciplina.show");
$router->post("/disciplinas", "DisciplinaController:store", "disciplina.store");
$router->post("/disciplinas/delete", "DisciplinaController:delete", "disciplina.delete");

/**
 * This method executes the routes
 */
$router->dispatch();

/**
 * Handling router errors
 */
if ($router->error()) {
    header('HTTP/1.1 404 Not Found');
    echo 'Error code: ' . $router->error();
    echo 'Message: \n' . json_encode($router->__debugInfo());
    die();
}
