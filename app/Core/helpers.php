<?php
function old(string $field, string $default = ''): string
{
    if (isset($_SESSION['old_input'][$field])) {
        $value = $_SESSION['old_input'][$field];
        unset($_SESSION['old_input'][$field]);
        return htmlspecialchars($value);
    }
    return htmlspecialchars($default);
}

function error_field(string $field): string
{
    if (isset($_SESSION['validation_errors'][$field])) {
        $error = $_SESSION['validation_errors'][$field];
        unset($_SESSION['validation_errors'][$field]);
        return sprintf(
            '<p class="mt-1 text-sm text-red-500">%s</p>',
            htmlspecialchars($error)
        );
    }
    return '';
}