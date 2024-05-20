<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kwmlist extends Model
{
    use HasFactory;

    // defines the table name in the database for this model to interact with it; fillable fields are the fields that can be mass assigned
    protected $fillable = [
        'listName',
        'created_at',
        'updated_at',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'updated_at' => 'datetime:Y-m-d\TH:i:s\Z'
    ];

    // a list can have many images
    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }

    // a list can have many notes
    public function notes(): HasMany
    {
        return $this->hasMany(Kwmnote::class, 'kwmlists_id');
    }

    // a list belongs to a user (owner) of the list
    public function kwmusers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kwmuser_lists', 'kwmlists_id', 'user_id');
    }

    // a list can have many todos
    public function kwmtodos(): hasMany
    {
        return $this->hasMany(Kwmtodo::class, 'kwmlists_id');
    }
}
