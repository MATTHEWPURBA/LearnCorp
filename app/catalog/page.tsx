import Link from 'next/link'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { BookOpen, Clock, Users, ArrowRight } from 'lucide-react'

async function getCourses() {
  try {
    const response = await fetch(`${process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:3000'}/api/courses?published=true`, {
      cache: 'no-store',
    })
    
    if (!response.ok) {
      throw new Error('Failed to fetch courses')
    }
    
    return response.json()
  } catch (error) {
    console.error('Error fetching courses:', error)
    return []
  }
}

export default async function CatalogPage() {
  const courses = await getCourses()

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      {/* Header */}
      <header className="border-b bg-white/80 backdrop-blur-sm">
        <div className="container mx-auto px-4 py-4 flex items-center justify-between">
          <Link href="/" className="flex items-center space-x-2">
            <BookOpen className="h-8 w-8 text-blue-600" />
            <h1 className="text-2xl font-bold text-gray-900">LearnCorp</h1>
          </Link>
          <div className="flex items-center space-x-4">
            <Link href="/dashboard">
              <Button>My Dashboard</Button>
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <main className="container mx-auto px-4 py-16">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">
            Explore Our Courses
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Discover a wide range of courses designed to help you learn and grow. 
            From beginner to advanced levels, we have something for everyone.
          </p>
        </div>

        {/* Courses Grid */}
        {courses.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {courses.map((course: any) => (
              <Card key={course.id} className="group hover:shadow-lg transition-shadow">
                <CardHeader>
                  {course.thumbnail && (
                    <div className="aspect-video bg-gray-200 rounded-lg mb-4 overflow-hidden">
                      <img
                        src={course.thumbnail}
                        alt={course.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                      />
                    </div>
                  )}
                  <CardTitle className="text-xl">{course.title}</CardTitle>
                  <CardDescription className="line-clamp-3">
                    {course.description}
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="flex items-center justify-between mb-4">
                    <div className="flex items-center space-x-4 text-sm text-muted-foreground">
                      <div className="flex items-center space-x-1">
                        <BookOpen className="h-4 w-4" />
                        <span>{course._count.lessons} lessons</span>
                      </div>
                      <div className="flex items-center space-x-1">
                        <Users className="h-4 w-4" />
                        <span>{course._count.enrollments} enrolled</span>
                      </div>
                    </div>
                    <Badge variant="secondary">
                      Published
                    </Badge>
                  </div>
                  <Link href={`/course/${course.slug}`}>
                    <Button className="w-full group-hover:bg-primary/90">
                      View Course
                      <ArrowRight className="h-4 w-4 ml-2" />
                    </Button>
                  </Link>
                </CardContent>
              </Card>
            ))}
          </div>
        ) : (
          <div className="text-center py-16">
            <BookOpen className="h-16 w-16 text-gray-400 mx-auto mb-4" />
            <h3 className="text-xl font-semibold text-gray-900 mb-2">
              No courses available yet
            </h3>
            <p className="text-gray-600">
              Check back soon for exciting new courses!
            </p>
          </div>
        )}
      </main>
    </div>
  )
}
