'use client'

import { useState, useRef, useEffect } from 'react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Send, Bot, User, Loader2 } from 'lucide-react'

interface Message {
  role: 'user' | 'assistant' | 'system'
  content: string
}

interface LessonAssistantProps {
  lessonTitle: string
  lessonContent?: string
}

export default function LessonAssistant({ lessonTitle, lessonContent }: LessonAssistantProps) {
  const [messages, setMessages] = useState<Message[]>([
    { 
      role: 'system', 
      content: `You are a helpful tutor for the lesson "${lessonTitle}". ${lessonContent ? `The lesson content is: ${lessonContent}` : ''} Answer questions about this lesson and help students understand the material.` 
    }
  ])
  const [input, setInput] = useState('')
  const [isLoading, setIsLoading] = useState(false)
  const [isInitializing, setIsInitializing] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const engineRef = useRef<any>(null)
  const messagesEndRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    initializeWebLLM()
  }, [])

  useEffect(() => {
    scrollToBottom()
  }, [messages])

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' })
  }

  const initializeWebLLM = async () => {
    try {
      // Dynamically import WebLLM to avoid SSR issues
      const { CreateMLCEngine } = await import('@mlc-ai/web-llm')
      
      engineRef.current = await CreateMLCEngine("Llama-3.1-8B-Instruct", {
        initProgressCallback: (progress: any) => {
          console.log("Loading model:", progress)
        },
      })
      
      setIsInitializing(false)
    } catch (err) {
      console.error('Failed to initialize WebLLM:', err)
      setError('Failed to initialize AI assistant. Please ensure you have a modern browser with WebGPU support.')
      setIsInitializing(false)
    }
  }

  const handleSendMessage = async () => {
    if (!input.trim() || isLoading || !engineRef.current) return

    const userMessage: Message = { role: 'user', content: input }
    const newMessages = [...messages, userMessage]
    setMessages(newMessages)
    setInput('')
    setIsLoading(true)

    try {
      const chunks = await engineRef.current.chat.completions.create({
        messages: newMessages,
        stream: true,
      })

      let assistantReply = ''
      const assistantMessage: Message = { role: 'assistant', content: '' }
      setMessages([...newMessages, assistantMessage])

      for await (const chunk of chunks) {
        const content = chunk.choices[0]?.delta.content || ''
        assistantReply += content
        
        setMessages(prev => {
          const updated = [...prev]
          updated[updated.length - 1] = { role: 'assistant', content: assistantReply }
          return updated
        })
      }
    } catch (err) {
      console.error('Error generating response:', err)
      setMessages(prev => [...prev, { 
        role: 'assistant', 
        content: 'Sorry, I encountered an error while generating a response. Please try again.' 
      }])
    } finally {
      setIsLoading(false)
    }
  }

  const handleKeyPress = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault()
      handleSendMessage()
    }
  }

  if (isInitializing) {
    return (
      <Card className="w-full h-96">
        <CardHeader>
          <CardTitle className="flex items-center space-x-2">
            <Bot className="h-5 w-5" />
            <span>AI Assistant</span>
          </CardTitle>
        </CardHeader>
        <CardContent className="flex items-center justify-center h-full">
          <div className="text-center space-y-4">
            <Loader2 className="h-8 w-8 animate-spin mx-auto" />
            <p className="text-muted-foreground">Loading AI assistant...</p>
            <p className="text-xs text-muted-foreground">
              This may take a moment on first load as we download the model.
            </p>
          </div>
        </CardContent>
      </Card>
    )
  }

  if (error) {
    return (
      <Card className="w-full h-96">
        <CardHeader>
          <CardTitle className="flex items-center space-x-2">
            <Bot className="h-5 w-5" />
            <span>AI Assistant</span>
          </CardTitle>
        </CardHeader>
        <CardContent className="flex items-center justify-center h-full">
          <div className="text-center space-y-4">
            <p className="text-red-600">{error}</p>
            <Button onClick={() => window.location.reload()}>
              Retry
            </Button>
          </div>
        </CardContent>
      </Card>
    )
  }

  return (
    <Card className="w-full h-96 flex flex-col">
      <CardHeader className="pb-3">
        <CardTitle className="flex items-center space-x-2">
          <Bot className="h-5 w-5" />
          <span>AI Assistant</span>
        </CardTitle>
      </CardHeader>
      <CardContent className="flex-1 flex flex-col space-y-4">
        {/* Messages */}
        <div className="flex-1 overflow-y-auto space-y-4 pr-2">
          {messages.filter(m => m.role !== 'system').map((message, index) => (
            <div
              key={index}
              className={`flex space-x-2 ${message.role === 'user' ? 'justify-end' : 'justify-start'}`}
            >
              <div className={`flex space-x-2 max-w-[80%] ${message.role === 'user' ? 'flex-row-reverse space-x-reverse' : ''}`}>
                <div className={`w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 ${
                  message.role === 'user' 
                    ? 'bg-blue-600 text-white' 
                    : 'bg-gray-200 text-gray-600'
                }`}>
                  {message.role === 'user' ? <User className="h-3 w-3" /> : <Bot className="h-3 w-3" />}
                </div>
                <div className={`rounded-lg px-3 py-2 text-sm ${
                  message.role === 'user'
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-100 text-gray-900'
                }`}>
                  {message.content}
                </div>
              </div>
            </div>
          ))}
          {isLoading && (
            <div className="flex space-x-2">
              <div className="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center">
                <Bot className="h-3 w-3" />
              </div>
              <div className="bg-gray-100 rounded-lg px-3 py-2">
                <Loader2 className="h-4 w-4 animate-spin" />
              </div>
            </div>
          )}
          <div ref={messagesEndRef} />
        </div>

        {/* Input */}
        <div className="flex space-x-2">
          <Input
            value={input}
            onChange={(e) => setInput(e.target.value)}
            onKeyPress={handleKeyPress}
            placeholder="Ask a question about this lesson..."
            disabled={isLoading}
            className="flex-1"
          />
          <Button 
            onClick={handleSendMessage} 
            disabled={!input.trim() || isLoading}
            size="icon"
          >
            <Send className="h-4 w-4" />
          </Button>
        </div>
      </CardContent>
    </Card>
  )
}
