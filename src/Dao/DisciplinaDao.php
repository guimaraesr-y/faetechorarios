<?php

namespace App\Dao;

class DisciplinaDao {

    public static function getAll()
    {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT * FROM disciplinas');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById($disciplinaId)
    {
        $con = Database::getConnection();
        $stmt = $con->prepare('SELECT * FROM disciplinas WHERE id = :id');
        $stmt->bindParam(':id', $disciplinaId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store($data)
    {
        $con = Database::getConnection();
        print_r(json_encode($data));

        $con->beginTransaction();
        if($data['id']) {
            $stmt = $con->prepare('UPDATE disciplinas SET nome = :nome, tempos = :tempos WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
        } else {
            $stmt = $con->prepare('INSERT INTO disciplinas (nome, tempos) VALUES (:nome, :tempos)');
        }
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':tempos', $data['tempos']);
        $stmt->execute();
        $con->commit();
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $con->beginTransaction();
        
        $stmt = $con->prepare('DELETE FROM disciplinas WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $con->commit();

        return $stmt->rowCount();
    }

}