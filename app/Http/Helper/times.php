<?php

use Carbon\Carbon;


if (!function_exists('formatDaysLate')) {
    function formatDaysLate($dueDate, $locale = null)
    {
        if (!$dueDate) {
            return null;
        }

        $today = Carbon::today(config('app.timezone'));
        $due = $dueDate instanceof Carbon ? $dueDate : Carbon::parse($dueDate);

        $diff = $due->diff($today);

        $locale = lang();

        if ($locale === 'ar') {
            $years   = $diff->y ? $diff->y . ' سنة ' : '';
            $months  = $diff->m ? $diff->m . ' شهر ' : '';
            $days    = $diff->d ? $diff->d . ' يوم' : '';
            return trim($years . $months . $days) ?: 'اليوم';
        }

        // Default English
        $years   = $diff->y ? $diff->y . ' year' . ($diff->y > 1 ? 's ' : ' ') : '';
        $months  = $diff->m ? $diff->m . ' month' . ($diff->m > 1 ? 's ' : ' ') : '';
        $days    = $diff->d ? $diff->d . ' day' . ($diff->d > 1 ? 's' : '') : '';
        return trim($years . $months . $days) ?: 'Today';
    }
}
