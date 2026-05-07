<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposal extends Model
{
    protected $fillable = ['ticket_id', 'cause_of_disposal', 'admin_name', 'file_path'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
