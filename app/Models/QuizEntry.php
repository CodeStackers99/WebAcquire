<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(QuizEntry::class, 'user_id');
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
