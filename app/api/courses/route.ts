import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url)
    const published = searchParams.get('published')
    const slug = searchParams.get('slug')

    if (slug) {
      // Get single course by slug
      const course = await db.course.findUnique({
        where: { slug },
        include: {
          lessons: {
            include: {
              quiz: {
                include: {
                  questions: true,
                },
              },
            },
            orderBy: { order: 'asc' },
          },
          _count: {
            select: {
              enrollments: true,
            },
          },
        },
      })

      if (!course) {
        return NextResponse.json({ error: 'Course not found' }, { status: 404 })
      }

      return NextResponse.json(course)
    } else {
      // Get all courses
      const courses = await db.course.findMany({
        where: published === 'true' ? { published: true } : undefined,
        include: {
          _count: {
            select: {
              lessons: true,
              enrollments: true,
            },
          },
        },
        orderBy: { createdAt: 'desc' },
      })

      return NextResponse.json(courses)
    }
  } catch (error) {
    console.error('Error fetching courses:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}
