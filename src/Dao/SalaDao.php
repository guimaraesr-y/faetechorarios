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

        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO salas (nome) VALUES (:nome)');
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->execute();
        $con->commit();
    }

}