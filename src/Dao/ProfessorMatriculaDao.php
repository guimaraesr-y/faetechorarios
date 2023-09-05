<?php

namespace App\Dao;

class ProfessorMatriculaDao {

    public static function getById($matricula_id)
    {
        $con = Database::getConnection();
        $stmt = $con->prepare("SELECT * FROM professor_matriculas WHERE id = :matricula_id");
        $stmt->bindParam(':matricula_id', $matricula_id);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getByProfessorId($professor_id) {
        $con = Database::getConnection();
        $stmt = $con->prepare("SELECT * FROM professor_matriculas WHERE professor_id = :professor_id");
        $stmt->bindParam(':professor_id', $professor_id);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store($data) 
    {
        $con = Database::getConnection();
        $stmt = null;

        $con->beginTransaction();
        if(isset($data['id'])) {
            $stmt = $con->prepare("UPDATE professor_matriculas SET professor_id = :professor_id, matricula = :matricula, carga_horaria = :carga_horaria WHERE id = :id");
            $stmt->bindParam(':id', $data['id']);
        } else {
            $stmt = $con->prepare("INSERT INTO professor_matriculas (professor_id, matricula, carga_horaria) VALUES (:professor_id, :matricula, :carga_horaria)");
        }
        $stmt->bindParam(':professor_id', $data['professor_id']);
        $stmt->bindParam(':matricula', $data['matricula']);
        $stmt->bindParam(':carga_horaria', $data['carga_horaria']);

        $stmt->execute();
        $con->commit();

        return $stmt->rowCount();
    }

}