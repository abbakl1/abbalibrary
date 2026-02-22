<?php

class User {

    private $conn;
    private $table = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    // Register user
    public function register($fullname,$email,$password){

        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (fullname,email,password,role,created_at)
             VALUES (?,?,?,'user',NOW())"
        );

        $stmt->bind_param("sss",$fullname,$email,$hashedPassword);

        return $stmt->execute();
    }

    // Login
    public function login($email,$password){

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE email=? LIMIT 1"
        );

        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){

            $user = $result->fetch_assoc();

            if(password_verify($password,$user['password'])){
                return $user;
            }
        }

        return false;
    }

    // Get user by ID
    public function getUserById($id){

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE id=?"
        );

        $stmt->bind_param("i",$id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Update profile
    public function updateProfile($id,$fullname,$email){

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET fullname=?, email=? WHERE id=?"
        );

        $stmt->bind_param("ssi",$fullname,$email,$id);

        return $stmt->execute();
    }

    // Change password
    public function changePassword($id,$newPassword){

        $hashed = password_hash($newPassword,PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET password=? WHERE id=?"
        );

        $stmt->bind_param("si",$hashed,$id);

        return $stmt->execute();
    }
}