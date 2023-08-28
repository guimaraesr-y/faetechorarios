<?php

namespace App\Dao;

class HorarioDao {

    /**
     * Get the horario by turma id.
     *
     * @param int $id The turma id.
     * @return array The horarios.
     */
    public static function getHorarioByTurmaId(int $turmaId) 
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT
                h.id AS horario_id,
                t.id AS turma_id,
                t.turno AS turma_turno,
                s.id AS sala_id,
                p.id AS professor_id,
                h.professor_turma_id,
                h.dia,
                h.horario
            FROM
                horarios h
            INNER JOIN
                professor_turma pt ON h.professor_turma_id = pt.id
            INNER JOIN
                turmas t ON pt.turma_id = t.id
            INNER JOIN
                professores p ON pt.professor_id = p.id
            INNER JOIN
                salas s ON h.sala_id = s.id
            WHERE turma_id = :turmaId;');
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the compared turmas horarios based on the given turma id.
     *
     * @param int $id The ID of the turmas horarios to retrieve.
     * @return mixed The compared turmas horarios.
     */
    public static function getHorariosIndisponiveis(int $turmaId) 
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT
                h.id AS horario_id,
                t.id AS turma_id,
                t.turno AS turma_turno,
                s.id AS sala_id,
                p.id AS professor_id,
                h.professor_turma_id,
                h.dia,
                h.horario
            FROM
                horarios h
            INNER JOIN
                professor_turma pt ON h.professor_turma_id = pt.id
            INNER JOIN
                turmas t ON pt.turma_id = t.id
            INNER JOIN
                professores p ON pt.professor_id = p.id
            INNER JOIN
                salas s ON h.sala_id = s.id
            WHERE t.id != :turmaId and t.turno = (select turno from turmas where id = :turmaId) ;');
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store(Array $data)
    {
        $con = Database::getConnection();
        $createSql = 'INSERT INTO horarios (sala_id, professor_turma_id, horario, dia) VALUES (:sala_id, :professor_turma_id, :horario, :dia);';
        $updateSql = 'UPDATE horarios SET sala_id = :sala_id, professor_turma_id = :turma_id, horario = :horario, dia = :dia WHERE id = :id;';
        
        $con->beginTransaction();

        foreach ($data as $horario) {
            // echo json_encode($horario);

            if ($horario['horario_id'] == '0') {
                echo 'creating...';
                $stmt = $con->prepare($createSql);
                $stmt->bindParam(':sala_id', $horario['sala_id']);
                $stmt->bindParam(':professor_turma_id', $horario['professor_turma_id']);
                $stmt->bindParam(':horario', $horario['horario']);
                $stmt->bindParam(':dia', $horario['dia']);
                $stmt->execute();
            } else {
                echo 'updating...';
                $stmt = $con->prepare($updateSql);
                $stmt->bindParam(':id', $horario['id']);
                $stmt->bindParam(':sala_id', $horario['sala_id']);
                $stmt->bindParam(':turma_id', $horario['turma_id']);
                $stmt->bindParam(':horario', $horario['horario']);
                $stmt->bindParam(':dia', $horario['dia']);
                $stmt->execute();
            }
        }

        $con->commit();
    }

}