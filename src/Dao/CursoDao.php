<?php

namespace App\Dao;

class CursoDao {

    public static function getAll()
    {
        $con = Database::getConnection();
        $stmt = $con->query("SELECT * FROM cursos");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $con = Database::getConnection();
        $stmt = $con->prepare('SELECT * FROM cursos WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function update($data)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('UPDATE cursos SET nome = :nome WHERE id = :id');
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public static function store($data)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('INSERT INTO cursos (nome) VALUES (:nome)');
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->execute();

        return $con->lastInsertId();
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('DELETE FROM cursos WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

}