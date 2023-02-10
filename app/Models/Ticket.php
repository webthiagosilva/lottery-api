<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $uuid
 * @property string $user_name
 * @property array $selected_numbers
 * @property array $machine_numbers
 * @property Carbon $machine_draw_at
 * @property boolean $is_active
 * @property boolean $is_winner
 */
class Ticket extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'machine_numbers',
        'machine_draw_at',
        'is_active',
        'is_winner',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'selected_numbers' => 'array',
        'machine_numbers' => 'array',
        'machine_draw_at' => 'datetime',
        'is_active' => 'boolean',
        'is_winner' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
