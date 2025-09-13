import { NextRequest, NextResponse } from 'next/server'
import { auth } from '@clerk/nextjs/server'
import { db } from '@/lib/db'

export async function POST(request: NextRequest) {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { courseId } = body

    if (!courseId) {
      return NextResponse.json({ error: 'Course ID is required' }, { status: 400 })
    }

    // Get user profile
    const userProfile = await db.userProfile.findUnique({
      where: { clerkUserId: userId },
    })

    if (!userProfile) {
      return NextResponse.json({ error: 'User profile not found' }, { status: 404 })
    }

    // Check if already enrolled
    const existingEnrollment = await db.enrollment.findUnique({
      where: {
        userProfileId_courseId: {
          userProfileId: userProfile.id,
          courseId,
        },
      },
    })

    if (existingEnrollment) {
      return NextResponse.json({ error: 'Already enrolled in this course' }, { status: 400 })
    }

    // Create enrollment
    const enrollment = await db.enrollment.create({
      data: {
        userProfileId: userProfile.id,
        courseId,
      },
    })

    return NextResponse.json(enrollment)
  } catch (error) {
    console.error('Error enrolling user:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}

export async function GET() {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    // Get user profile
    const userProfile = await db.userProfile.findUnique({
      where: { clerkUserId: userId },
    })

    if (!userProfile) {
      return NextResponse.json({ error: 'User profile not found' }, { status: 404 })
    }

    // Get enrollments with course details
    const enrollments = await db.enrollment.findMany({
      where: { userProfileId: userProfile.id },
      include: {
        Course: true,
      },
      orderBy: { createdAt: 'desc' },
    })

    return NextResponse.json(enrollments)
  } catch (error) {
    console.error('Error fetching enrollments:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}
