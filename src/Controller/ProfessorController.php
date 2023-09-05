<?php

namespace App\Controller;

use App\Dao\ProfessorDao;
use App\Dao\ProfessorMatriculaDao;

class ProfessorController extends Controller
{
    public function show()
    {
        $professorId = $_GET['professor_id'] ?? null;
        $matriculaId = $_GET['matricula_id'] ?? null;

        self::render('professor.twig', [
            'professores' => ProfessorDao::getAll(),
            'professorSelecionado' => $professorId ? ProfessorDao::getProfessorById($professorId)[0] : [],
            'matriculas' => $professorId ? ProfessorMatriculaDao::getByProfessorId($professorId) : [],
            'matriculaSelecionada' => $matriculaId ? ProfessorMatriculaDao::getById($matriculaId) : [],
            '_GET' => $_GET,
        ]);
    }

    public function store(Array $data)
    {
        if($data['actor'] == 'professor') {
            ProfessorDao::store($data);
            header('Location: /professores');
        } else {
            ProfessorMatriculaDao::store($data);
        }

        header('Location: /professores');
    }
    
    public function delete($data)
    {
        ProfessorDao::delete($data['id']);
        header('Location: /professores');
    }
}