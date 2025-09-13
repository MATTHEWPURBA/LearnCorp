'use client'

import { useState, useEffect } from 'react'
import { useParams, useRouter } from 'next/navigation'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { ArrowLeft, ArrowRight } from 'lucide-react'
import LessonPlayer from '@/components/LessonPlayer'
import LessonAssistant from '@/components/LessonAssistant'
import QuizComponent from '@/components/QuizComponent'

interface Lesson {
  id: string
  title: string
  description?: string
  youtubeId?: string
  assetKey?: string
  order: number
  quiz?: {
    id: string
    title?: string
    questions: Array<{
      id: string
      prompt: string
      a: string
      b: string
      c: string
      d: string
      correct: string
    }>
  }
}

interface Course {
  id: string
  title: string
  slug: string
  lessons: Lesson[]
}

export default function LessonPage() {
  const params = useParams()
  const router = useRouter()
  const [course, setCourse] = useState<Course | null>(null)
  const [currentLesson, setCurrentLesson] = useState<Lesson | null>(null)
  const [loading, setLoading] = useState(true)
  const [quizSubmitted, setQuizSubmitted] = useState(false)

  useEffect(() => {
    fetchCourseData()
  }, [params.slug])

  const fetchCourseData = async () => {
    try {
      const response = await fetch(`/api/courses?slug=${params.slug}`)
      if (response.ok) {
        const courseData = await response.json()
        setCourse(courseData)
        
        const lesson = courseData.lessons.find((l: Lesson) => l.id === params.id)
        setCurrentLesson(lesson)
      }
    } catch (error) {
      console.error('Error fetching course data:', error)
    } finally {
      setLoading(false)
    }
  }

  const handleQuizSubmit = async (answers: Record<string, string>) => {
    if (!currentLesson?.quiz) return

    try {
      const response = await fetch(`/api/quiz/${currentLesson.quiz.id}/submit`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ answers }),
      })

      if (response.ok) {
        const result = await response.json()
        setQuizSubmitted(true)
        // You could show the results here or redirect to a results page
      }
    } catch (error) {
      console.error('Error submitting quiz:', error)
    }
  }

  const goToLesson = (lessonId: string) => {
    router.push(`/course/${params.slug}/lesson/${lessonId}`)
  }

  const goToNextLesson = () => {
    if (!course || !currentLesson) return
    
    const currentIndex = course.lessons.findIndex(l => l.id === currentLesson.id)
    if (currentIndex < course.lessons.length - 1) {
      goToLesson(course.lessons[currentIndex + 1].id)
    }
  }

  const goToPreviousLesson = () => {
    if (!course || !currentLesson) return
    
    const currentIndex = course.lessons.findIndex(l => l.id === currentLesson.id)
    if (currentIndex > 0) {
      goToLesson(course.lessons[currentIndex - 1].id)
    }
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-[400px]">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>
    )
  }

  if (!course || !currentLesson) {
    return (
      <div className="text-center py-12">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Lesson not found</h2>
        <p className="text-gray-600 mb-4">The lesson you're looking for doesn't exist or you don't have access to it.</p>
        <Button onClick={() => router.push(`/course/${params.slug}`)}>
          Back to Course
        </Button>
      </div>
    )
  }

  const currentIndex = course.lessons.findIndex(l => l.id === currentLesson.id)
  const hasNext = currentIndex < course.lessons.length - 1
  const hasPrevious = currentIndex > 0

  return (
    <div className="space-y-8">
      {/* Lesson Header */}
      <div className="flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <Button variant="outline" onClick={() => router.push(`/course/${params.slug}`)}>
            <ArrowLeft className="h-4 w-4 mr-2" />
            Back to Course
          </Button>
          <div>
            <h1 className="text-2xl font-bold">{currentLesson.title}</h1>
            <p className="text-muted-foreground">
              Lesson {currentIndex + 1} of {course.lessons.length}
            </p>
          </div>
        </div>
        
        <div className="flex items-center space-x-2">
          {hasPrevious && (
            <Button variant="outline" onClick={goToPreviousLesson}>
              <ArrowLeft className="h-4 w-4 mr-2" />
              Previous
            </Button>
          )}
          {hasNext && (
            <Button onClick={goToNextLesson}>
              Next
              <ArrowRight className="h-4 w-4 ml-2" />
            </Button>
          )}
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Main Content */}
        <div className="lg:col-span-2 space-y-6">
          {/* Lesson Player */}
          <LessonPlayer
            title={currentLesson.title}
            description={currentLesson.description}
            youtubeId={currentLesson.youtubeId}
            assetKey={currentLesson.assetKey}
            assetName={`${currentLesson.title} Resource`}
          />

          {/* Quiz */}
          {currentLesson.quiz && !quizSubmitted && (
            <QuizComponent
              quizId={currentLesson.quiz.id}
              title={currentLesson.quiz.title}
              questions={currentLesson.quiz.questions}
              onSubmit={handleQuizSubmit}
            />
          )}

          {/* Quiz Results */}
          {currentLesson.quiz && quizSubmitted && (
            <Card>
              <CardHeader>
                <CardTitle>Quiz Completed!</CardTitle>
              </CardHeader>
              <CardContent>
                <p className="text-muted-foreground mb-4">
                  Great job completing this lesson's quiz. You can now proceed to the next lesson.
                </p>
                {hasNext && (
                  <Button onClick={goToNextLesson}>
                    Continue to Next Lesson
                    <ArrowRight className="h-4 w-4 ml-2" />
                  </Button>
                )}
              </CardContent>
            </Card>
          )}
        </div>

        {/* Sidebar */}
        <div className="space-y-6">
          {/* AI Assistant */}
          <LessonAssistant
            lessonTitle={currentLesson.title}
            lessonContent={currentLesson.description}
          />

          {/* Lesson Navigation */}
          <Card>
            <CardHeader>
              <CardTitle>Course Progress</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-2">
                {course.lessons.map((lesson, index) => (
                  <button
                    key={lesson.id}
                    onClick={() => goToLesson(lesson.id)}
                    className={`w-full text-left p-3 rounded-lg border transition-colors ${
                      lesson.id === currentLesson.id
                        ? 'border-primary bg-primary/5 text-primary'
                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'
                    }`}
                  >
                    <div className="flex items-center justify-between">
                      <span className="font-medium text-sm">{lesson.title}</span>
                      <span className="text-xs text-muted-foreground">
                        {index + 1}
                      </span>
                    </div>
                  </button>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  )
}
