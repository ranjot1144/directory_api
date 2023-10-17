<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Add this line
        'name',
        'title',
        'url',
        'description',
        // Other fillable columns
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_website');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function hasVoted($user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }
}
