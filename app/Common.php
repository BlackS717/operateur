<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('formatDateFr')) {
    /**
     * Formate une date/heure SQLite (ou un timestamp UNIX) en date lisible fr (jj/mm/aaaa hh:mm).
     */
    function formatDateFr(?string $value): string
    {
        if (empty($value)) {
            return '-';
        }

        $timestamp = is_numeric($value) ? (int) $value : strtotime($value);
        if ($timestamp === false || $timestamp === 0) {
            return esc($value);
        }

        return date('d/m/Y H:i', $timestamp);
    }
}
