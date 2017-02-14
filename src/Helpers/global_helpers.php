<?php
namespace SmartEmailing\v3\Helpers;

/**
 * Converts the given date to allowed format
 *
 * @param string $date date string that can be converted by strtotime
 * @param bool   $convert
 *
 * @return false|string
 */
function convertDate($date, $convert = true)
{
    if (!$convert) {
        return $date;
    }

    return date('Y-m-d H:i:s', strtotime($date));
}