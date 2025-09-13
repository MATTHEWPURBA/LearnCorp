'use client'

import { useState, useRef, useEffect } from 'react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Play, Download, FileText } from 'lucide-react'
import { getPublicUrl } from '@/lib/r2'

interface LessonPlayerProps {
  title: string
  description?: string
  youtubeId?: string
  assetKey?: string
  assetName?: string
}

export default function LessonPlayer({ 
  title, 
  description, 
  youtubeId, 
  assetKey,
  assetName 
}: LessonPlayerProps) {
  const [isPlaying, setIsPlaying] = useState(false)
  const playerRef = useRef<HTMLIFrameElement>(null)

  useEffect(() => {
    if (youtubeId && isPlaying) {
      // YouTube player will load when iframe is visible
    }
  }, [youtubeId, isPlaying])

  const handleDownload = () => {
    if (assetKey) {
      const url = getPublicUrl(assetKey)
      const link = document.createElement('a')
      link.href = url
      link.download = assetName || 'lesson-resource'
      link.click()
    }
  }

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle>{title}</CardTitle>
        {description && (
          <p className="text-sm text-muted-foreground">{description}</p>
        )}
      </CardHeader>
      <CardContent className="space-y-4">
        {/* YouTube Video Player */}
        {youtubeId && (
          <div className="aspect-video bg-black rounded-lg overflow-hidden">
            {isPlaying ? (
              <iframe
                ref={playerRef}
                width="100%"
                height="100%"
                src={`https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`}
                title={title}
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
                className="w-full h-full"
              />
            ) : (
              <div className="w-full h-full flex items-center justify-center relative">
                <img
                  src={`https://img.youtube.com/vi/${youtubeId}/maxresdefault.jpg`}
                  alt={title}
                  className="w-full h-full object-cover"
                />
                <Button
                  onClick={() => setIsPlaying(true)}
                  size="lg"
                  className="absolute inset-0 m-auto w-16 h-16 rounded-full bg-red-600 hover:bg-red-700"
                >
                  <Play className="h-8 w-8 ml-1" />
                </Button>
              </div>
            )}
          </div>
        )}

        {/* Resource Download */}
        {assetKey && (
          <div className="flex items-center justify-between p-4 bg-muted rounded-lg">
            <div className="flex items-center space-x-3">
              <FileText className="h-5 w-5 text-muted-foreground" />
              <span className="font-medium">{assetName || 'Lesson Resource'}</span>
            </div>
            <Button onClick={handleDownload} variant="outline" size="sm">
              <Download className="h-4 w-4 mr-2" />
              Download
            </Button>
          </div>
        )}

        {!youtubeId && !assetKey && (
          <div className="text-center py-8 text-muted-foreground">
            <FileText className="h-12 w-12 mx-auto mb-4 opacity-50" />
            <p>No content available for this lesson yet.</p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
