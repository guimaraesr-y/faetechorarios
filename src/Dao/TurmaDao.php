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

        if($turmaSelecionada) {
            $stmt = $con->prepare('SELECT professor_id FROM professor_turma WHERE turma_id = :turmaId');
            $stmt->bindParam(':turmaId', $turmaId);
            $stmt->execute();
            $professores = $stmt->fetchAll(\PDO::FETCH_COLUMN);
    
            $turmaSelecionada['professores'] = $professores;
        }

        return $turmaSelecionada;
    }

    /**
     * Retrieves a list of professors with their IDs, names, and selection status for a given class.
     *
     * @param int $turmaId The ID of the class to retrieve professors for.
     * @throws \PDOException If there is an error executing the SQL statement.
     * @return array An array of associative arrays containing professor details.
     */
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
        
        // insere ou altera os dados na tabela turma
        $stmt = null;
        if(empty($data['id'])) {
            $stmt = $con->prepare('INSERT INTO turmas (nome, turno) VALUES (:nome, :turno)');
        } else {
            $stmt = $con->prepare('UPDATE turmas SET nome = :nome, turno = :turno WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
        }
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':turno', $data['turno']);
        $stmt->execute();

        $turmaId = empty($data['id']) ? $con->lastInsertId() : (int) $data['id'];
        print($turmaId);
        $turma_professores = ProfessorDao::getProfessoresByTurmaId($turmaId);
        print_r ($turma_professores) . '<br>';
        print_r ($data['professores']);

        // função array_filter para encontrar os professores ausentes em $data['professores']
        $professoresParaDeletar = array_filter($turma_professores, function ($professor) use ($data) {
            return !in_array($professor['professor_id'], $data['professores']);
        });

        // deleta os registros da tabela professor_turma que não estão mais associados à turma
        if (!empty($professoresParaDeletar)) {
            $placeholders = implode(',', array_fill(0, count($professoresParaDeletar), '?'));
            $con->beginTransaction();
            $stmt = $con->prepare("DELETE FROM professor_turma WHERE turma_id = :turma_id AND professor_id IN ($placeholders)");
            $stmt->bindParam(':turma_id', $turmaId);
            foreach ($professoresParaDeletar as $index => $professorId) {
                $stmt->bindValue(($index + 1), $professorId, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $con->commit();
        }

        $con->beginTransaction();
        foreach ($data['professores'] as $professorId) {
            // verifica se o professor já está relacionado à turma
            if (!in_array($professorId, $turma_professores)) {
                // insere o relacionamento na tabela professor_turma
                $stmt = $con->prepare('INSERT INTO professor_turma (turma_id, professor_id) VALUES (:turma_id, :professor_id)');
                $stmt->bindParam(':turma_id', $turmaId);
                $stmt->bindParam(':professor_id', $professorId);
                $stmt->execute();
            }
        }
        $con->commit();
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
}