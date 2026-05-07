<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    public $timestamps = false; // Only created_at is needed as per Schema.sql

    protected $casts = [
        'created_at' => 'datetime',
    ];

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

    /**
     * New ticket submitted by a user (first log row for that ticket).
     */
    public function isNewSubmission(): bool
    {
        return $this->old_status === null && $this->new_status === 'pending';
    }

    public function isCancellation(): bool
    {
        return $this->new_status === 'cancelled';
    }

    public static function formatStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending',
            'approved' => 'OnQueue',
            'in_progress' => 'In progress',
            'resolved' => 'Resolved',
            'disapproved' => 'Disapproved',
            'cancelled' => 'Cancelled',
            default => str_replace('_', ' ', ucwords($status, '_')),
        };
    }

    public function newStatusLabel(): string
    {
        return self::formatStatusLabel($this->new_status);
    }
}
