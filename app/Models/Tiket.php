<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','order_id','event_id','name','location','code','start_date','end_date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function tiket()
    {
        return $this->hasMany(Tiket::class);
    }

}
