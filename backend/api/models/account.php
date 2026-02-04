<?php

class Account {
    private $conn;
    private $table = "account";

public $a_id;
public $e_id;
public $username;
public $password;
public $role;

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

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY a_id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        return $stmt;
    }

    public function create(){
    $query = 'INSERT INTO ' . $this->table . '
    SET
        a_id = :a_id,
        e_id = :e_id,
        username = :username,
        password = :password,
        role = :role';

    $stmt = $this->conn->prepare($query);

    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    $this->a_id = $cleanData($this->a_id);
    $this->e_id = $cleanData($this->e_id);
    $this->username = $cleanData($this->username);
    $this->password = $cleanData($this->password);
    $this->role = $cleanData($this->role);

    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':role', $this->role);

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
    e_id = :e_id,
    username = :username,
    password = :password,
    role = :role
    WHERE
        a_id = :a_id_where
    ";
    
    $stmt = $this->conn->prepare($query);

    $cleanData = function($value) {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value));
    };

    $this->a_id = $cleanData($this->a_id);
    $this->e_id = $cleanData($this->e_id);
    $this->username = $cleanData($this->username);
    $this->password = $cleanData($this->password);
    $this->role = $cleanData($this->role);
    
    $stmt->bindParam(':a_id', $this->a_id);
    $stmt->bindParam(':e_id', $this->e_id);
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':role', $this->role);
    
    $stmt->bindParam(':a_id_where', $this->a_id);

    if($stmt->execute()){
        return true;
    }

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