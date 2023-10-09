<?php

declare(strict_types=1);

namespace App\Helpers;

use stdClass;

class BookingsHelper
{
    public string $personName;
    public int $classesId;
    public string $date;
    public string $startDate;
    public string $endDate;
    public int $page;

    public function __construct() { }

    public static function withStdClass(stdClass $data): self
    {
        $instance = new self();
        if(isset($data->personName)) $instance->personName = $data->personName;
        if(isset($data->date)) $instance->date = $data->date;
        if(isset($data->classesId)) $instance->classesId = $data->classesId;
        if(isset($data->page)) $instance->page = (int) $data->page;
        return $instance;
    }

    public static function withDates(string $startDate, string $endDate): self {
        $instance = new self();
        $instance->startDate = $startDate;
        $instance->endDate = $endDate;
        return $instance;
    }
}
