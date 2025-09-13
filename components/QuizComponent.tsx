'use client'

import { useState } from 'react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { CheckCircle, XCircle, RotateCcw } from 'lucide-react'

interface QuizQuestion {
  id: string
  prompt: string
  a: string
  b: string
  c: string
  d: string
  correct: string
}

interface QuizComponentProps {
  quizId: string
  title?: string
  questions: QuizQuestion[]
  onSubmit: (answers: Record<string, string>) => Promise<void>
}

export default function QuizComponent({ quizId, title, questions, onSubmit }: QuizComponentProps) {
  const [answers, setAnswers] = useState<Record<string, string>>({})
  const [currentQuestion, setCurrentQuestion] = useState(0)
  const [isSubmitted, setIsSubmitted] = useState(false)
  const [score, setScore] = useState<number | null>(null)
  const [showResults, setShowResults] = useState(false)

  const handleAnswerSelect = (questionId: string, answer: string) => {
    setAnswers(prev => ({
      ...prev,
      [questionId]: answer
    }))
  }

  const handleSubmit = async () => {
    setIsSubmitted(true)
    await onSubmit(answers)
  }

  const handleShowResults = (finalScore: number) => {
    setScore(finalScore)
    setShowResults(true)
  }

  const resetQuiz = () => {
    setAnswers({})
    setCurrentQuestion(0)
    setIsSubmitted(false)
    setScore(null)
    setShowResults(false)
  }

  const getScoreColor = (score: number, total: number) => {
    const percentage = (score / total) * 100
    if (percentage >= 80) return 'bg-green-100 text-green-800'
    if (percentage >= 60) return 'bg-yellow-100 text-yellow-800'
    return 'bg-red-100 text-red-800'
  }

  if (showResults && score !== null) {
    return (
      <Card className="w-full">
        <CardHeader>
          <CardTitle className="flex items-center justify-between">
            <span>Quiz Results</span>
            <Button onClick={resetQuiz} variant="outline" size="sm">
              <RotateCcw className="h-4 w-4 mr-2" />
              Retake Quiz
            </Button>
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="text-center">
            <div className={`inline-flex items-center px-4 py-2 rounded-full text-2xl font-bold ${getScoreColor(score, questions.length)}`}>
              {score}/{questions.length}
            </div>
            <p className="mt-2 text-muted-foreground">
              You scored {Math.round((score / questions.length) * 100)}%
            </p>
          </div>

          <div className="space-y-4">
            {questions.map((question, index) => {
              const userAnswer = answers[question.id]
              const isCorrect = userAnswer === question.correct
              
              return (
                <div key={question.id} className="border rounded-lg p-4">
                  <div className="flex items-start space-x-2 mb-3">
                    {isCorrect ? (
                      <CheckCircle className="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" />
                    ) : (
                      <XCircle className="h-5 w-5 text-red-600 mt-0.5 flex-shrink-0" />
                    )}
                    <div className="flex-1">
                      <p className="font-medium">Question {index + 1}</p>
                      <p className="text-sm text-muted-foreground mt-1">{question.prompt}</p>
                    </div>
                  </div>
                  
                  <div className="grid grid-cols-2 gap-2 text-sm">
                    {['a', 'b', 'c', 'd'].map(option => {
                      const isUserAnswer = userAnswer === option
                      const isCorrectAnswer = question.correct === option
                      
                      let className = "p-2 rounded border "
                      if (isCorrectAnswer) {
                        className += "bg-green-50 border-green-200 text-green-800"
                      } else if (isUserAnswer && !isCorrect) {
                        className += "bg-red-50 border-red-200 text-red-800"
                      } else {
                        className += "bg-gray-50 border-gray-200"
                      }
                      
                      return (
                        <div key={option} className={className}>
                          <span className="font-medium">{option.toUpperCase()}:</span> {question[option as keyof QuizQuestion]}
                        </div>
                      )
                    })}
                  </div>
                </div>
              )
            })}
          </div>
        </CardContent>
      </Card>
    )
  }

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle className="flex items-center justify-between">
          <span>{title || 'Quiz'}</span>
          <Badge variant="outline">
            Question {currentQuestion + 1} of {questions.length}
          </Badge>
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-6">
        {questions.map((question, index) => (
          <div 
            key={question.id} 
            className={`space-y-4 ${index === currentQuestion ? 'block' : 'hidden'}`}
          >
            <div>
              <h3 className="text-lg font-medium mb-2">{question.prompt}</h3>
              <div className="grid grid-cols-1 gap-3">
                {['a', 'b', 'c', 'd'].map(option => (
                  <label
                    key={option}
                    className={`flex items-center space-x-3 p-3 rounded-lg border cursor-pointer transition-colors ${
                      answers[question.id] === option
                        ? 'border-primary bg-primary/5'
                        : 'border-gray-200 hover:border-gray-300'
                    }`}
                  >
                    <input
                      type="radio"
                      name={question.id}
                      value={option}
                      checked={answers[question.id] === option}
                      onChange={(e) => handleAnswerSelect(question.id, e.target.value)}
                      className="sr-only"
                    />
                    <div className={`w-4 h-4 rounded-full border-2 flex items-center justify-center ${
                      answers[question.id] === option
                        ? 'border-primary'
                        : 'border-gray-300'
                    }`}>
                      {answers[question.id] === option && (
                        <div className="w-2 h-2 rounded-full bg-primary"></div>
                      )}
                    </div>
                    <span className="flex-1">{question[option as keyof QuizQuestion]}</span>
                  </label>
                ))}
              </div>
            </div>

            <div className="flex justify-between">
              <Button
                variant="outline"
                onClick={() => setCurrentQuestion(Math.max(0, currentQuestion - 1))}
                disabled={currentQuestion === 0}
              >
                Previous
              </Button>
              
              {currentQuestion === questions.length - 1 ? (
                <Button
                  onClick={handleSubmit}
                  disabled={!answers[question.id] || isSubmitted}
                >
                  {isSubmitted ? 'Submitting...' : 'Submit Quiz'}
                </Button>
              ) : (
                <Button
                  onClick={() => setCurrentQuestion(Math.min(questions.length - 1, currentQuestion + 1))}
                  disabled={!answers[question.id]}
                >
                  Next
                </Button>
              )}
            </div>
          </div>
        ))}

        {/* Progress Bar */}
        <div className="w-full bg-gray-200 rounded-full h-2">
          <div 
            className="bg-primary h-2 rounded-full transition-all duration-300"
            style={{ width: `${((currentQuestion + 1) / questions.length) * 100}%` }}
          ></div>
        </div>
      </CardContent>
    </Card>
  )
}
