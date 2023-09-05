<?php

namespace App\Controller;

use App\Dao\CursoDao;

class CursoController extends Controller {

    public function show()
    {
        $curso_id = $_GET['id'] ?? null;

        self::render('curso.twig', [
            'cursos' => CursoDao::getAll(),
        ]);
    }

    public function store()
    {

    }

    public function delete()
    {

    }

}