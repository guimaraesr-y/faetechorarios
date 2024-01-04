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

    public static function store($data)
    {
        $con = Database::getConnection();

        $con->beginTransaction();

        if(isset($data['id'])) {
            $stmt = $con->prepare('UPDATE professores SET nome = :nome WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':nome', $data['nome']);
        } else {
            $stmt = $con->prepare('INSERT INTO professores (nome, tempos_semanais) VALUES (:nome, :tempos_semanais)');
            $stmt->bindParam(':nome', $data['nome']);
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