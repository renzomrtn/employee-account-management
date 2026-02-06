<?php

class Session_Log {
    private $conn;
    private $table = "session_log";

public $sl_id;
public $a_id;
public $status;
public $login_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY sl_id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        return $stmt;
    }

    public function create(){
    $query = 'INSERT INTO ' . $this->table . '
    SET
        a_id = :a_id,
        status = :status,
        login_time = :login_time';

    $stmt = $this->conn->prepare($query);

    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    $this->a_id = $cleanData($this->a_id);
    $this->login_time = $cleanData($this->login_time);
    $this->status = $cleanData($this->status);

    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':login_time', $this->login_time);
    $stmt->bindParam(':status', $this->status);

    if($stmt->execute()){
        return true;
    }

    printf("Error: %s.\n", $stmt->error);
    return false;
    }

    public function update() {
    $query = "
    UPDATE " . $this->table . "
    SET
    a_id = :a_id,
    login_time = :login_time,
    status = :status
    WHERE
        sl_id = :sl_id_where
    ";
    
    $stmt = $this->conn->prepare($query);

    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    $this->a_id = $cleanData($this->a_id);
    $this->login_time = $cleanData($this->login_time);
    $this->status = $cleanData($this->status);
    
    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':login_time', $this->login_time);
    $stmt->bindParam(':status', $this->status);
    
    $stmt->bindParam(':sl_id_where', $this->sl_id);

    if($stmt->execute()){
        return true;
    }

    error_log("Database Error: " . implode(", ", $stmt->errorInfo()));
    return false;        
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE sl_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->sl_id);

        if($stmt->execute()){
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

}

?>