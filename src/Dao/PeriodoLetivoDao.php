<?php

namespace App\Dao;

class PeriodoLetivoDao
{
    public static function getAll()
    {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT * FROM periodo_letivo');
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
        $especificacao = $data['ano'] . '.' . $data['periodo'];
        $con = Database::getConnection();

        $con->beginTransaction();

        $stmt = $con->prepare('INSERT INTO periodo_letivo (especificacao) VALUES (:especificacao)');
        $stmt->bindParam(':especificacao', $especificacao);
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