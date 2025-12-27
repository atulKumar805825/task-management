<?php

if (! function_exists('task_status_color')) {
    function task_status_color(string $status): string
    {
        return match ($status) {
            'pending'      => 'warning',
            'in_progress'  => 'info',
            'completed'    => 'success',
            default        => 'secondary',
        };
    }
}

if (! function_exists('task_priority_color')) {
    function task_priority_color(string $priority): string
    {
        return match ($priority) {
            'high'   => 'danger',
            'medium' => 'warning',
            'low'    => 'success',
            default  => 'secondary',
        };
    }
}
