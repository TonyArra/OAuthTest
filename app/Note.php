<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\NoteCreating;

class Note extends Model
{

    protected $fillable = ['content'];

    protected $events = [
        'creating' => NoteCreating::class
    ];

}
