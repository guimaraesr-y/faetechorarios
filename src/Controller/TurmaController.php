<?php

namespace App\Controller;
use App\Dao\TurmaDao;
use App\Dao\ProfessorMatriculaDao;
use App\Dao\CursoDao;
use App\Dao\DisciplinaDao;

class TurmaController extends Controller
{

    public function show()
    {
        $periodosLetivos = TurmaDao::getPeriodosLetivos();
        $periodoLetivo = isset($_GET['periodoLetivo']) ? $_GET['periodoLetivo'] : end($periodosLetivos)['id'];
        
        $professorMatriculas = ProfessorMatriculaDao::getAll();
        $disciplinas = DisciplinaDao::getAll();

        $cursos = CursoDao::getAll();

        $turma_id = $_GET['id'] ?? null;

        self::render('turma.twig', [
            'turmas' => TurmaDao::getTurmas($periodoLetivo),
            'turmaSelecionada' => $turma_id ? TurmaDao::getTurmaById($turma_id) : [],
            'periodoLetivo' => $periodoLetivo,
            'periodosLetivos' => $periodosLetivos,
            'professorMatriculas' => $professorMatriculas,
            'cursos' => $cursos,
            'disciplinas' => $disciplinas,
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