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
        $turnos = array("MANHA", "TARDE", "NOITE");
        $turnoSelecionado = isset($_GET['turno']) ? $_GET['turno'] : 'MANHA';
        
        $periodosLetivos = TurmaDao::getPeriodosLetivos();
        $periodoLetivo = isset($_GET['periodoLetivo']) ? $_GET['periodoLetivo'] : end($periodosLetivos)['id'];

        
        $horariosIndisponiveis = HorarioDao::getHorariosIndisponiveis($turnoSelecionado, $periodoLetivo);
        $turmas = TurmaDao::getTurmasByTurno($turnoSelecionado, $periodoLetivo);
        $salas = SalaDao::getAll();

        self::render('index.twig', [
            'salas' => $salas,
            // 'turmas' => $turmas,
            // 'professores' => $professores,
            'horariosIndisponiveis' => $horariosIndisponiveis,
            'periodoLetivo' => $periodoLetivo,
            'periodosLetivos' => $periodosLetivos,
            'turno' => $turnoSelecionado,
            'turmas' => $turmas,
            '_GET' => $_GET,
        ]);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        HorarioDao::store($data);
    }
}

function getPeriodoAtual() {
    $anoAtual = date("Y"); // Obtém o ano atual
    $mesAtual = date("n"); // Obtém o mês atual

    if ($mesAtual <= 6) {
        $periodo = "1";
    } else {
        $periodo = "2";
    }

    return $anoAtual . "." . $periodo;
}