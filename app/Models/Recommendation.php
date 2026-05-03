<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['ticket_id', 'specs', 'file_path'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
