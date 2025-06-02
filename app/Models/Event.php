<?php

namespace App\Models;

use Core\Database;
use PDOException;

class Event
{
    private $table = 'events';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

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

            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getAll()
    {
        $this->db->query("SELECT e.*, ec.name as category_name 
                         FROM {$this->table} e 
                         LEFT JOIN event_categories ec ON e.category_id = ec.id");
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT e.*, ec.name as category_name 
                         FROM {$this->table} e 
                         LEFT JOIN event_categories ec ON e.category_id = ec.id 
                         WHERE e.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

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

            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function delete($id)
    {
        try {
            $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getByCategory($categoryId)
    {
        $this->db->query("SELECT e.*, ec.name as category_name 
                         FROM {$this->table} e 
                         LEFT JOIN event_categories ec ON e.category_id = ec.id 
                         WHERE e.category_id = :category_id");
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    public function getPublished()
    {
        $this->db->query("SELECT e.*, ec.name as category_name
                         FROM {$this->table} e
                         LEFT JOIN event_categories ec ON e.category_id = ec.id
                         WHERE e.is_published = 1");
        return $this->db->resultSet();
    }

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
            return [
                'quota' => isset($result['quota']) ? (int)$result['quota'] : 0,
                'registered' => $registeredCount,
                'available' => (isset($result['quota']) ? (int)$result['quota'] : 0) - $registeredCount
            ];
        }
        return false;
    }

    public function getParticipants($eventId)
    {
        $this->db->query("SELECT u.id, u.username, u.full_name, u.email, r.registration_code, r.registration_date, r.status, r.attended
                          FROM registrations r
                          JOIN users u ON r.id_user = u.id
                          WHERE r.id_event = :event_id
                          ORDER BY r.registration_date DESC");
        $this->db->bind(':event_id', $eventId);
        return $this->db->resultSet();
    }

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

    public function getUpcomingEventsCount($currentDate)
    {
        $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE start_date >= :current_date AND is_published = 1");
        $this->db->bind(':current_date', $currentDate);
        $result = $this->db->single();
        return $result ? $result['total'] : 0;
    }

    public function getRecentEvents($limit = 3)
    {
        $this->db->query("SELECT e.*, ec.name as category_name, 
                        (SELECT COUNT(*) FROM registrations r WHERE r.id_event = e.id AND r.status != 'canceled') as participants_count
                        FROM {$this->table} e
                        LEFT JOIN event_categories ec ON e.category_id = ec.id
                        ORDER BY e.id DESC
                        LIMIT :limit");
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getRecommendedEvents($userId, $limit = 3)
    {
        try {
            // Log parameters for debugging
            error_log("Getting recommended events for user ID: " . $userId . ", limit: " . $limit);

            // First, check if there are any published future events
            $this->db->query("SELECT COUNT(*) as count FROM {$this->table} WHERE is_published = 1 AND start_date >= CURDATE()");
            $result = $this->db->single();
            $publishedCount = $result ? $result['count'] : 0;
            error_log("Total published future events: " . $publishedCount);

            if ($publishedCount == 0) {
                error_log("No published future events available");
                return [];
            }

            // Get the events the user has already registered for
            $this->db->query("SELECT id_event FROM registrations WHERE id_user = :user_id AND status != 'canceled'");
            $this->db->bind(':user_id', $userId);
            $userEvents = $this->db->resultSet();

            $registeredEventIds = [];
            foreach ($userEvents as $event) {
                $registeredEventIds[] = $event['id_event'];
            }

            error_log("User is registered for " . count($registeredEventIds) . " events");

            // Build the query for recommended events
            $query = "
                SELECT e.*, ec.name as category_name, 
                      (SELECT COUNT(*) FROM registrations r WHERE r.id_event = e.id AND r.status != 'canceled') as participants_count
                FROM {$this->table} e
                LEFT JOIN event_categories ec ON e.category_id = ec.id
                WHERE e.is_published = 1
                AND e.start_date >= CURDATE()";

            // Add the exclusion for events the user is already registered for
            if (!empty($registeredEventIds)) {
                $placeholders = implode(',', array_fill(0, count($registeredEventIds), '?'));
                $query .= " AND e.id NOT IN ($placeholders)";
            }

            $query .= " ORDER BY e.start_date ASC LIMIT ?";

            $this->db->query($query);

            // Bind the registered event IDs
            $paramIndex = 1;
            foreach ($registeredEventIds as $eventId) {
                $this->db->bind($paramIndex++, $eventId);
            }

            // Bind the limit parameter
            $this->db->bind($paramIndex, $limit, \PDO::PARAM_INT);

            $recommendedEvents = $this->db->resultSet();
            error_log("Found " . count($recommendedEvents) . " recommended events");

            return $recommendedEvents;
        } catch (\Exception $e) {
            error_log("Error in getRecommendedEvents: " . $e->getMessage());
            return [];
        }
    }
}
