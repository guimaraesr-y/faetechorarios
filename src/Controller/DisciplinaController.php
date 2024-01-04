<?php

namespace App\Controller;

use App\Dao\DisciplinaDao;
use App\Dao\CursoDao;

class DisciplinaController extends Controller {

    public function show()
    {
        $disciplina_id = $_GET['id'] ?? null;

        self::render('disciplina.twig', [
            'disciplinas' => DisciplinaDao::getAll(),
            'disciplinaSelecionada' => $disciplina_id ? DisciplinaDao::getById($disciplina_id) : [],
            'cursos' => CursoDao::getAll(),
            '_GET' => $_GET,
        ]);
    }

    public function store($data)
    {
        if($data['id']) {
            DisciplinaDao::update($data);
        } else {
            DisciplinaDao::store($data);
        }
        header('Location: /disciplinas');
    }

    public function delete($data)
    {
        DisciplinaDao::delete($data['id']);
        header('Location: /disciplinas');
    }

}