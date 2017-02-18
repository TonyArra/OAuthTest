<?php

namespace App\Listeners;

use App\Events\NoteCreating;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoteCreatingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NoteCreating  $event
     * @return void
     */
    public function handle(NoteCreating $event)
    {
        $event->note->tags = json_encode([]);
    }
}
