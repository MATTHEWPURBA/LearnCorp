<div class="h-96 flex flex-col">
    <!-- Chat Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
        @foreach($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message['role'] === 'user' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                    {{ $message['content'] }}
                </div>
            </div>
        @endforeach
        
        @if($isLoading)
            <div class="flex justify-start">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-gray-200 text-gray-800">
                    <div class="flex items-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-600"></div>
                        <span>AI is thinking...</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Message Input -->
    <div class="border-t p-4">
        <form wire:submit.prevent="sendMessage" class="flex space-x-2">
            <input 
                type="text" 
                wire:model="newMessage" 
                placeholder="Ask me anything about your studies..."
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                {{ $isLoading ? 'disabled' : '' }}
            >
            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                {{ $isLoading ? 'disabled' : '' }}
            >
                Send
            </button>
        </form>
    </div>
</div>

<!-- WebLLM Integration Script -->
<script type="module">
    // WebLLM Integration (runs in browser)
    let engine = null;
    let isInitialized = false;

    async function initializeWebLLM() {
        try {
            // Check if WebGPU is supported
            if (!navigator.gpu) {
                console.log('WebGPU not supported, using fallback');
                return;
            }

            // Import WebLLM
            const { CreateMLCEngine } = await import('https://esm.run/@mlc-ai/web-llm');
            
            // Initialize with a small model for fast loading
            engine = await CreateMLCEngine({ 
                model: "Llama-3-8B-Instruct-q4f32_1-MLC" 
            });
            
            isInitialized = true;
            console.log('WebLLM initialized successfully');
        } catch (error) {
            console.log('WebLLM initialization failed:', error);
        }
    }

    // Initialize WebLLM on page load
    initializeWebLLM();

    // Listen for AI response events
    document.addEventListener('ai-response', async (event) => {
        const { message } = event.detail;
        
        // If WebLLM is available, use it for enhanced responses
        if (isInitialized && engine) {
            try {
                const response = await engine.chat.completions.create({
                    messages: [
                        { role: "system", content: "You are a helpful learning assistant. Provide concise, educational responses." },
                        { role: "user", content: message }
                    ],
                    stream: true
                });

                let fullResponse = "";
                for await (const chunk of response) {
                    fullResponse += chunk.choices?.[0]?.delta?.content || "";
                }
                
                // Update the Livewire component with the AI response
                @this.call('addAiResponse', fullResponse);
            } catch (error) {
                console.log('WebLLM error:', error);
                // Fallback to the original message
                @this.call('addAiResponse', message);
            }
        } else {
            // Fallback to the original message
            @this.call('addAiResponse', message);
        }
    });

    // Auto-scroll to bottom when new messages arrive
    function scrollToBottom() {
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Watch for new messages and scroll
    const observer = new MutationObserver(scrollToBottom);
    observer.observe(document.getElementById('chat-messages'), { childList: true });
</script>
