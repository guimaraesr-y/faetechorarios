<?php

namespace App\Dao;

class DisciplinaDao {

    public static function getAll()
    {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT
                d.id,
                d.nome,
                d.tempos,
                c.nome AS nomeCurso
            FROM disciplinas d

            INNER JOIN cursos c ON d.curso_id = c.id
            ORDER BY 1');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById($disciplinaId)
    {
        $con = Database::getConnection();
        $stmt = $con->prepare('SELECT 
                d.id,
                d.nome,
                d.tempos,
                c.nome AS nomeCurso
            FROM disciplinas d

            INNER JOIN cursos c ON d.curso_id = c.id

            WHERE d.id = :id');
        $stmt->bindParam(':id', $disciplinaId);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function update($data)
    {
        $con = Database::getConnection();
        $stmt = $con->prepare('UPDATE disciplinas SET nome = :nome, tempos = :tempos, curso_id = :curso WHERE id = :id');
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':tempos', $data['tempos']);
        $stmt->bindParam(':curso', $data['curso_id']);
        $stmt->execute();
    }

    public static function store($data)
    {
        $con = Database::getConnection();
        print_r(json_encode($data));

        $stmt = $con->prepare('INSERT INTO disciplinas (nome, tempos, curso_id) VALUES (:nome, :tempos, :curso)');
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':tempos', $data['tempos']);
        $stmt->bindParam(':curso', $data['curso_id']);
        $stmt->execute();
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