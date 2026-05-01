<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    public $timestamps = false; // Only created_at is needed as per Schema.sql

    protected $fillable = [
        'ticket_id',
        'changed_by',
        'old_status',
        'new_status',
        'remarks',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
