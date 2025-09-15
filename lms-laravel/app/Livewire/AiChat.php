<?php

namespace App\Livewire;

use Livewire\Component;

class AiChat extends Component
{
    public $messages = [];
    public $newMessage = '';
    public $isLoading = false;

    public function mount()
    {
        $this->messages = [
            [
                'role' => 'assistant',
                'content' => 'Hello! I\'m your AI learning assistant. How can I help you with your studies today?'
            ]
        ];
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $this->newMessage
        ];

        $userMessage = $this->newMessage;
        $this->newMessage = '';
        $this->isLoading = true;

        // Simulate AI response (in real implementation, this would call WebLLM)
        $this->dispatch('ai-response', [
            'message' => "I understand you're asking about: \"{$userMessage}\". This is a simulated response. In the actual implementation, this would be powered by WebLLM running in your browser."
        ]);
    }

    public function addAiResponse($message)
    {
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $message
        ];
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}
