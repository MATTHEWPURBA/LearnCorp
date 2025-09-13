'use client'

import { useState, useEffect } from 'react'
import { useParams, useRouter } from 'next/navigation'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { ArrowLeft, Users, Clock, Calendar } from 'lucide-react'
import JitsiRoom from '@/components/JitsiRoom'

interface Course {
  id: string
  title: string
  slug: string
}

export default function LiveClassPage() {
  const params = useParams()
  const router = useRouter()
  const [course, setCourse] = useState<Course | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchCourseData()
  }, [params.slug])

  const fetchCourseData = async () => {
    try {
      const response = await fetch(`/api/courses?slug=${params.slug}`)
      if (response.ok) {
        const courseData = await response.json()
        setCourse(courseData)
      }
    } catch (error) {
      console.error('Error fetching course data:', error)
    } finally {
      setLoading(false)
    }
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-[400px]">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>
    )
  }

  if (!course) {
    return (
      <div className="text-center py-12">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Course not found</h2>
        <p className="text-gray-600 mb-4">The course you're looking for doesn't exist.</p>
        <Button onClick={() => router.push('/dashboard')}>
          Back to Dashboard
        </Button>
      </div>
    )
  }

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <Button variant="outline" onClick={() => router.push(`/course/${params.slug}`)}>
            <ArrowLeft className="h-4 w-4 mr-2" />
            Back to Course
          </Button>
          <div>
            <h1 className="text-2xl font-bold">Live Class</h1>
            <p className="text-muted-foreground">{course.title}</p>
          </div>
        </div>
      </div>

      {/* Live Class Info */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center space-x-2">
            <Users className="h-5 w-5" />
            <span>Live Session Information</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div className="flex items-center space-x-2">
              <Calendar className="h-4 w-4 text-muted-foreground" />
              <span className="text-sm">Session: {course.title}</span>
            </div>
            <div className="flex items-center space-x-2">
              <Users className="h-4 w-4 text-muted-foreground" />
              <span className="text-sm">Room: lms-{params.slug}</span>
            </div>
            <div className="flex items-center space-x-2">
              <Clock className="h-4 w-4 text-muted-foreground" />
              <span className="text-sm">Status: Live Now</span>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Jitsi Video Conference */}
      <JitsiRoom 
        roomName={params.slug as string}
        displayName="Student"
      />

      {/* Instructions */}
      <Card>
        <CardHeader>
          <CardTitle>Live Class Instructions</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="bg-blue-50 p-4 rounded-lg">
            <h4 className="font-medium text-blue-900 mb-2">Getting Started</h4>
            <ul className="text-sm text-blue-800 space-y-1">
              <li>• Your microphone and camera will be enabled by default</li>
              <li>• Use the controls above the video to mute/unmute or turn video on/off</li>
              <li>• Raise your hand using the hand icon if you have questions</li>
              <li>• Use the chat feature to communicate with other participants</li>
            </ul>
          </div>
          
          <div className="bg-green-50 p-4 rounded-lg">
            <h4 className="font-medium text-green-900 mb-2">Best Practices</h4>
            <ul className="text-sm text-green-800 space-y-1">
              <li>• Test your audio and video before joining</li>
              <li>• Use headphones to avoid echo</li>
              <li>• Mute yourself when not speaking</li>
              <li>• Be respectful and follow the instructor's guidance</li>
            </ul>
          </div>

          <div className="bg-yellow-50 p-4 rounded-lg">
            <h4 className="font-medium text-yellow-900 mb-2">Technical Requirements</h4>
            <ul className="text-sm text-yellow-800 space-y-1">
              <li>• Modern web browser with WebRTC support</li>
              <li>• Stable internet connection</li>
              <li>• Microphone and camera (optional but recommended)</li>
              <li>• Allow browser permissions for camera and microphone</li>
            </ul>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
