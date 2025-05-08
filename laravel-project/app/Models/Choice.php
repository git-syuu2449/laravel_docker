<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Summary of Choice
 */
class Choice extends Model
{
    /** @use HasFactory<\Database\Factories\Models\ChoiceFactory> */
    use HasFactory;
    use SoftDeletes;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
