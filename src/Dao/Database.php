<?php

namespace App\Dao;

class Database
{
    private static $db_name;
    private static $db_user;
    private static $db_pass;

    public static function init()
    {
        self::$db_name = getenv('APP_ENV') == 'production' ? getenv('DB_NAME') : getenv('DB_NAME_DEV');
        self::$db_user = getenv('APP_ENV') == 'production' ? getenv('DB_USER') : getenv('DB_USER_DEV');
        self::$db_pass = getenv('APP_ENV') == 'production' ? getenv('DB_PASS') : getenv('DB_PASS_DEV');
        self::createDatabase();
    }

    public static function getConnection()
    {
        global $db_name, $db_user, $db_pass;

        if (empty(self::$db_name) || empty(self::$db_user)) {
            self::init(); // Certifica-se de que as variáveis estejam inicializadas
        }

        return new \PDO("mysql:host=localhost;dbname=" . self::$db_name, self::$db_user, self::$db_pass, [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public static function createDatabase()
    {
        $con = self::getConnection();

        $sql = "
            CREATE TABLE IF NOT EXISTS turmas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                turno VARCHAR(255) NOT NULL
            );
            
            CREATE TABLE IF NOT EXISTS salas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL
            );
            
            CREATE TABLE IF NOT EXISTS professores (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL
            );
            
            CREATE TABLE IF NOT EXISTS professor_turma (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_id INT,
                turma_id INT,
                FOREIGN KEY (professor_id) REFERENCES professores(id),
                FOREIGN KEY (turma_id) REFERENCES turmas(id)
            );
            
            CREATE TABLE IF NOT EXISTS horarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sala_id INT,
                professor_turma_id INT,
                horario INT,
                dia INT,
                FOREIGN KEY (sala_id) REFERENCES salas(id),
                FOREIGN KEY (professor_turma_id) REFERENCES professor_turma(id)
            );
            ";

        $con->exec($sql);
    }

    public static function seed()
    {
        $con = self::getConnection();

        $sql = `
            -- Inserir dados na tabela 'turmas'
            INSERT INTO turmas (nome, turno)
            VALUES
                ('Turma A', 'Manhã'),
                ('Turma B', 'Tarde'),
                ('Turma C', 'Noite');
            
            -- Inserir dados na tabela 'salas'
            INSERT INTO salas (nome)
            VALUES
                ('Sala 101'),
                ('Sala 102'),
                ('Sala 103');
            
            -- Inserir dados na tabela 'professores'
            INSERT INTO professores (nome)
            VALUES
                ('Professor 1'),
                ('Professor 2'),
                ('Professor 3');
            
            -- Inserir dados na tabela 'professor_turma'
            INSERT INTO professor_turma (professor_id, turma_id)
            VALUES
                (1, 1), -- Professor 1 na Turma A
                (2, 2), -- Professor 2 na Turma B
                (3, 3); -- Professor 3 na Turma C
            
            -- Inserir dados na tabela 'horarios'
            INSERT INTO horarios (sala_id, professor_turma_id, horario, dia)
            VALUES
                (1, 1, 1, 1), -- Sala 101, Professor 1 na Turma A, Horário 1, Segunda-feira
                (2, 2, 2, 2), -- Sala 102, Professor 2 na Turma B, Horário 2, Terça-feira
                (3, 3, 3, 3); -- Sala 103, Professor 3 na Turma C, Horário 3, Quarta-feira;
            `;

        $con->exec($sql);
    }
}