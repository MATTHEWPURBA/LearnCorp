import { NextRequest, NextResponse } from 'next/server'
import { auth } from '@clerk/nextjs/server'
import { db } from '@/lib/db'

export async function POST(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const quizId = params.id
    const body = await request.json()
    const { answers } = body

    if (!answers) {
      return NextResponse.json({ error: 'Answers are required' }, { status: 400 })
    }

    // Get user profile
    const userProfile = await db.userProfile.findUnique({
      where: { clerkUserId: userId },
    })

    if (!userProfile) {
      return NextResponse.json({ error: 'User profile not found' }, { status: 404 })
    }

    // Get quiz with questions
    const quiz = await db.quiz.findUnique({
      where: { id: quizId },
      include: {
        questions: true,
      },
    })

    if (!quiz) {
      return NextResponse.json({ error: 'Quiz not found' }, { status: 404 })
    }

    // Calculate score
    let correctAnswers = 0
    const totalQuestions = quiz.questions.length

    quiz.questions.forEach(question => {
      if (answers[question.id] === question.correct) {
        correctAnswers++
      }
    })

    const score = correctAnswers

    // Create quiz attempt
    const quizAttempt = await db.quizAttempt.create({
      data: {
        userProfileId: userProfile.id,
        quizId,
        score,
        totalQuestions,
        answers,
      },
    })

    return NextResponse.json({
      quizAttempt,
      score,
      totalQuestions,
      percentage: Math.round((score / totalQuestions) * 100),
    })
  } catch (error) {
    console.error('Error submitting quiz:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}
