<?php
namespace App\Models;

use Core\Database;
use PDOException;

class User
{
    private $table = 'users';
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public  function register($data)
    {
        try {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->db->query("INSERT INTO $this->table (username, email, full_name, password, profile_picture )
                                    VALUES (:username, :email, :full_name, :password, :profile_picture)");
            $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':full_name', $data['full_name']);
            $this->db->bind(':password', $password);
            $this->db->bind(':profile_picture', $data['profile_picture']);


            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM $this->table WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function getUserByUsername($username)
    {
        $this->db->query("SELECT * FROM $this->table WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function getTotalUsers()
    {
        $this->db->query("SELECT COUNT(*) as total FROM $this->table");
        $result = $this->db->single();
        return $result ? $result['total'] : 0;
    }
}
