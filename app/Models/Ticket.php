<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_no',
        'user_id',
        'service_type',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to',
        'admin_remarks',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            // Generate ticket number
            $date = now()->format('Ymd');
            $latest = self::where('ticket_no', 'like', "TKT-$date-%")->orderBy('id', 'desc')->first();
            $sequence = $latest ? intval(substr($latest->ticket_no, -4)) + 1 : 1;
            $ticket->ticket_no = "TKT-$date-".str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Calculate due date based on priority if not already set
            if (! $ticket->due_date) {
                $ticket->due_date = match (strtolower($ticket->priority)) {
                    'high' => now()->addHours(4),
                    'medium' => now()->addDay(),
                    'low' => now()->addDays(3),
                    default => now()->addDays(3),
                };
            }

            // Set default status if not set
            if (! $ticket->status) {
                $ticket->status = 'pending';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * service_type column stores ServiceType.code
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type', 'code');
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function recommendation()
    {
        return $this->hasOne(Recommendation::class);
    }

    public function disposal()
    {
        return $this->hasOne(Disposal::class);
    }
}
