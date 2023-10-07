<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = ['classes_id', 'person_name'];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'classes_id', 'id');
    }
}
