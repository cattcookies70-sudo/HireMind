<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'school',
        'major',
        'cv_path',
    ];

    /**
     * Relation inverse avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}