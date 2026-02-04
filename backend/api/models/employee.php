<?php

class Employee {
    private $conn;
    private $table = "employee";

public $e_id;
public $name;
public $address;
public $birthday;
public $position;
public $department;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY e_id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        return $stmt;
    }

    public function create(){
    $query = 'INSERT INTO ' . $this->table . '
    SET
        e_id = :e_id,
        name = :name,
        address = :address,
        birthday = :birthday,
        position = :position,
        department = :department';

    $stmt = $this->conn->prepare($query);

    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    $this->e_id = $cleanData($this->e_id);
    $this->name = $cleanData($this->name);
    $this->address = $cleanData($this->address);
    $this->birthday = $cleanData($this->birthday);
    $this->position = $cleanData($this->position);
    $this->department = $cleanData($this->department);

    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':birthday', $this->birthday);
    $stmt->bindParam(':position', $this->position);
    $stmt->bindParam(':department', $this->department);

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
    e_id = :e_id,
    name = :name,
    address = :address,
    birthday = :birthday,
    position = :position,
    department = :department
    WHERE
        e_id = :e_id_where
    ";
    
    $stmt = $this->conn->prepare($query);

    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    $this->e_id = $cleanData($this->e_id);
    $this->name = $cleanData($this->name);
    $this->address = $cleanData($this->address);
    $this->birthday = $cleanData($this->birthday);
    $this->position = $cleanData($this->position);
    $this->department = $cleanData($this->department);
    
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':birthday', $this->birthday);
    $stmt->bindParam(':position', $this->position);
    $stmt->bindParam(':department', $this->department);
    
    $stmt->bindParam(':e_id_where', $this->e_id);

    if($stmt->execute()){
        return true;
    }

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