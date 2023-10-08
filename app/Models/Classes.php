<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $fillable = ['name', 'date', 'capacity'];

    public function bookings(){
        return $this->hasMany(Bookings::class);
    }

    public static function getIdByDate($date): int
    {
        return self::where('date', $date)
                   ->value('id');
    }
}
