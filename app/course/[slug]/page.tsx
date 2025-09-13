import { notFound } from 'next/navigation'
import { auth } from '@clerk/nextjs/server'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Progress } from '@/components/ui/progress'
import Link from 'next/link'
import { BookOpen, Clock, Users, Play, CheckCircle, Lock, ArrowRight } from 'lucide-react'

async function getCourse(slug: string) {
  try {
    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:3000'
    const response = await fetch(`${baseUrl}/api/courses?slug=${slug}`, {
      cache: 'no-store',
    })
    
    if (!response.ok) {
      return null
    }
    
    return response.json()
  } catch (error) {
    console.error('Error fetching course:', error)
    return null
  }
}

async function getEnrollmentStatus(courseId: string) {
  try {
    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:3000'
    const response = await fetch(`${baseUrl}/api/enroll`, {
      cache: 'no-store',
    })
    
    if (!response.ok) {
      return null
    }
    
    const enrollments = await response.json()
    return enrollments.find((e: any) => e.courseId === courseId)
  } catch (error) {
    console.error('Error fetching enrollment:', error)
    return null
  }
}

async function getProgress(courseId: string) {
  try {
    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:3000'
    const response = await fetch(`${baseUrl}/api/progress?course=${courseId}`, {
      cache: 'no-store',
    })
    
    if (!response.ok) {
      return null
    }
    
    return response.json()
  } catch (error) {
    console.error('Error fetching progress:', error)
    return null
  }
}

export default async function CoursePage({ params }: { params: { slug: string } }) {
  const { userId } = auth()
  const course = await getCourse(params.slug)
  
  if (!course) {
    notFound()
  }

  const enrollment = await getEnrollmentStatus(course.id)
  const progress = await getProgress(course.id)

  const isEnrolled = !!enrollment
  const completedLessons = progress?.progress?.completedLessons || 0
  const totalLessons = progress?.progress?.totalLessons || course.lessons.length
  const progressPercentage = totalLessons > 0 ? Math.round((completedLessons / totalLessons) * 100) : 0

  return (
    <div className="space-y-8">
      {/* Course Header */}
      <div className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg p-8">
        <div className="flex items-start justify-between">
          <div className="space-y-4">
            <div className="flex items-center space-x-2">
              <Badge variant="secondary" className="bg-white/20 text-white">
                {course.published ? 'Published' : 'Draft'}
              </Badge>
              <Badge variant="secondary" className="bg-white/20 text-white">
                {course.lessons.length} lessons
              </Badge>
            </div>
            <h1 className="text-4xl font-bold">{course.title}</h1>
            <p className="text-xl text-blue-100 max-w-3xl">
              {course.description}
            </p>
            <div className="flex items-center space-x-6 text-blue-100">
              <div className="flex items-center space-x-2">
                <BookOpen className="h-5 w-5" />
                <span>{course.lessons.length} lessons</span>
              </div>
              <div className="flex items-center space-x-2">
                <Users className="h-5 w-5" />
                <span>{course._count.enrollments} enrolled</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Progress Section (if enrolled) */}
      {isEnrolled && (
        <Card>
          <CardHeader>
            <CardTitle>Your Progress</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="flex justify-between items-center">
                <span className="font-medium">Overall Progress</span>
                <span className="text-2xl font-bold">{progressPercentage}%</span>
              </div>
              <Progress value={progressPercentage} className="h-3" />
              <div className="flex justify-between text-sm text-muted-foreground">
                <span>{completedLessons} of {totalLessons} lessons completed</span>
                <span>{progressPercentage === 100 ? 'Course completed!' : `${totalLessons - completedLessons} lessons remaining`}</span>
              </div>
            </div>
          </CardContent>
        </Card>
      )}

      {/* Enrollment Section */}
      {!isEnrolled && userId && (
        <Card>
          <CardContent className="pt-6">
            <div className="text-center space-y-4">
              <h3 className="text-xl font-semibold">Ready to start learning?</h3>
              <p className="text-muted-foreground">
                Enroll in this course to access all lessons, quizzes, and live classes.
              </p>
              <form action={`/api/enroll`} method="POST">
                <input type="hidden" name="courseId" value={course.id} />
                <Button size="lg" type="submit">
                  Enroll in Course
                  <ArrowRight className="h-4 w-4 ml-2" />
                </Button>
              </form>
            </div>
          </CardContent>
        </Card>
      )}

      {/* Course Content */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Lessons List */}
        <div className="lg:col-span-2">
          <Card>
            <CardHeader>
              <CardTitle>Course Content</CardTitle>
              <CardDescription>
                {isEnrolled ? 'Continue your learning journey' : 'Enroll to access all lessons'}
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {course.lessons.map((lesson: any, index: number) => {
                  const isCompleted = progress?.progress?.completedLessons >= index + 1
                  const canAccess = isEnrolled || index === 0
                  
                  return (
                    <div
                      key={lesson.id}
                      className={`flex items-center space-x-4 p-4 rounded-lg border ${
                        canAccess ? 'hover:bg-gray-50 cursor-pointer' : 'opacity-50'
                      }`}
                    >
                      <div className="flex-shrink-0">
                        {isCompleted ? (
                          <CheckCircle className="h-6 w-6 text-green-600" />
                        ) : canAccess ? (
                          <Play className="h-6 w-6 text-blue-600" />
                        ) : (
                          <Lock className="h-6 w-6 text-gray-400" />
                        )}
                      </div>
                      <div className="flex-1">
                        <h4 className="font-medium">{lesson.title}</h4>
                        {lesson.description && (
                          <p className="text-sm text-muted-foreground mt-1">
                            {lesson.description}
                          </p>
                        )}
                        <div className="flex items-center space-x-2 mt-2">
                          {lesson.youtubeId && (
                            <Badge variant="outline" className="text-xs">
                              Video
                            </Badge>
                          )}
                          {lesson.assetKey && (
                            <Badge variant="outline" className="text-xs">
                              Resource
                            </Badge>
                          )}
                          {lesson.quiz && (
                            <Badge variant="outline" className="text-xs">
                              Quiz
                            </Badge>
                          )}
                        </div>
                      </div>
                      {canAccess && (
                        <Link href={`/course/${course.slug}/lesson/${lesson.id}`}>
                          <Button variant="outline" size="sm">
                            {isCompleted ? 'Review' : 'Start'}
                          </Button>
                        </Link>
                      )}
                    </div>
                  )
                })}
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Sidebar */}
        <div className="space-y-6">
          {/* Quick Actions */}
          <Card>
            <CardHeader>
              <CardTitle>Quick Actions</CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              {isEnrolled && (
                <>
                  <Link href={`/course/${course.slug}/live`} className="block">
                    <Button variant="outline" className="w-full justify-start">
                      <Play className="h-4 w-4 mr-2" />
                      Join Live Class
                    </Button>
                  </Link>
                  {progress?.progress?.isCompleted && (
                    <Button variant="outline" className="w-full justify-start">
                      <CheckCircle className="h-4 w-4 mr-2" />
                      Download Certificate
                    </Button>
                  )}
                </>
              )}
              <Link href="/dashboard" className="block">
                <Button variant="outline" className="w-full justify-start">
                  <BookOpen className="h-4 w-4 mr-2" />
                  Back to Dashboard
                </Button>
              </Link>
            </CardContent>
          </Card>

          {/* Course Stats */}
          <Card>
            <CardHeader>
              <CardTitle>Course Statistics</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="flex justify-between">
                <span className="text-muted-foreground">Lessons</span>
                <span className="font-medium">{course.lessons.length}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-muted-foreground">Enrollments</span>
                <span className="font-medium">{course._count.enrollments}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-muted-foreground">Status</span>
                <Badge variant={course.published ? "default" : "secondary"}>
                  {course.published ? "Published" : "Draft"}
                </Badge>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  )
}
