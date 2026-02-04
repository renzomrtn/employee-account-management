<?php

class Session_Log {
    private $conn; // holds the connection object
    private $table = "session_log"; // table name in our db

// current session_log properties
public $sl_id;
public $a_id;
public $status;
public $timestamp;


    // constructor of the  Session_Log class
    public function __construct($db) {
        $this->conn = $db;
    }

    // method to read all employees
    public function read() {
        // create query
        $query = "SELECT * FROM " . $this->table . " ORDER BY sl_id DESC";

        // prepare statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create session_log
    public function create(){
    // create query
    $query = 'INSERT INTO ' . $this->table . '
    SET
        a_id = :a_id,
        status = :status,
        timestamp = :timestamp';

    // prepare statement
    $stmt = $this->conn->prepare($query);

    // Helper function to clean data while preserving NULL values
    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    // Clean data while preserving NULL values
    $this->a_id = $cleanData($this->a_id);
    $this->timestamp = $cleanData($this->timestamp);
    $this->status = $cleanData($this->status);

    // bind data
    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':timestamp', $this->timestamp);
    $stmt->bindParam(':status', $this->status);

    // execute query
    if($stmt->execute()){
        return true;
    }

    // print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);
    return false;
    }

    // update session_log
    public function update() {
    $query = "
    UPDATE " . $this->table . "
    SET
    a_id = :a_id,
    timestamp = :timestamp,
    status = :status
    WHERE
        sl_id = :sl_id_where
    ";
    
    // prepare statement
    $stmt = $this->conn->prepare($query);

    // Helper function to clean data while preserving NULL values
    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    // Clean data while preserving NULL values
    $this->a_id = $cleanData($this->a_id);
    $this->timestamp = $cleanData($this->timestamp);
    $this->status = $cleanData($this->status);
    
    // bind data - make sure all parameters match the query
    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':timestamp', $this->timestamp);
    $stmt->bindParam(':status', $this->status);
    
    // Bind the WHERE clause parameter
    $stmt->bindParam(':sl_id_where', $this->sl_id);

    // execute query
    if($stmt->execute()){
        return true;
    }

    // Log the error instead of printing it
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