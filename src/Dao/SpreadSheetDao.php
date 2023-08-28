<?php

namespace App\Dao;

class SpreadsheetDao {

    public static function getHorariosByTurmaId($turmaId) {
        $con = Database::getConnection();

        $sql = "SELECT h.horario, h.dia, p.nome as professor_nome FROM horarios h
            INNER JOIN professor_turma pt ON h.professor_turma_id = pt.id
            INNER JOIN professores p ON pt.professor_id = p.id
            WHERE pt.turma_id = :turmaId";

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}