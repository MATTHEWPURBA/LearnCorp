import { NextRequest, NextResponse } from 'next/server'
import { auth } from '@clerk/nextjs/server'
import { db } from '@/lib/db'

export async function GET() {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    let userProfile = await db.userProfile.findUnique({
      where: { clerkUserId: userId },
    })

    if (!userProfile) {
      // Create user profile if it doesn't exist
      userProfile = await db.userProfile.create({
        data: {
          clerkUserId: userId,
          email: '', // Will be updated from Clerk
          role: 'LEARNER',
        },
      })
    }

    return NextResponse.json(userProfile)
  } catch (error) {
    console.error('Error fetching user profile:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}

export async function PUT(request: NextRequest) {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { email, firstName, lastName } = body

    const userProfile = await db.userProfile.upsert({
      where: { clerkUserId: userId },
      update: {
        email,
        firstName,
        lastName,
      },
      create: {
        clerkUserId: userId,
        email,
        firstName,
        lastName,
        role: 'LEARNER',
      },
    })

    return NextResponse.json(userProfile)
  } catch (error) {
    console.error('Error updating user profile:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}
