<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

use App\Enums\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 権限
     * @var array
     */
    protected $casts = [
        'role' => Role::class,
    ];

    /**
     * 管理者か
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    /**
     * 一般ユーザーか
     * @return bool
     */
    public function isGeneral(): bool
    {
        return $this->role === Role::User;
    }

    /**
     * ゲストか
     * @return bool
     */
    public function isGuest(): bool
    {
        return $this->role === Role::Guest;
    }


    /**
     * questionリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Question, User>
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * choiceリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Choice, User>
     */
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    /**
     * id
     * @param mixed $query
     * @param mixed $id
     */
    public function scopeId(Builder $query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * with questions
     * @param mixed $query
     */
    public function scopeWithQuestions($query)
    {
        return $query->with('questions');
    }

    /**
     * with choices
     * @param mixed $query
     */
    public function scopeWithChoices($query)
    {
        return $query->with('choices');
    }

    /**
     * questions-choices
     * @param mixed $query
     */
    public function scopeLoadQuestionsChainChoices()
    {
        return $this->load([
            'questions.choices'
        ]);
    }
}
