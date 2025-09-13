import { auth } from '@clerk/nextjs/server'
import { redirect } from 'next/navigation'
import { UserButton } from '@clerk/nextjs'
import Link from 'next/link'
import { Button } from '@/components/ui/button'
import { BookOpen, Home, GraduationCap } from 'lucide-react'

export default async function ProtectedLayout({
  children,
}: {
  children: React.ReactNode
}) {
  const { userId } = auth()

  if (!userId) {
    redirect('/sign-in')
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation */}
      <nav className="bg-white border-b">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            <Link href="/dashboard" className="flex items-center space-x-2">
              <BookOpen className="h-6 w-6 text-blue-600" />
              <span className="text-xl font-bold text-gray-900">LearnCorp</span>
            </Link>
            
            <div className="flex items-center space-x-4">
              <Link href="/catalog">
                <Button variant="ghost" size="sm">
                  <Home className="h-4 w-4 mr-2" />
                  Browse
                </Button>
              </Link>
              <Link href="/dashboard">
                <Button variant="ghost" size="sm">
                  <GraduationCap className="h-4 w-4 mr-2" />
                  My Courses
                </Button>
              </Link>
              <UserButton afterSignOutUrl="/" />
            </div>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-8">
        {children}
      </main>
    </div>
  )
}
