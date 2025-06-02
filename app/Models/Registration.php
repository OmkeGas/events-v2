<?php

namespace App\Models;

use Core\Database;
use PDOException;

class Registration
{
    private $table = 'registrations';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function register($eventId, $userId)
    {
        try {
            // Generate registration code
            $registrationCode = $this->generateRegistrationCode();

            // Start transaction
            $this->db->beginTransaction();

            // Insert registration
            $this->db->query("INSERT INTO {$this->table} 
                             (id_event, id_user, registration_code, status) 
                             VALUES 
                             (:id_event, :id_user, :registration_code, 'registered')");

            $this->db->bind(':id_event', $eventId);
            $this->db->bind(':id_user', $userId);
            $this->db->bind(':registration_code', $registrationCode);

            $this->db->execute();
            $registrationId = $this->db->lastInsertId();

            // Create ticket
            $ticketNumber = $this->generateTicketNumber();

            $this->db->query("INSERT INTO tickets 
                             (registration_id, ticket_number) 
                             VALUES 
                             (:registration_id, :ticket_number)");

            $this->db->bind(':registration_id', $registrationId);
            $this->db->bind(':ticket_number', $ticketNumber);

            $this->db->execute();

            // Commit transaction
            $this->db->commit();

            return [
                'status' => true,
                'registration_id' => $registrationId,
                'registration_code' => $registrationCode,
                'ticket_number' => $ticketNumber
            ];
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function generateRegistrationCode()
    {
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        return 'REG-' . $date . '-' . $random;
    }

    private function generateTicketNumber()
    {
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        return 'TIX-' . $date . '-' . $random;
    }

    public function getUserRegistrations($userId)
    {
        $this->db->query("SELECT r.*, e.title, e.start_date, e.start_time, e.end_date, e.end_time, 
                          e.location_name, e.location_address, e.location_link, e.thumbnail, 
                          ec.name as category_name, t.ticket_number, t.qr_code 
                          FROM {$this->table} r 
                          JOIN events e ON r.id_event = e.id 
                          JOIN event_categories ec ON e.category_id = ec.id 
                          LEFT JOIN tickets t ON t.registration_id = r.id 
                          WHERE r.id_user = :user_id 
                          ORDER BY r.registration_date DESC");

        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT r.*, e.title, e.start_date, e.start_time, e.end_date, e.end_time, 
                          e.location_name, e.thumbnail, ec.name as category_name 
                          FROM {$this->table} r 
                          JOIN events e ON r.id_event = e.id 
                          JOIN event_categories ec ON e.category_id = ec.id 
                          WHERE r.id = :id");

        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getByEventAndUser($eventId, $userId)
    {
        $this->db->query("SELECT r.*, t.ticket_number, t.qr_code 
                          FROM {$this->table} r 
                          LEFT JOIN tickets t ON t.registration_id = r.id 
                          WHERE r.id_event = :event_id AND r.id_user = :user_id AND r.status != 'canceled'");

        $this->db->bind(':event_id', $eventId);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function cancelRegistration($id)
    {
        try {
            $this->db->query("UPDATE {$this->table} SET status = 'canceled' WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTotalRegistrations()
    {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE status != 'canceled'");
        $result = $this->db->single();
        return $result ? $result['total'] : 0;
    }
}
