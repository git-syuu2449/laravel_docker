<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Summary of Choice
 */
class Choice extends Model
{
    /** @use HasFactory<\Database\Factories\Models\ChoiceFactory> */
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
