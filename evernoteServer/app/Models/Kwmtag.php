<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kwmtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagName',
        'user_id'
    ];

    // a tag can be in many notes
    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Kwmnote::class, 'kwmnote_kwmtags', 'kwmtags_id', 'kwmnotes_id');
    }
}
