<?php
Namespace Core;
class Flasher
{
    private static $icons = [
        'success' => '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>',
        'error' => '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
        </svg>',
        'warning' => '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
        </svg>'
    ];

    private static $colors = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'orange'
    ];

    public static function render()
    {
        if (!self::hasMessages()) {
            return '';
        }

        return self::renderContainer(self::renderFlashMessages());
    }

    private static function renderContainer($content)
    {
        return '<div class="fixed z-[100] top-5 right-5 flex flex-col gap-4 w-full max-w-xs">' .
            $content .
            '</div>';
    }

    private static function renderFlashMessages()
    {
        $output = '';
        foreach (self::display() as $index => $flash) {
            $output .= self::renderSingleFlash($flash, $index);
        }
        return $output;
    }

    private static function renderSingleFlash($flash, $index)
    {
        $type = $flash['type'];
        $color = self::$colors[$type] ?? 'blue';
        $icon = self::$icons[$type] ?? self::$icons['warning'];
        $toastId = "toast-{$type}-{$index}";
        $toastClass = self::$toastClasses[$type] ?? '';

        return "
            <div id='{$toastId}' class='flex items-center w-full max-w-xs p-3 pe-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800' role='alert'>
                <div class='inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-{$color}-500 bg-{$color}-100 rounded-lg dark:bg-{$color}-800 dark:text-{$color}-200'>
                    {$icon}
                </div>
                <div class='ms-3 text-sm font-normal'>{$flash['message']} {$flash['action']}</div>
                <button type='button' class='ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700' data-dismiss-target='#{$toastId}' aria-label='Close'>
                    <span class='sr-only'>Close</span>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                </button>
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('{$toastId}');
                    if (toast) {
                        toast.style.opacity = '0';
                        toast.style.transition = 'opacity 0.5s ease-in-out';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 3000);
            </script>
        ";
    }

    public static function setFlash($message, $action = '', $type = 'success')
    {
        $_SESSION['flash'][] = [
            'message' => $message,
            'action' => $action,
            'type' => $type
        ];
    }

    public static function hasMessages()
    {
        return isset($_SESSION['flash']) && !empty($_SESSION['flash']);
    }

    public static function display()
    {
        if (self::hasMessages()) {
            $messages = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $messages;
        }
        return [];
    }
}
