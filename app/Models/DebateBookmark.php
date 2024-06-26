<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebateBookmark extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'debate_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function debate()
    {
        return $this->belongsTo(Debate::class);
    }
}
