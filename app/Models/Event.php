<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'name',
        'description',
        'category',
        'event_date',
        'event_time',
        'location',
        'latitude',
        'longitude',
        'max_participants',
        'price',
        'image',
        'status',
        'source',
        'source_url',
    ];

    protected $casts = [
        'event_date' => 'date',
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants')
            ->withPivot('status', 'registered_at')
            ->withTimestamps();
    }

    public function favouritedBy()
    {
        return $this->belongsToMany(User::class, 'favourites')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isFull()
    {
        return $this->participants()->count() >= $this->max_participants;
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return $this->getFallbackImageUrl();
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return $this->getFallbackImageUrl();
    }

    public function getFallbackImageUrlAttribute()
    {
        return $this->getFallbackImageUrl();
    }

    protected function getFallbackImageUrl()
    {
        $path = match ($this->category) {
            'running' => 'events/placeholders/running.png',
            'hiking' => 'events/placeholders/hiking.png',
            'cycling' => 'events/placeholders/cycling.png',
            default => 'events/placeholders/default.png',
        };

        return asset('storage/' . $path);
    }
}