<?php

namespace App\Controller;
use App\Dao\TurmaDao;

class TurmaController extends Controller
{

    public function show()
    {
        $turma_id = $_GET['id'] ?? null;

        self::render('turma.twig', [
            'turmas' => TurmaDao::getAll(),
            'turmaSelecionada' => $turma_id ? TurmaDao::getTurmaById($turma_id) : [],
            'professores' => TurmaDao::getProfessores($turma_id),	
            '_GET' => $_GET
        ]);
    }

    public static function store($data)
    {
        TurmaDao::store($data);
        header('Location: /turmas');
    }

    public static function delete($data)
    {
        TurmaDao::delete($data['id']);
        header('Location: /turmas');
    }

}