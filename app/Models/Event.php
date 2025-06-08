<?php

namespace App\Models;

use Core\Database;
use PDOException;

/**
 * Event Model
 * Handles all database operations related to events
 */
class Event
{
    /** Table name in a database */
    private $table = 'events';

    /** Database connection instance */
    private $db;

    /**
     * Constructor - initializes database connection
     */
    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Create a new event
     */
    public function create($data)
    {
        try {
            $this->db->query("INSERT INTO {$this->table} 
                            (category_id, title, speaker, short_description, full_description, 
                             thumbnail, start_date, start_time, end_date, end_time, registration_deadline, 
                             location_name, location_address, location_link, 
                             quota, is_published) 
                            VALUES 
                            (:category_id, :title, :speaker, :short_description, :full_description,
                             :thumbnail, :start_date, :start_time, :end_date, :end_time, :registration_deadline,
                             :location_name, :location_address, :location_link,
                             :quota, :is_published)");

            // Bind parameters
            $this->bindEventParams($data);

            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log("Error creating event: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get all events with their categories
     */
    public function getAll()
    {
        $this->db->query("SELECT e.*, ec.name as category_name 
                         FROM {$this->table} e 
                         LEFT JOIN event_categories ec ON e.category_id = ec.id
                         ORDER BY e.start_date DESC");
        return $this->db->resultSet();
    }

    /**
     * Get event by ID with category information
     */
    public function getById($id)
    {
        $this->db->query("SELECT e.*, ec.name as category_name 
                         FROM {$this->table} e 
                         LEFT JOIN event_categories ec ON e.category_id = ec.id 
                         WHERE e.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Update an existing event
     */
    public function update($id, $data)
    {
        try {
            $this->db->query("UPDATE {$this->table} 
                             SET category_id = :category_id,
                                 title = :title,
                                 speaker = :speaker,
                                 short_description = :short_description,
                                 full_description = :full_description,
                                 thumbnail = :thumbnail,
                                 start_date = :start_date,
                                 start_time = :start_time,
                                 end_date = :end_date,
                                 end_time = :end_time,
                                 registration_deadline = :registration_deadline,
                                 location_name = :location_name,
                                 location_address = :location_address,
                                 location_link = :location_link,
                                 quota = :quota,
                                 is_published = :is_published
                             WHERE id = :id");

            $this->db->bind(':id', $id);

            // Bind parameters
            $this->bindEventParams($data);

            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log("Error updating event: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Delete an event
     */
    public function delete($id)
    {
        try {
            $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Helper method to bind event parameters to query
     */
    private function bindEventParams($data)
    {
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':speaker', $data['speaker']);
        $this->db->bind(':short_description', $data['short_description']);
        $this->db->bind(':full_description', $data['full_description']);
        $this->db->bind(':thumbnail', $data['thumbnail']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':registration_deadline', $data['registration_deadline']);
        $this->db->bind(':location_name', $data['location_name']);
        $this->db->bind(':location_address', $data['location_address'] ?? null);
        $this->db->bind(':location_link', $data['location_link'] ?? null);
        $this->db->bind(':quota', $data['quota']);
        $this->db->bind(':is_published', $data['is_published']);
    }

    /**
     * Get events by category
     */
    public function getByCategory($categoryId)
    {
        $this->db->query("SELECT e.*, ec.name as category_name 
                         FROM {$this->table} e 
                         LEFT JOIN event_categories ec ON e.category_id = ec.id 
                         WHERE e.category_id = :category_id
                         ORDER BY e.start_date ASC");
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    /**
     * Get only published events
     */
    public function getPublished()
    {
        $this->db->query("SELECT e.*, ec.name as category_name
                         FROM {$this->table} e
                         LEFT JOIN event_categories ec ON e.category_id = ec.id
                         WHERE e.is_published = 1
                         ORDER BY e.start_date ASC");
        return $this->db->resultSet();
    }

    /**
     * Check the available quota for an event
     */
    public function checkQuota($eventId)
    {
        $this->db->query("SELECT
                        e.quota,
                        (SELECT COUNT(*) FROM registrations r_sub
                         WHERE r_sub.id_event = e.id AND r_sub.status != 'canceled') as registered_count
                     FROM
                        {$this->table} e
                     WHERE
                        e.id = :event_id");
        $this->db->bind(':event_id', $eventId);
        $result = $this->db->single();

        if ($result) {
            $registeredCount = isset($result['registered_count']) ? (int)$result['registered_count'] : 0;
            $quota = isset($result['quota']) ? (int)$result['quota'] : 0;

            return [
                'quota' => $quota,
                'registered' => $registeredCount,
                'available' => $quota - $registeredCount
            ];
        }
        return false;
    }

    /**
     * Get participants for an event
     */
    public function getParticipants($eventId)
    {
        $this->db->query("SELECT u.id, u.username, u.full_name, u.email, 
                          r.registration_code, r.registration_date, r.status, r.attended
                          FROM registrations r
                          JOIN users u ON r.id_user = u.id
                          WHERE r.id_event = :event_id
                          ORDER BY r.registration_date DESC");
        $this->db->bind(':event_id', $eventId);
        return $this->db->resultSet();
    }

    /**
     * Check if a user is registered for an event
     */
    public function isUserRegistered($eventId, $userId)
    {
        $this->db->query("SELECT COUNT(*) as count
                          FROM registrations
                          WHERE id_event = :event_id AND id_user = :user_id AND status != 'canceled'");
        $this->db->bind(':event_id', $eventId);
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['count'] > 0;
    }

    /**
     * Get count of upcoming events
     */
    public function getUpcomingEventsCount($currentDate = null)
    {
        try {
            $currentDate = $currentDate ?? date('Y-m-d');

            $this->db->query("SELECT COUNT(*) as total 
                             FROM {$this->table} 
                             WHERE start_date >= :current_date 
                             AND is_published = 1");

            $this->db->bind(':current_date', $currentDate);
            $result = $this->db->single();

            return (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Error in getUpcomingEventsCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get recent events with participant count
     */
    public function getRecentEvents($limit = 3)
    {
        try {
            $this->db->query("SELECT e.*, ec.name as category_name, 
                            COALESCE((SELECT COUNT(*) FROM registrations r 
                                     WHERE r.id_event = e.id AND r.status != 'canceled'), 0) as participants_count
                            FROM {$this->table} e
                            LEFT JOIN event_categories ec ON e.category_id = ec.id
                            WHERE e.is_published = 1
                            ORDER BY e.start_date DESC
                            LIMIT :limit");
            $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Error in getRecentEvents: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recommended events for a user (events they haven't registered for)
     */
    public function getRecommendedEvents($userId, $limit = 3)
    {
        try {
            $this->db->query("SELECT 
                                e.*, 
                                ec.name as category_name,
                                COALESCE((SELECT COUNT(*) FROM registrations r 
                                         WHERE r.id_event = e.id AND r.status != 'canceled'), 0) as participants_count
                            FROM {$this->table} e
                            LEFT JOIN event_categories ec ON e.category_id = ec.id
                            LEFT JOIN registrations ur ON ur.id_event = e.id AND ur.id_user = :user_id AND ur.status != 'canceled'
                            WHERE e.is_published = 1
                            AND e.start_date >= CURDATE()
                            AND ur.id IS NULL
                            ORDER BY e.start_date ASC
                            LIMIT :limit");

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit, \PDO::PARAM_INT);

            return $this->db->resultSet() ?: [];
        } catch (PDOException $e) {
            error_log("Error in getRecommendedEvents: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Search for events based on keyword
     * Searches in title, speaker, short and full description, and location
     */
    public function searchEvents($keyword)
    {
        try {
            $searchTerm = "%{$keyword}%";

            $this->db->query("SELECT e.*, ec.name as category_name 
                            FROM {$this->table} e 
                            LEFT JOIN event_categories ec ON e.category_id = ec.id 
                            WHERE (e.title LIKE :search 
                                OR e.speaker LIKE :search 
                                OR e.short_description LIKE :search 
                                OR e.full_description LIKE :search 
                                OR e.location_name LIKE :search)
                            AND e.is_published = 1
                            ORDER BY e.start_date ASC");

            $this->db->bind(':search', $searchTerm);
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Error searching events: " . $e->getMessage());
            return [];
        }
    }
}
