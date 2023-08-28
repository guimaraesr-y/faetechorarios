<?php

namespace App\Controller;

use App\Dao\HorarioDao;
use App\Dao\ProfessorDao;
use App\Dao\SalaDao;
use App\Dao\TurmaDao;

class HomeController extends Controller
{
    public function index()
    {
        $salas = SalaDao::getAll();
        $turmas = TurmaDao::getAll();
        $professores = ProfessorDao::getProfessoresByTurmaId($_GET['turma_id'] ?? 0);
        $turmaHorarios = HorarioDao::getHorarioByTurmaId($_GET['turma_id'] ?? 0);
        $horariosIndisponiveis = HorarioDao::getHorariosIndisponiveis($_GET['turma_id'] ?? 0);

        self::render('index.twig', [
            'salas' => $salas,
            'turmas' => $turmas,
            'professores' => $professores,
            'turmaHorarios' => $turmaHorarios,
            'horariosIndisponiveis' => $horariosIndisponiveis,
            '_GET' => $_GET,
        ]);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // echo json_encode($data);
        HorarioDao::store($data);
    }
}