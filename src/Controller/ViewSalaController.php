<?php

namespace App\Controller;

use App\Dao\HorarioDao;
use App\Dao\ProfessorDao;
use App\Dao\SalaDao;
use App\Dao\TurmaDao;

class ViewSalaController extends Controller
{
    public function index()
    {
        $turnos = array("MANHA", "TARDE", "NOITE");
        $turnoSelecionado = isset($_GET['turno']) ? $_GET['turno'] : 'MANHA';
        
        $periodosLetivos = TurmaDao::getPeriodosLetivos();
        $periodoLetivo = isset($_GET['periodoLetivo']) ? $_GET['periodoLetivo'] : end($periodosLetivos)['id'];

        $horarios = HorarioDao::getHorariosViewBySala($turnoSelecionado, $periodoLetivo);
        $turmas = TurmaDao::getTurmasByTurno($turnoSelecionado, $periodoLetivo);
        $salas = SalaDao::getAll();

        self::render('horario-sala.twig', [
            'salas' => $salas,
            'horarios' => $horarios,
            'periodoLetivo' => $periodoLetivo,
            'periodosLetivos' => $periodosLetivos,
            'turno' => $turnoSelecionado,
            'turmas' => $turmas,
            '_GET' => $_GET,
        ]);
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