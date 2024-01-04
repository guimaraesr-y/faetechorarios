<?php

namespace App\Controller;

use App\Dao\CursoDao;

class CursoController extends Controller {

    public function show()
    {
        $curso_id = $_GET['id'] ?? null;

        self::render('curso.twig', [
            'cursos' => CursoDao::getAll(),
            'cursoSelecionado' => $curso_id ? CursoDao::getById($curso_id) : [],
            '_GET' => $_GET,
        ]);
    }

    public function store($data)
    {
        if(!empty($data['id'])) {
            CursoDao::update($data);
        } else {
            CursoDao::store($data);
        }

        header('Location: /cursos');
    }

    public function delete($data)
    {
        $curso_id = $data['id'] ?? null;
        CursoDao::delete($curso_id);
        header('Location: /cursos');
    }

}