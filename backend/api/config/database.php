<?php
class Database{

    private $host = "localhost";
    private $dbname = "employee_db";
    private $username = "root";
    private $password = "2325@BrGyLoob@2325";
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch(PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
        }

        return $this->conn;

    }
}
?>