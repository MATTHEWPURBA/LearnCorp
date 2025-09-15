<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Session Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $session->title }}</h1>
        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
            <span>Status: 
                <span class="px-2 py-1 rounded-full text-xs font-medium 
                    {{ $session->status === 'live' ? 'bg-green-100 text-green-800' : 
                       ($session->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 
                        'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst($session->status) }}
                </span>
            </span>
            <span>Start: {{ $session->start_time->format('M j, Y g:i A') }}</span>
            @if($session->end_time)
                <span>End: {{ $session->end_time->format('M j, Y g:i A') }}</span>
            @endif
        </div>
    </div>

    <!-- Session Controls (for instructors) -->
    @if(auth()->user()->id === $session->course->user_id)
        <div class="mb-6 flex space-x-4">
            @if($session->isScheduled())
                <button wire:click="startSession" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Start Session
                </button>
            @endif
            
            @if($session->isLive())
                <button wire:click="endSession" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    End Session
                </button>
            @endif
        </div>
    @endif

    <!-- Jitsi Video Conference -->
    @if($session->isLive() || $session->isScheduled())
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">Live Session</h2>
                <p class="text-sm text-gray-600">Room: {{ $session->room_name }}</p>
            </div>
            
            <div class="relative">
                <div id="jitsi-container" class="w-full h-[70vh] bg-gray-900 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
                        <p>Loading video conference...</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium mb-2">Session Not Available</h3>
                <p class="text-gray-600">
                    @if($session->isEnded())
                        This live session has ended.
                    @elseif($session->isCancelled())
                        This live session has been cancelled.
                    @else
                        This live session is not yet available.
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>

<!-- Jitsi Meet Integration -->
@if($session->isLive() || $session->isScheduled())
<script src="https://meet.jit.si/external_api.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('jitsi-container');
        
        if (container) {
            try {
                const api = new JitsiMeetExternalAPI('meet.jit.si', {
                    roomName: '{{ $session->room_name }}',
                    parentNode: container,
                    userInfo: {
                        displayName: '{{ auth()->user()->name }}',
                        email: '{{ auth()->user()->email }}'
                    },
                    configOverwrite: {
                        startWithAudioMuted: true,
                        startWithVideoMuted: true,
                        enableWelcomePage: false,
                        prejoinPageEnabled: false
                    },
                    interfaceConfigOverwrite: {
                        TOOLBAR_BUTTONS: [
                            'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                            'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                            'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                            'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                            'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone'
                        ],
                        SHOW_JITSI_WATERMARK: false,
                        SHOW_WATERMARK_FOR_GUESTS: false,
                        SHOW_POWERED_BY: false
                    }
                });

                // Handle API events
                api.addEventListeners({
                    readyToClose: function() {
                        console.log('Jitsi session ended');
                    },
                    participantLeft: function(participant) {
                        console.log('Participant left:', participant);
                    },
                    participantJoined: function(participant) {
                        console.log('Participant joined:', participant);
                    }
                });
            } catch (error) {
                console.error('Failed to initialize Jitsi:', error);
                container.innerHTML = '<div class="text-center text-white"><p>Failed to load video conference. Please refresh the page.</p></div>';
            }
        }
    });
</script>
@endif
