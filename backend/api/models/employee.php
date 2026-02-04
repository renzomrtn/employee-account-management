<?php

class Employee {
    private $conn; // holds the connection object
    private $table = "employee"; // table name in our db

// current employee properties
public $e_id;
public $name;
public $address;
public $birthday;
public $position;
public $department;


    // constructor of the  Employee class
    public function __construct($db) {
        $this->conn = $db;
    }

    // method to read all employees
    public function read() {
        // create query
        $query = "SELECT * FROM " . $this->table . " ORDER BY e_id DESC";

        // prepare statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create employee
    public function create(){
    // create query
    $query = 'INSERT INTO ' . $this->table . '
    SET
        e_id = :e_id,
        name = :name,
        address = :address,
        birthday = :birthday,
        position = :position,
        department = :department';

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
    $this->e_id = $cleanData($this->e_id);
    $this->name = $cleanData($this->name);
    $this->address = $cleanData($this->address);
    $this->birthday = $cleanData($this->birthday);
    $this->position = $cleanData($this->position);
    $this->department = $cleanData($this->department);

    // bind data
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':birthday', $this->birthday);
    $stmt->bindParam(':position', $this->position);
    $stmt->bindParam(':department', $this->department);

    // execute query
    if($stmt->execute()){
        return true;
    }

    // print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);
    return false;
    }

    // update employee
    public function update() {
    $query = "
    UPDATE " . $this->table . "
    SET
    e_id = :e_id,
    name = :name,
    address = :address,
    birthday = :birthday,
    position = :position,
    department = :department
    WHERE
        e_id = :e_id_where
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
    $this->e_id = $cleanData($this->e_id);
    $this->name = $cleanData($this->name);
    $this->address = $cleanData($this->address);
    $this->birthday = $cleanData($this->birthday);
    $this->position = $cleanData($this->position);
    $this->department = $cleanData($this->department);
    
    // bind data - make sure all parameters match the query
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':birthday', $this->birthday);
    $stmt->bindParam(':position', $this->position);
    $stmt->bindParam(':department', $this->department);
    
    // Bind the WHERE clause parameter
    $stmt->bindParam(':e_id_where', $this->e_id);

    // execute query
    if($stmt->execute()){
        return true;
    }

    // Log the error instead of printing it
    error_log("Database Error: " . implode(", ", $stmt->errorInfo()));
    return false;        
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE e_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->e_id);

        if($stmt->execute()){
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

}

?>