<?php

namespace App\Dao;

class ProfessorMatriculaDao {

    public static function getAll()
    {
        $con = Database::getConnection();
        $stmt = $con->query('SELECT 
                pm.id,
                p.id AS professorId,
                p.nome,
                pm.matricula,
                pm.carga_horaria,
                total_carga.carga_total

            FROM professor_matriculas pm

            INNER JOIN professores p ON pm.professor_id = p.id
            INNER JOIN turmas t ON pm.id = t.professor_matricula_id
            INNER JOIN (
                SELECT t.professor_matricula_id, SUM(d.tempos) AS carga_total
                FROM turmas t
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                GROUP BY t.professor_matricula_id
            ) total_carga ON pm.id = total_carga.professor_matricula_id

            GROUP BY pm.id, p.id, p.nome, pm.matricula, pm.carga_horaria, total_carga.carga_total');
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

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

    public static function update($data) 
    {
        $con = Database::getConnection();
        
        $con->beginTransaction();

        $stmt = $con->prepare("UPDATE professor_matriculas SET professor_id = :professor_id, matricula = :matricula, carga_horaria = :carga_horaria WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':professor_id', $data['professor_id']);
        $stmt->bindParam(':matricula', $data['matricula']);
        $stmt->bindParam(':carga_horaria', $data['carga_horaria']);
        $stmt->execute();

        $con->commit();

        return $stmt->rowCount();
    }

    public static function store($data) 
    {
        $con = Database::getConnection();

        $con->beginTransaction();

        $stmt = $con->prepare("INSERT INTO professor_matriculas (professor_id, matricula, carga_horaria) VALUES (:professor_id, :matricula, :carga_horaria)");
        $stmt->bindParam(':professor_id', $data['professor_id']);
        $stmt->bindParam(':matricula', $data['matricula']);
        $stmt->bindParam(':carga_horaria', $data['carga_horaria']);
        $stmt->execute();

        $con->commit();

        return $stmt->rowCount();
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('DELETE FROM professor_matriculas WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

}