<?php

namespace App\Dao;

class ProfessorDao
{
    public static function getAll()
    {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT * FROM professores');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getProfessorById(int $professorId)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT * FROM professores WHERE id = :id');
        $stmt->bindParam(':id', $professorId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getProfessoresByTurmaId(int $id)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT pt.id as professor_turma_id, pt.professor_id, p.nome, p.tempos_semanais FROM professor_turma pt INNER JOIN professores p ON pt.professor_id = p.id WHERE turma_id = :id');
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store($data)
    {
        $con = Database::getConnection();

        $con->beginTransaction();

        if(isset($data['id'])) {
            $stmt = $con->prepare('UPDATE professores SET nome = :nome, tempos_semanais = :tempos_semanais WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':tempos_semanais', $data['tempos_semanais']);
        } else {
            $stmt = $con->prepare('INSERT INTO professores (nome, tempos_semanais) VALUES (:nome, :tempos_semanais)');
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':tempos_semanais', $data['tempos_semanais']);
        }
        $stmt->execute();

        $con->commit();

        return $stmt->rowCount();
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $con->beginTransaction();
        
        $stmt = $con->prepare('DELETE FROM professores WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $con->commit();

        return $stmt->rowCount();
    }
}