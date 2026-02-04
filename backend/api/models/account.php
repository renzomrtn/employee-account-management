<?php

class Account {
    private $conn; // holds the connection object
    private $table = "account"; // table name in our db

// current account properties
public $a_id;
public $e_id;
public $username;
public $password;
public $role;


    // constructor of the  Account class
    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT a_id, password, role FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return ['success' => false];
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Fetched row: " . print_r($row, true));

        if (password_verify($password, $row['password'])) {
            return [
                'success' => true,
                'account_id' => $row['a_id'],
                'role' => $row['role']
            ];
        }

        return ['success' => false];
    }

    // method to read all employees
    public function read() {
        // create query
        $query = "SELECT * FROM " . $this->table . " ORDER BY a_id DESC";

        // prepare statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    // create account
    public function create(){
    // create query
    $query = 'INSERT INTO ' . $this->table . '
    SET
        a_id = :a_id,
        e_id = :e_id,
        username = :username,
        password = :password,
        role = :role';

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
    $this->e_id = $cleanData($this->e_id);
    $this->username = $cleanData($this->username);
    $this->password = $cleanData($this->password);
    $this->role = $cleanData($this->role);

    // bind data
    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':role', $this->role);

    // execute query
    if($stmt->execute()){
        return true;
    }

    // print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);
    return false;
    }

    // update account
    public function update() {
    $query = "
    UPDATE " . $this->table . "
    SET
    a_id = :a_id,
    e_id = :e_id,
    username = :username,
    password = :password,
    role = :role
    WHERE
        a_id = :a_id_where
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
    $this->e_id = $cleanData($this->e_id);
    $this->username = $cleanData($this->username);
    $this->password = $cleanData($this->password);
    $this->role = $cleanData($this->role);
    
    // bind data - make sure all parameters match the query
    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':role', $this->role);
    
    // Bind the WHERE clause parameter
    $stmt->bindParam(':a_id_where', $this->a_id);

    // execute query
    if($stmt->execute()){
        return true;
    }

    // Log the error instead of printing it
    error_log("Database Error: " . implode(", ", $stmt->errorInfo()));
    return false;        
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE a_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->a_id);

        if($stmt->execute()){
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

}

?>