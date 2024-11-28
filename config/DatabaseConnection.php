<?php
// DatabaseConnection.php
class DatabaseConnection
{
    private $conn;

    public function __construct($username, $password, $dbname = "lanchonete")
    {
        try {
            $this->conn = new PDO(
                "mysql:host=localhost;dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            echo "Conexão realizada com sucesso";
        } catch (PDOException $e) {
            error_log("ERRO: falha na conexão: " . $e->getMessage());
            throw new Exception("Erro de conexão com banco de dados");
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}