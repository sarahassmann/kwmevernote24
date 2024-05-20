<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kwmnote extends Model
{
    use HasFactory;
    protected $fillable = [
        'noteTitle',
        'noteDescription',
        'user_id',
        'kwmlists_id'
    ];

    // a note can only belong to one list
    public function list(): BelongsTo
    {
        return $this->belongsTo(Kwmlist::class, 'kwmlists_id');
    }

    // a note can have many tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Kwmtag::class, 'kwmnote_kwmtags','kwmnotes_id', 'kwmtags_id');
    }

    // a note can have many todos
    public function kwmtodos(): HasMany
    {
        return $this->hasMany(Kwmtodo::class, 'kwmnotes_id');
    }



}
