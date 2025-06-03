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

    public function getAll($limit = null, $offset = null)
    {
        $query = "SELECT * FROM $this->table";

        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }

        $this->db->query($query);

        if ($limit !== null) {
            $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
            if ($offset !== null) {
                $this->db->bind(':offset', $offset, \PDO::PARAM_INT);
            }
        }

        return $this->db->resultSet();
    }

    public function getUserById($id)
    {
        $this->db->query("SELECT * FROM $this->table WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUser($data)
    {
        try {
            $this->db->query("UPDATE $this->table SET 
                username = :username, 
                email = :email, 
                full_name = :full_name
                " . (isset($data['password']) && !empty($data['password']) ? ", password = :password" : "") . "
                " . (isset($data['profile_picture']) && !empty($data['profile_picture']) ? ", profile_picture = :profile_picture" : "") . "
                " . (isset($data['role']) ? ", role = :role" : "") . "
                WHERE id = :id");

            $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':full_name', $data['full_name']);
            $this->db->bind(':id', $data['id']);

            if (isset($data['password']) && !empty($data['password'])) {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
                $this->db->bind(':password', $password);
            }

            if (isset($data['profile_picture']) && !empty($data['profile_picture'])) {
                $this->db->bind(':profile_picture', $data['profile_picture']);
            }

            if (isset($data['role'])) {
                $this->db->bind(':role', $data['role']);
            }

            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function deleteUser($id)
    {
        try {
            $this->db->query("DELETE FROM $this->table WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
}
