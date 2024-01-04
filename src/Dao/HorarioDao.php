<?php

namespace App\Dao;

class HorarioDao {

    /**
     * Get the horario by turma id.
     *
     * @param int $id The turma id.
     * @return array The horarios.
     */
    public static function getHorarioByTurmaId(int $turmaId) 
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT
                h.id AS horario_id,
                t.id AS turma_id,
                t.turno AS turma_turno,
                s.id AS sala_id,
                p.id AS professor_id,
                h.professor_turma_id,
                h.dia,
                h.horario
            FROM
                horarios h
            INNER JOIN
                professor_turma pt ON h.professor_turma_id = pt.id
            INNER JOIN
                turmas t ON pt.turma_id = t.id
            INNER JOIN
                professores p ON pt.professor_id = p.id
            INNER JOIN
                salas s ON h.sala_id = s.id
            WHERE turma_id = :turmaId;');
        $stmt->bindParam(':turmaId', $turmaId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the compared turmas horarios based on the given turma id.
     *
     * @param int $id The ID of the turmas horarios to retrieve.
     * @return mixed The compared turmas horarios.
     */
    public static function getHorariosIndisponiveis(string $turno, int $periodoLetivoId) 
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT
                h.id AS horario_id,
                t.id AS turma_id,
                t.turno,
                s.id AS sala_id,
                d.nome AS disciplina_nome,
                c.nome AS curso_nome,
                ts.dia,
                ts.tempo
            FROM
                horarios h
            INNER JOIN
                tempo_semana ts ON h.tempo_semana_id = ts.id
            INNER JOIN
                turmas t ON h.turma_id = t.id
            INNER JOIN
                salas s ON h.sala_id = s.id
            INNER JOIN
                disciplinas d ON t.disciplina_id = d.id
            INNER JOIN
                cursos c ON t.curso_id = c.id
            WHERE t.turno = :turno AND t.periodo_letivo_id = :periodoLetivoId');
        $stmt->bindParam(':turno', $turno);
        $stmt->bindParam(':periodoLetivoId', $periodoLetivoId); 
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getHorariosViewBySala(string $turno, int $periodoLetivoId)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT
                t.turno AS turno,
                s.id AS sala_id,
                d.nome AS disciplina_nome,
                c.nome AS curso_nome,
                ts.dia,
                ts.tempo,
                p.nome AS professor_nome
            FROM
                horarios h
            INNER JOIN
                tempo_semana ts ON h.tempo_semana_id = ts.id
            INNER JOIN
                turmas t ON h.turma_id = t.id
            INNER JOIN
                salas s ON h.sala_id = s.id
            INNER JOIN
                disciplinas d ON t.disciplina_id = d.id
            INNER JOIN
                cursos c ON t.curso_id = c.id
            INNER JOIN
                professor_matriculas pm ON t.professor_matricula_id = pm.id
            INNER JOIN 
                professores p ON pm.professor_id = p.id
            WHERE t.turno = :turno AND t.periodo_letivo_id = :periodoLetivoId');
        
        $stmt->bindParam(':turno', $turno);
        $stmt->bindParam(':periodoLetivoId', $periodoLetivoId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function store(Array $data)
    {
        $con = Database::getConnection();
        $createSql = "INSERT INTO horarios (tempo_semana_id, sala_id, periodo_letivo_id, turma_id) VALUES (:tempo_semana_id, :sala_id, :periodo_letivo_id, :turma_id);";
        
        $deleteSql = 'DELETE horarios FROM horarios 
            INNER JOIN turmas ON horarios.turma_id = turmas.id 
            INNER JOIN tempo_semana ON horarios.tempo_semana_id = tempo_semana.id 
            WHERE 
                sala_id = :sala_id AND 
                horarios.periodo_letivo_id = :periodo_letivo_id AND 
                turmas.turno = :turno';

        $con->beginTransaction();
        
        $stmt = $con->prepare($deleteSql);
        $stmt->bindParam(':sala_id', $data['salaId']);
        $stmt->bindParam(':periodo_letivo_id', $data['periodoLetivoId']);
        $stmt->bindParam(':turno', $data['turno']);
        
        $stmt->execute();
        $con->commit();
        
        foreach ($data['tableData'] as $item) {
            if(!$item) continue;

            $tempoSemana = self::getTempoSemana($item['dia'], $item['tempo']);
            
            $stmt = $con->prepare($createSql);
            $stmt->bindParam(':tempo_semana_id', $tempoSemana);
            $stmt->bindParam(':sala_id', $item['sala_id']);
            $stmt->bindParam(':periodo_letivo_id', $data['periodoLetivoId']);
            $stmt->bindParam(':turma_id', $item['turma_id']);
        
            $stmt->execute();
        }
    }

    public static function getTempoSemana(string $dia, int $tempo)
    {
        $con = Database::getConnection();

        $stmt = $con->prepare('SELECT id FROM tempo_semana WHERE dia = :dia AND tempo = :tempo');
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':tempo', $tempo);

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['id'];
    }

    public static function getHorariosViewSala() {
        
    }

}