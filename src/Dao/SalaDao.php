<?php

namespace App\Dao;

class SalaDao {

    public static function getAll()
    {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT * FROM salas');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store($data)
    {
        $con = Database::getConnection();
        print_r(json_encode($data));

        $con->beginTransaction();
        if($data['id']) {
            $stmt = $con->prepare('UPDATE salas SET nome = :nome WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
        } else {
            $stmt = $con->prepare('INSERT INTO salas (nome) VALUES (:nome)');
        }
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->execute();
        $con->commit();
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $con->beginTransaction();
        
        $stmt = $con->prepare('DELETE FROM salas WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $con->commit();

        return $stmt->rowCount();
    }

}