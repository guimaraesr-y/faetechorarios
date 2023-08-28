<?php

namespace App\Util;

use App\Dao\HorarioDao;
use App\Dao\SpreadsheetDao;
use App\Dao\TurmaDao;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetHandler {

    // TODO: melhorar e editar este código
    public static function getSpreadsheet($turmaId): Spreadsheet
    {
        // Crie um novo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $horarioDaTurma = self::getHorarioDaTurma($turmaId);
        
        $numParaCelula = [0 => 'B', 1 => 'C', 2 => 'D', 3 => 'E', 4 => 'F'];
        $numParaHorario = [0 => '2', 1 => '3', 2 => '4', 3 => '5', 4 => '6', 5 => '7'];
        $colunas = ['A' => 'Horário', 'B' => 'Segunda', 'C' => 'Terça', 'D' => 'Quarta', 'E' => 'Quinta', 'F' =>'Sexta'];
        
        $activeWorksheet->setCellValue('A1', 'Horário');
        foreach ($colunas as $celula => $dia) {
            $activeWorksheet->setCellValue($celula . '1', $dia);
        }

        foreach ($horarioDaTurma as $horario) {
            $activeWorksheet->setCellValue(
                $numParaCelula[$horario['dia']] . $numParaHorario[$horario['horario']], 
                $horario['professor_nome']);
        } 

        return $spreadsheet;
    }

    // Função de exemplo para obter os dados de horário da turma
    private static function getHorarioDaTurma($turmaId) {
        $horarioDaTurma = SpreadsheetDao::getHorariosByTurmaId($turmaId);

        return $horarioDaTurma;
    }

}
