<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getValidationRules($websiteId, $userId): array
    {
        return [
            'user_id' => [
                'required',
                Rule::unique('votes', 'user_id')
                    ->where(function ($query) use ($websiteId) {
                        return $query->where('website_id', $websiteId);
                    })
                    ->ignore($userId, 'user_id')
            ]
        ];
    }
    
}
