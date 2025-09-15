<?php

namespace App\Livewire;

use App\Models\LiveSession as LiveSessionModel;
use Livewire\Component;

class LiveSession extends Component
{
    public LiveSessionModel $session;

    public function mount(LiveSessionModel $session)
    {
        $this->session = $session;
    }

    public function startSession()
    {
        $this->session->update(['status' => 'live']);
        session()->flash('message', 'Live session started!');
    }

    public function endSession()
    {
        $this->session->update([
            'status' => 'ended',
            'end_time' => now(),
        ]);
        session()->flash('message', 'Live session ended.');
    }

    public function render()
    {
        return view('livewire.live-session');
    }
}
