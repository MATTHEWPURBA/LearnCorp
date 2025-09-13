import { auth } from '@clerk/nextjs/server'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Progress } from '@/components/ui/progress'
import Link from 'next/link'
import { Button } from '@/components/ui/button'
import { BookOpen, Clock, Trophy, ArrowRight } from 'lucide-react'

async function getDashboardData() {
  try {
    const baseUrl = process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:3000'
    
    const [enrollmentsResponse, progressResponse] = await Promise.all([
      fetch(`${baseUrl}/api/enroll`, { cache: 'no-store' }),
      fetch(`${baseUrl}/api/progress`, { cache: 'no-store' }),
    ])

    const enrollments = enrollmentsResponse.ok ? await enrollmentsResponse.json() : []
    const progress = progressResponse.ok ? await progressResponse.json() : { overallProgress: [] }

    return { enrollments, progress: progress.overallProgress || [] }
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
    return { enrollments: [], progress: [] }
  }
}

export default async function DashboardPage() {
  const { userId } = auth()
  const { enrollments, progress } = await getDashboardData()

  const completedCourses = progress.filter((p: any) => p.isCompleted).length
  const totalCourses = enrollments.length
  const overallProgress = totalCourses > 0 ? Math.round(progress.reduce((acc: number, p: any) => acc + p.percentage, 0) / totalCourses) : 0

  return (
    <div className="space-y-8">
      {/* Welcome Section */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 mb-2">
          Welcome to your Dashboard
        </h1>
        <p className="text-gray-600">
          Track your learning progress and continue your educational journey.
        </p>
      </div>

      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Enrolled Courses</CardTitle>
            <BookOpen className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{totalCourses}</div>
            <p className="text-xs text-muted-foreground">
              {totalCourses === 0 ? 'No courses yet' : 'Active enrollments'}
            </p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Completed Courses</CardTitle>
            <Trophy className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{completedCourses}</div>
            <p className="text-xs text-muted-foreground">
              {completedCourses === 0 ? 'Keep learning!' : 'Great job!'}
            </p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Overall Progress</CardTitle>
            <Clock className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{overallProgress}%</div>
            <p className="text-xs text-muted-foreground">
              Across all courses
            </p>
          </CardContent>
        </Card>
      </div>

      {/* Quick Actions */}
      <div className="flex flex-col sm:flex-row gap-4">
        <Link href="/catalog" className="flex-1">
          <Button className="w-full" variant="outline">
            <BookOpen className="h-4 w-4 mr-2" />
            Browse All Courses
          </Button>
        </Link>
      </div>

      {/* My Courses */}
      <div>
        <h2 className="text-2xl font-bold text-gray-900 mb-6">My Courses</h2>
        
        {enrollments.length > 0 ? (
          <div className="space-y-4">
            {enrollments.map((enrollment: any) => {
              const courseProgress = progress.find((p: any) => p.courseId === enrollment.courseId)
              const progressPercentage = courseProgress?.percentage || 0
              
              return (
                <Card key={enrollment.id} className="hover:shadow-md transition-shadow">
                  <CardHeader>
                    <div className="flex items-start justify-between">
                      <div className="space-y-1">
                        <CardTitle className="text-lg">{enrollment.Course.title}</CardTitle>
                        <CardDescription>
                          {enrollment.Course.description}
                        </CardDescription>
                      </div>
                      <Badge variant={courseProgress?.isCompleted ? "default" : "secondary"}>
                        {courseProgress?.isCompleted ? "Completed" : "In Progress"}
                      </Badge>
                    </div>
                  </CardHeader>
                  <CardContent className="space-y-4">
                    <div className="space-y-2">
                      <div className="flex justify-between text-sm">
                        <span>Progress</span>
                        <span>{progressPercentage}%</span>
                      </div>
                      <Progress value={progressPercentage} className="h-2" />
                      <div className="flex justify-between text-xs text-muted-foreground">
                        <span>
                          {courseProgress?.completedLessons || 0} of {courseProgress?.totalLessons || 0} lessons completed
                        </span>
                      </div>
                    </div>
                    
                    <div className="flex gap-2">
                      <Link href={`/course/${enrollment.Course.slug}`} className="flex-1">
                        <Button className="w-full">
                          {courseProgress?.isCompleted ? "Review Course" : "Continue Learning"}
                          <ArrowRight className="h-4 w-4 ml-2" />
                        </Button>
                      </Link>
                      {enrollment.Course.slug && (
                        <Link href={`/course/${enrollment.Course.slug}/live`}>
                          <Button variant="outline">
                            Live Class
                          </Button>
                        </Link>
                      )}
                    </div>
                  </CardContent>
                </Card>
              )
            })}
          </div>
        ) : (
          <Card>
            <CardContent className="text-center py-12">
              <BookOpen className="h-12 w-12 text-gray-400 mx-auto mb-4" />
              <h3 className="text-lg font-semibold text-gray-900 mb-2">
                No courses enrolled yet
              </h3>
              <p className="text-gray-600 mb-4">
                Start your learning journey by enrolling in a course.
              </p>
              <Link href="/catalog">
                <Button>
                  Browse Courses
                </Button>
              </Link>
            </CardContent>
          </Card>
        )}
      </div>
    </div>
  )
}
