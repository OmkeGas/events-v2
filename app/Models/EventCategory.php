<?php

namespace App\Models;

use Core\Database;
use PDOException;

class EventCategory
{
    private $table = 'event_categories';
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getAll()
    {
        try {
            $this->db->query("SELECT * FROM {$this->table}");
            return $this->db->resultSet();
        } catch (PDOException $e) {
            return [];
        }
    }
}