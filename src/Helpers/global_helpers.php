<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Helpers;

/**
 * Converts the given date to allowed format
 *
 * @param string $date date string that can be converted by strtotime
 * @param bool   $convert
 *
 * @return string
 */
function convertDate($date, $convert = true)
{
    if ($convert === false) {
        return $date;
    }

    return date('Y-m-d H:i:s', strtotime($date));
}
