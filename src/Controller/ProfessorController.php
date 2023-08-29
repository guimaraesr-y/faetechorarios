<?php

namespace App\Controller;

use App\Dao\ProfessorDao;

class ProfessorController extends Controller
{
    public function show()
    {
        $professorId = $_GET['id'] ?? null;

        self::render('professor.twig', [
            'professores' => ProfessorDao::getAll(),
            'professorSelecionado' => $professorId ? ProfessorDao::getProfessorById($professorId)[0] : [],
            '_GET' => $_GET,
        ]);
    }

    public function store(Array $data)
    {
        ProfessorDao::store($data);
        header('Location: /professores');
    }
    
    public function delete($data)
    {
        ProfessorDao::delete($data['id']);
        header('Location: /professores');
    }
}