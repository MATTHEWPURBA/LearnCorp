'use client'

import { useEffect, useRef, useState } from 'react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Video, Mic, MicOff, VideoOff, Phone } from 'lucide-react'

interface JitsiRoomProps {
  roomName: string
  displayName?: string
}

declare global {
  interface Window {
    JitsiMeetExternalAPI: any
  }
}

export default function JitsiRoom({ roomName, displayName }: JitsiRoomProps) {
  const jitsiContainerRef = useRef<HTMLDivElement>(null)
  const jitsiApiRef = useRef<any>(null)
  const [isLoaded, setIsLoaded] = useState(false)
  const [isJoined, setIsJoined] = useState(false)
  const [isMuted, setIsMuted] = useState(false)
  const [isVideoOff, setIsVideoOff] = useState(false)

  useEffect(() => {
    loadJitsiScript()
    
    return () => {
      if (jitsiApiRef.current) {
        jitsiApiRef.current.dispose()
      }
    }
  }, [])

  const loadJitsiScript = () => {
    if (window.JitsiMeetExternalAPI) {
      initializeJitsi()
      return
    }

    const script = document.createElement('script')
    script.src = 'https://meet.jit.si/external_api.js'
    script.async = true
    script.onload = () => {
      setIsLoaded(true)
      initializeJitsi()
    }
    script.onerror = () => {
      console.error('Failed to load Jitsi script')
    }
    document.body.appendChild(script)
  }

  const initializeJitsi = () => {
    if (!jitsiContainerRef.current || !window.JitsiMeetExternalAPI) return

    const options = {
      roomName: `lms-${roomName}`,
      parentNode: jitsiContainerRef.current,
      width: '100%',
      height: 600,
      userInfo: {
        displayName: displayName || 'Student',
      },
      configOverwrite: {
        startWithAudioMuted: false,
        startWithVideoMuted: false,
        enableWelcomePage: false,
        prejoinPageEnabled: false,
      },
      interfaceConfigOverwrite: {
        TOOLBAR_BUTTONS: [
          'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
          'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
          'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
          'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
          'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone', 'security'
        ],
        SHOW_JITSI_WATERMARK: false,
        SHOW_WATERMARK_FOR_GUESTS: false,
        SHOW_BRAND_WATERMARK: false,
        SHOW_POWERED_BY: false,
      }
    }

    jitsiApiRef.current = new window.JitsiMeetExternalAPI('meet.jit.si', options)

    // Event listeners
    jitsiApiRef.current.addEventListeners({
      videoConferenceJoined: () => {
        setIsJoined(true)
      },
      videoConferenceLeft: () => {
        setIsJoined(false)
      },
      readyToClose: () => {
        jitsiApiRef.current.dispose()
      }
    })
  }

  const toggleMute = () => {
    if (jitsiApiRef.current) {
      jitsiApiRef.current.executeCommand('toggleAudio')
      setIsMuted(!isMuted)
    }
  }

  const toggleVideo = () => {
    if (jitsiApiRef.current) {
      jitsiApiRef.current.executeCommand('toggleVideo')
      setIsVideoOff(!isVideoOff)
    }
  }

  const hangUp = () => {
    if (jitsiApiRef.current) {
      jitsiApiRef.current.executeCommand('hangup')
    }
  }

  if (!isLoaded) {
    return (
      <Card className="w-full">
        <CardContent className="flex items-center justify-center h-96">
          <div className="text-center space-y-4">
            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
            <p>Loading video conference...</p>
          </div>
        </CardContent>
      </Card>
    )
  }

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle className="flex items-center justify-between">
          <span>Live Class: {roomName}</span>
          {isJoined && (
            <div className="flex space-x-2">
              <Button
                size="sm"
                variant={isMuted ? "destructive" : "outline"}
                onClick={toggleMute}
              >
                {isMuted ? <MicOff className="h-4 w-4" /> : <Mic className="h-4 w-4" />}
              </Button>
              <Button
                size="sm"
                variant={isVideoOff ? "destructive" : "outline"}
                onClick={toggleVideo}
              >
                {isVideoOff ? <VideoOff className="h-4 w-4" /> : <Video className="h-4 w-4" />}
              </Button>
              <Button
                size="sm"
                variant="destructive"
                onClick={hangUp}
              >
                <Phone className="h-4 w-4" />
              </Button>
            </div>
          )}
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div ref={jitsiContainerRef} className="w-full" />
        {!isJoined && (
          <div className="mt-4 p-4 bg-blue-50 rounded-lg">
            <p className="text-sm text-blue-800">
              <strong>Welcome to the live class!</strong> Your microphone and camera will be enabled by default. 
              You can control them using the buttons above once you join.
            </p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
