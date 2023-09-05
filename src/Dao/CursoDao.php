<?php

namespace App\Dao;

class CursoDao {

    public static function getAll()
    {
        $con = Database::getConnection();
        $stmt = $con->query("SELECT * FROM cursos");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}