<?php

declare(strict_types=1);

namespace App\Helpers;

use stdClass;

class ClassesHelper
{
    public string $name;
    public string $startDate;
    public string $endDate;
    public int $capacity;
    public int $page;

    public function __construct() { }

    public static function withStdClass(stdClass $data ): self
    {
        $instance = new self();
        if(isset($data->name)) $instance->name = $data->name;
        if(isset($data->startDate)) $instance->startDate = $data->startDate;
        if(isset($data->endDate)) $instance->endDate = $data->endDate;
        if(isset($data->capacity)) $instance->capacity = $data->capacity;
        if(isset($data->page)) $instance->page = (int) $data->page;
        return $instance;
    }

    public static function withDates(string $startDate, string $endDate ): self
    {
        $instance = new self();
        $instance->startDate = $startDate;
        $instance->endDate = $endDate;
        return $instance;
    }
}
