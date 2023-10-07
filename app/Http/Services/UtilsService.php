<?php

namespace App\Http\Services;

use App\Models\Classes;
use Carbon\Carbon;

/**
 * UtilsService
 * The goal of this service is to have a set of functions that serve a generic purpose
 */
class UtilsService
{
    /**
     * parseDate
     * Parse string date to Date type
     * @param  mixed $date
     * @return Carbon
     */
    public function parseDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}

