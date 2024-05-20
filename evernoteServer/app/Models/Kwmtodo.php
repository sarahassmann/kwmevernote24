<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kwmtodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'todoName',
        'todoDescription',
        'due_date',
        'user_id',
        'kwmlists_id',
        'kwmnotes_id'
    ];

    // kwmlist relationship with kwmtodo model; one to many relationship
    public function kwmlist(): BelongsTo
    {
        return $this->belongsTo(Kwmlist::class, 'kwmlists_id', 'id');
    }

    // a kwmtodo belongs to a tag and a tag belongs to many kwmtodos
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Kwmtag::class, 'kwmtodo_kwmtags', 'kwmtodos_id', 'kwmtags_id');
    }

    // kwmnote relationship with kwmtodo model; one to many relationship
    public function kwmnote(): BelongsTo
    {
        return $this->belongsTo(Kwmnote::class, 'kwmnotes_id', 'id');
    }
}
