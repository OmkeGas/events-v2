<?php
namespace Core;
use Core\Database;

class Validator
{
    private array $errors = [];
    private Database $db;
    
    private const ERROR_MESSAGES = [
        'required' => 'The %s cannot be empty',
        'min' => 'The %s must be at least %d characters',
        'max' => 'The %s must not exceed %d characters',
        'email' => 'Please enter a valid email address',
        'unique' => 'This %s is already taken',
        'matches' => 'The %s and %s must match',
        'numeric' => 'The %s must be a number',
        'min_value' => 'The %s must be at least %d',
        'date' => 'The %s must be a valid date',
        'greater_than_equal_to_field' => 'The %s must be on or after %s',
        'in' => 'The %s must be one of the allowed values',
        'uploaded_file' => 'Please upload a valid file for %s',
        'mime_types' => 'The %s must be of type: %s',
        'max_size' => 'The %s must not exceed %s'
    ];

    public function __construct()
    {
        $this->db = new Database();
    }

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $this->sanitize($data[$field] ?? '');

            if (in_array('required', $fieldRules) && empty(trim($value))) {
                $this->addError($field, sprintf(self::ERROR_MESSAGES['required'], 
                    $this->formatFieldName($field)));
                continue;
            }

            if (empty(trim($value))) {
                continue;
            }

            $this->validateField($field, $value, $fieldRules, $data);
        }

        return empty($this->errors);
    }

    private function validateField(string $field, string $value, array $rules, array $data): void
    {
        foreach ($rules as $rule) {
            if ($rule === 'required') continue;

            if (is_string($rule)) {
                $this->applyRule($field, $value, $rule, null, $data);
            } elseif (is_array($rule)) {
                $this->applyRule($field, $value, $rule[0], $rule[1] ?? null, $data);
            }
        }
    }

    private function applyRule(string $field, string $value, string $rule, $param = null, array $data = []): void
    {
        $fieldName = $this->formatFieldName($field);

        switch ($rule) {
            case 'min':
                if (strlen($value) < $param) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['min'], 
                        $fieldName, $param));
                }
                break;

            case 'max':
                if (strlen($value) > $param) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['max'], 
                        $fieldName, $param));
                }
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, self::ERROR_MESSAGES['email']);
                }
                break;

            case 'unique':
                $this->validateUnique($field, $value, $param);
                break;

            case 'matches':
                if (!isset($param['field_to_match']) || $value !== $data[$param['field_to_match']] ?? '') {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['matches'],
                        $this->formatFieldName($field), $this->formatFieldName($param['field_to_match'])));
                }
                break;

            case 'numeric':
                if (!is_numeric($value)) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['numeric'], $fieldName));
                }
                break;

            case 'min_value':
                if (!is_numeric($value) || floatval($value) < $param) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['min_value'], $fieldName, $param));
                }
                break;

            case 'date':
                // Accept both Y-m-d and m/d/Y formats
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                if (!$date) {
                    // Try the datepicker format
                    $date = \DateTime::createFromFormat('m/d/Y', $value);
                }

                if (!$date) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['date'], $fieldName));
                }
                break;

            case 'greater_than_equal_to_field':
                // Try both date formats for both fields
                $date1 = \DateTime::createFromFormat('Y-m-d', $value);
                if (!$date1) {
                    $date1 = \DateTime::createFromFormat('m/d/Y', $value);
                }

                $date2 = \DateTime::createFromFormat('Y-m-d', $data[$param] ?? '');
                if (!$date2) {
                    $date2 = \DateTime::createFromFormat('m/d/Y', $data[$param] ?? '');
                }

                if (!$date1 || !$date2 || $date1 < $date2) {
                    $this->addError($field, sprintf(
                        self::ERROR_MESSAGES['greater_than_equal_to_field'],
                        $fieldName,
                        $this->formatFieldName($param)
                    ));
                }
                break;

            case 'in':
                if (!in_array($value, $param)) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['in'], $fieldName));
                }
                break;

            case 'uploaded_file':
                // If no file was uploaded or if it's a first visit to the form, don't trigger an error
                if (isset($_FILES[$field]) && ($_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE || $_FILES[$field]['error'] === UPLOAD_ERR_OK)) {
                    // If error is UPLOAD_ERR_NO_FILE, we'll skip this validation unless it's required
                    if ($_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
                        // Check if we also have a 'required' rule for this field
                        if (in_array('required', $rules[$field] ?? [])) {
                            $this->addError($field, sprintf(self::ERROR_MESSAGES['uploaded_file'], $fieldName));
                        }
                    }
                } else {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['uploaded_file'], $fieldName));
                }
                break;

            case 'mime_types':
                if (!isset($_FILES[$field]) || !in_array($_FILES[$field]['type'], $param)) {
                    $allowedTypes = implode(', ', $param);
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['mime_types'], $fieldName, $allowedTypes));
                }
                break;

            case 'max_size':
                $maxSizeBytes = $this->convertSizeToBytes($param);
                if (!isset($_FILES[$field]) || $_FILES[$field]['size'] > $maxSizeBytes) {
                    $this->addError($field, sprintf(self::ERROR_MESSAGES['max_size'], $fieldName, $param));
                }
                break;
        }
    }

    private function validateUnique(string $field, string $value, ?array $param): void
    {
        if (!is_array($param) || !isset($param['table'], $param['column'])) {
            throw new InvalidArgumentException("Invalid unique rule parameters");
        }

        $query = "SELECT COUNT(*) as count FROM {$param['table']} WHERE {$param['column']} = :value";
        $this->db->query($query);
        $this->db->bind(':value', $value);

        if ($this->db->single()['count'] > 0) {
            $this->addError($field, sprintf(self::ERROR_MESSAGES['unique'], 
                $this->formatFieldName($field)));
        }
    }

    private function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value));
    }

    private function formatFieldName(string $field): string
    {
        return ucwords(str_replace('_', ' ', $field));
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function hasError(string $field): bool
    {
        return isset($_SESSION['validation_errors'][$field]);
    }

    public static function getError(string $field): string
    {
        return $_SESSION['validation_errors'][$field] ?? '';
    }

    public static function clearErrors(): void
    {
        unset($_SESSION['validation_errors'], $_SESSION['old_input']);
    }

    private function convertSizeToBytes(string $size): int
    {
        $unit = strtoupper(substr($size, -1));
        $value = (int)substr($size, 0, -1);

        switch ($unit) {
            case 'K':
                return $value * 1024;
            case 'M':
                return $value * 1024 * 1024;
            case 'G':
                return $value * 1024 * 1024 * 1024;
            default:
                return $value;
        }
    }
}
