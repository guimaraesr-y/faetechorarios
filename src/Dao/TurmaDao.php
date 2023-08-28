<?php

namespace App\Dao;

class TurmaDao
{
    public static function getAll()
    {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT * FROM turmas');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTurmaById($turmaId)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT * FROM turmas WHERE id = :turmaId');
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();
        $turmaSelecionada = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $con->prepare('SELECT professor_id FROM professor_turma WHERE turma_id = :turmaId');
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();
        $professores = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $turmaSelecionada['professores'] = $professores;

        return $turmaSelecionada;
    }

    public static function getProfessores($turmaId)
    {
        $con = Database::getConnection();
        
        $stmt = $con->prepare('SELECT p.id, p.nome,
                MAX(CASE WHEN pt.turma_id = :turmaId THEN 1 ELSE 0 END) AS is_selected
            FROM professores p
            LEFT JOIN professor_turma pt ON p.id = pt.professor_id
            GROUP BY p.id, p.nome');
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store($data)
    {
        $con = Database::getConnection();
        
        // insere os dados na tabela turma
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO turmas (nome, turno) VALUES (:nome, :turno)');
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':turno', $data['turno']);
        $stmt->execute();

        // verifica se os professores da turma estão no array de professores selecionados
        // os professores que não estiverem mais selecionados serão deletados
        $turma_professores = ProfessorDao::getProfessoresByTurmaId($data['turma_id']);
        foreach ($turma_professores as $professor) {
            if (array_search($professor['id'], $data['professores']) === false) {
                // $stmt = $con->prepare('DELETE FROM professor_turma WHERE turma_id = :turma_id AND professor_id = :professor_id');
                // $stmt->bindParam(':turma_id', $data['turma_id']);
                // $stmt->bindParam(':professor_id', $professor['id']);
                // $stmt->execute();
            }
        } // TODO: arrumar esse código de guardar os professores no array

        // $stmt = $con->prepare('INSERT INTO professor_turma (turma_id, professor_id) VALUES (:turma_id, :professor_id)');
        // $stmt->bindParam(':turma_id', $data['turma_id']);
        // $stmt->bindParam(':professor_id', $professor['id']);
        // $stmt->execute();

        $con->commit();

        // self::criarHorariosVazios($lastInsert);
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $con->beginTransaction();
        $stmt = $con->prepare('DELETE FROM turmas WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $con->commit();

        return $stmt->rowCount();
    }

    /**
     * Creates empty schedules for a given class.
     *
     * @param int $turmaId The ID of the class.
     * @throws \PDOException If an error occurs.
     * @return boolean True on success.
     */
    public static function criarHorariosVazios(int $turmaId)
    {
        $con = Database::getConnection();
        $salas = SalaDao::getAll();

        // cria os horarios vazios da turma para cada sala
        $con->beginTransaction();
        foreach ($salas as $sala) {
            for ($i=0; $i < 6; $i++) { 
                $stmt = $con->prepare('INSERT INTO horarios (sala_id, turma_id, horario, segunda, terca, quarta, quinta, sexta) VALUES (:sala_id, :turma_id, :horario, 0, 0, 0, 0, 0)');
                $stmt->bindParam(':sala_id', $sala['id']);
                $stmt->bindParam(':turma_id', $turmaId);
                $stmt->bindParam(':horario', $i);
                $stmt->execute();
            }
        }

        return $con->commit();
    }
}