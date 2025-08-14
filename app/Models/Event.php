<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date','location','description','price'];

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function tiket() {
        return $this->hasMany(Tiket::class);
    }

}
