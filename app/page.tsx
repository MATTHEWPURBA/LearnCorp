import Link from 'next/link'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { BookOpen, Users, Video, Brain } from 'lucide-react'

export default function HomePage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      {/* Header */}
      <header className="border-b bg-white/80 backdrop-blur-sm">
        <div className="container mx-auto px-4 py-4 flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <BookOpen className="h-8 w-8 text-blue-600" />
            <h1 className="text-2xl font-bold text-gray-900">LearnCorp</h1>
          </div>
          <div className="flex items-center space-x-4">
            <Link href="/catalog">
              <Button variant="ghost">Browse Courses</Button>
            </Link>
            <Link href="/dashboard">
              <Button>Get Started</Button>
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <main className="container mx-auto px-4 py-16">
        <div className="text-center max-w-4xl mx-auto">
          <h2 className="text-5xl font-bold text-gray-900 mb-6">
            Learn Without Limits
          </h2>
          <p className="text-xl text-gray-600 mb-8">
            Experience the future of online learning with AI-powered tutoring, 
            live interactive classes, and personalized learning paths.
          </p>
          <div className="flex justify-center space-x-4">
            <Link href="/catalog">
              <Button size="lg" className="px-8">
                Explore Courses
              </Button>
            </Link>
            <Link href="/dashboard">
              <Button size="lg" variant="outline" className="px-8">
                Start Learning
              </Button>
            </Link>
          </div>
        </div>

        {/* Features Grid */}
        <div className="mt-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <Card className="text-center">
            <CardHeader>
              <Video className="h-12 w-12 text-blue-600 mx-auto mb-4" />
              <CardTitle>Video Lessons</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                High-quality video content with YouTube integration for seamless streaming.
              </CardDescription>
            </CardContent>
          </Card>

          <Card className="text-center">
            <CardHeader>
              <Users className="h-12 w-12 text-green-600 mx-auto mb-4" />
              <CardTitle>Live Classes</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                Interactive live sessions powered by Jitsi for real-time collaboration.
              </CardDescription>
            </CardContent>
          </Card>

          <Card className="text-center">
            <CardHeader>
              <Brain className="h-12 w-12 text-purple-600 mx-auto mb-4" />
              <CardTitle>AI Tutor</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                Personalized AI assistance that runs directly in your browser.
              </CardDescription>
            </CardContent>
          </Card>

          <Card className="text-center">
            <CardHeader>
              <BookOpen className="h-12 w-12 text-orange-600 mx-auto mb-4" />
              <CardTitle>Progress Tracking</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                Comprehensive progress tracking with quizzes and certificates.
              </CardDescription>
            </CardContent>
          </Card>
        </div>

        {/* Tech Stack */}
        <div className="mt-20 text-center">
          <h3 className="text-2xl font-bold text-gray-900 mb-8">
            Built with Modern Technology
          </h3>
          <div className="flex flex-wrap justify-center items-center space-x-8 text-gray-600">
            <span className="text-sm">Next.js</span>
            <span className="text-sm">•</span>
            <span className="text-sm">Vercel</span>
            <span className="text-sm">•</span>
            <span className="text-sm">Neon Postgres</span>
            <span className="text-sm">•</span>
            <span className="text-sm">Clerk Auth</span>
            <span className="text-sm">•</span>
            <span className="text-sm">Cloudflare R2</span>
            <span className="text-sm">•</span>
            <span className="text-sm">WebLLM</span>
          </div>
        </div>
      </main>
    </div>
  )
}
