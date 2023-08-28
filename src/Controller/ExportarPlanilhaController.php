<?php

namespace App\Controller;

use App\Util\SpreadsheetHandler;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportarPlanilhaController extends Controller
{
    public function show()
    {
        $turmaId = $_GET['turma_id'];

        $planilha = SpreadsheetHandler::getSpreadsheet($turmaId);
        $writer = new Xlsx($planilha);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="planilha.xlsx"');

        $writer->save('php://output');
    }

}