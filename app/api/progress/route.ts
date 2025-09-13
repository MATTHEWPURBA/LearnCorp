import { NextRequest, NextResponse } from 'next/server'
import { auth } from '@clerk/nextjs/server'
import { db } from '@/lib/db'

export async function GET(request: NextRequest) {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const { searchParams } = new URL(request.url)
    const courseId = searchParams.get('course')

    // Get user profile
    const userProfile = await db.userProfile.findUnique({
      where: { clerkUserId: userId },
    })

    if (!userProfile) {
      return NextResponse.json({ error: 'User profile not found' }, { status: 404 })
    }

    if (courseId) {
      // Get progress for specific course
      const enrollment = await db.enrollment.findUnique({
        where: {
          userProfileId_courseId: {
            userProfileId: userProfile.id,
            courseId,
          },
        },
        include: {
          Course: {
            include: {
              lessons: {
                include: {
                  quiz: {
                    include: {
                      attempts: {
                        where: { userProfileId: userProfile.id },
                        orderBy: { createdAt: 'desc' },
                        take: 1,
                      },
                    },
                  },
                },
                orderBy: { order: 'asc' },
              },
            },
          },
        },
      })

      if (!enrollment) {
        return NextResponse.json({ error: 'Not enrolled in this course' }, { status: 404 })
      }

      // Calculate progress
      const totalLessons = enrollment.Course.lessons.length
      const completedLessons = enrollment.Course.lessons.filter(lesson => {
        if (lesson.quiz) {
          return lesson.quiz.attempts.length > 0 && lesson.quiz.attempts[0].score >= (lesson.quiz.attempts[0].totalQuestions * 0.7) // 70% threshold
        }
        return false // For now, only quiz completion counts
      }).length

      const progressPercentage = totalLessons > 0 ? Math.round((completedLessons / totalLessons) * 100) : 0

      return NextResponse.json({
        enrollment,
        progress: {
          completedLessons,
          totalLessons,
          percentage: progressPercentage,
          isCompleted: completedLessons === totalLessons && totalLessons > 0,
        },
      })
    } else {
      // Get overall progress across all courses
      const enrollments = await db.enrollment.findMany({
        where: { userProfileId: userProfile.id },
        include: {
          Course: {
            include: {
              lessons: {
                include: {
                  quiz: {
                    include: {
                      attempts: {
                        where: { userProfileId: userProfile.id },
                        orderBy: { createdAt: 'desc' },
                        take: 1,
                      },
                    },
                  },
                },
                orderBy: { order: 'asc' },
              },
            },
          },
        },
      })

      const overallProgress = enrollments.map(enrollment => {
        const totalLessons = enrollment.Course.lessons.length
        const completedLessons = enrollment.Course.lessons.filter(lesson => {
          if (lesson.quiz) {
            return lesson.quiz.attempts.length > 0 && lesson.quiz.attempts[0].score >= (lesson.quiz.attempts[0].totalQuestions * 0.7)
          }
          return false
        }).length

        const progressPercentage = totalLessons > 0 ? Math.round((completedLessons / totalLessons) * 100) : 0

        return {
          courseId: enrollment.courseId,
          courseTitle: enrollment.Course.title,
          completedLessons,
          totalLessons,
          percentage: progressPercentage,
          isCompleted: completedLessons === totalLessons && totalLessons > 0,
        }
      })

      return NextResponse.json({ overallProgress })
    }
  } catch (error) {
    console.error('Error fetching progress:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}
