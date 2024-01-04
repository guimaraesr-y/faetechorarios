<?php

namespace App\Dao;

class TurmaDao
{
    public static function getTurmas(int $periodoLetivoId) {
        $con = Database::getConnection();

        // todo
        $stmt = $con->prepare('SELECT 
                T.id,
                T.etapa,
                T.turno,
                D.id AS disciplinaId,
                D.nome AS nomeDisciplina,
                D.tempos AS temposDisciplina,
                P.nome AS nomeProfessor,
                PM.matricula AS matriculaProfessor,
                PM.carga_horaria cargaHorariaMaxProf,
                D.tempos AS cargaHorariaDisc,
                C.id AS cursoId,
                C.nome AS nomeCurso
            FROM turmas T
            
            INNER JOIN disciplinas D ON T.disciplina_id = D.id
            INNER JOIN professor_matriculas PM ON T.professor_matricula_id = PM.id
            INNER JOIN professores P ON PM.professor_id = P.id
            INNER JOIN cursos C ON T.curso_id = C.id
            
            WHERE T.periodo_letivo_id = :periodoLetivoId');

        $stmt->bindParam(':periodoLetivoId', $periodoLetivoId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTurmasByTurno(string $turno, int $periodoLetivoId) {
        $con = Database::getConnection();

        // todo
        $stmt = $con->prepare('SELECT 
                T.id,
                T.etapa,
                T.turno,
                D.id AS disciplinaId,
                D.nome AS nomeDisciplina,
                D.tempos AS temposDisciplina,
                P.nome AS nomeProfessor,
                PM.matricula AS matriculaProfessor,
                PM.carga_horaria cargaHorariaMaxProf,
                D.tempos AS cargaHorariaMaxDisc,
                C.id AS cursoId,
                C.nome AS nomeCurso
            FROM turmas T
            
            INNER JOIN disciplinas D ON T.disciplina_id = D.id
            INNER JOIN professor_matriculas PM ON T.professor_matricula_id = PM.id
            INNER JOIN professores P ON PM.professor_id = P.id
            INNER JOIN cursos C ON T.curso_id = C.id
            
            WHERE T.turno = :turno AND T.periodo_letivo_id = :periodoLetivoId');

        $stmt->bindParam(':turno', $turno);
        $stmt->bindParam(':periodoLetivoId', $periodoLetivoId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getPeriodosLetivos() {
        $con = Database::getConnection();

        $stmt = $con->query('SELECT * FROM periodo_letivo');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTurmaById($turmaId)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT 
                T.id,
                T.etapa,
                T.turno,
                T.etapa,
                T.periodo_letivo_id,
                D.id AS disciplinaId,
                D.nome AS nomeDisciplina,
                D.tempos AS temposDisciplina,
                P.nome AS nomeProfessor,
                PM.id AS professorMatriculaId,
                PM.matricula AS matriculaProfessor,
                PM.carga_horaria cargaHorariaMax,
                C.id AS cursoId,
                C.nome AS nomeCurso
            FROM turmas T
            
            INNER JOIN disciplinas D ON T.disciplina_id = D.id
            INNER JOIN professor_matriculas PM ON T.professor_matricula_id = PM.id
            INNER JOIN professores P ON PM.professor_id = P.id
            INNER JOIN cursos C ON T.curso_id = C.id
            
            WHERE T.ID = :turmaId');

        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function store($data)
    {
        if (empty($data['professor']) ||
            empty($data['curso']) || 
            empty($data['turno']) || 
            empty($data['etapa']) || 
            empty($data['periodoLetivo']) ||
            empty($data['disciplina'])) {
            header('HTTP/1.1 400 Bad Request');
            die('<script>alert("Preencha todos os campos");window.history.back();</script>');
        }

        $con = Database::getConnection();
        
        // insere ou altera os dados na tabela turma
        $stmt = null;
        if(empty($data['id'])) { // insert
            $stmt = $con->prepare('INSERT INTO turmas
                (curso_id, professor_matricula_id, disciplina_id, periodo_letivo_id, etapa, turno) 
                VALUES (:cursoId, :professorMatriculaId, :disciplinaId, :periodoLetivoId, :etapa, :turno)');
        } else { // update
            $stmt = $con->prepare('UPDATE turmas 
                SET curso_id = :cursoId, 
                    professor_matricula_id = :professorMatriculaId, 
                    disciplina_id = :disciplinaId, 
                    periodo_letivo_id = :periodoLetivoId, 
                    etapa = :etapa, 
                    turno = :turno
                WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
        } 

        $stmt->bindParam(':cursoId', $data['curso']);
        $stmt->bindParam(':professorMatriculaId', $data['professor']);
        $stmt->bindParam(':disciplinaId', $data['disciplina']);
        $stmt->bindParam(':periodoLetivoId', $data['periodoLetivo']);
        $stmt->bindParam(':etapa', $data['etapa']);
        $stmt->bindParam(':turno', $data['turno']);
        
        $stmt->execute();
    }

    public static function delete($id)
    {
        $con = Database::getConnection();

        $con->beginTransaction();
        $stmt = $con->prepare('DELETE FROM turmas WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $con->commit();

        return $stmt->rowCount();
    }
}