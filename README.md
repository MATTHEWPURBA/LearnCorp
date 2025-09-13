# LearnCorp LMS - Modern Learning Management System

A full-stack learning management system built with Next.js, featuring AI-powered tutoring, live interactive classes, and comprehensive progress tracking.

## ✨ Features

- **🎥 Video Lessons**: YouTube integration for seamless video streaming
- **👥 Live Classes**: Interactive live sessions powered by Jitsi
- **🤖 AI Tutor**: Browser-based AI assistant using WebLLM
- **📊 Progress Tracking**: Comprehensive analytics and completion tracking
- **📝 Interactive Quizzes**: Multi-choice quizzes with instant feedback
- **📜 Certificates**: Automatic certificate generation upon course completion
- **🔐 Authentication**: Secure user management with Clerk
- **☁️ File Storage**: Cloudflare R2 for course materials and certificates

## 🛠️ Tech Stack

- **Frontend**: Next.js 14 (App Router), TypeScript, Tailwind CSS
- **Authentication**: Clerk
- **Database**: Neon Postgres with Prisma ORM
- **File Storage**: Cloudflare R2 (S3-compatible)
- **Video Streaming**: YouTube IFrame API
- **Live Classes**: Jitsi Meet
- **AI Chatbot**: WebLLM (runs in browser)
- **Deployment**: Vercel
- **UI Components**: Custom components with Radix UI primitives

## 🚀 Quick Start

### Prerequisites

- Node.js 18+ 
- npm or yarn
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd LearnCorp
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Set up environment variables**
   ```bash
   cp .env.example .env.local
   ```
   
   Fill in your environment variables (see [Environment Setup](#environment-setup))

4. **Set up the database**
   ```bash
   npx prisma migrate dev
   npx prisma generate
   ```

5. **Start the development server**
   ```bash
   npm run dev
   ```

6. **Open your browser**
   Navigate to [http://localhost:3000](http://localhost:3000)

## 🔧 Environment Setup

Create a `.env.local` file with the following variables:

```env
# Database
DATABASE_URL="postgresql://USER:PASS@ep-xxxx-pooler.SOME.aws.neon.tech/neondb?sslmode=require&channel_binding=require"

# Clerk Auth
NEXT_PUBLIC_CLERK_PUBLISHABLE_KEY=pk_test_...
CLERK_SECRET_KEY=sk_test_...

# Cloudflare R2
R2_ACCESS_KEY_ID=...
R2_SECRET_ACCESS_KEY=...
R2_ENDPOINT=https://<ACCOUNT_ID>.r2.cloudflarestorage.com
R2_BUCKET=lms-assets
R2_PUBLIC_URL=https://pub-<ACCOUNT_ID>.r2.dev
```

### Getting API Keys

1. **Neon Database**: Sign up at [neon.tech](https://neon.tech) and create a project
2. **Clerk Auth**: Sign up at [clerk.com](https://clerk.com) and create an application
3. **Cloudflare R2**: Sign up at [cloudflare.com](https://cloudflare.com) and enable R2 storage

## 📁 Project Structure

```
├── app/                    # Next.js App Router
│   ├── (protected)/       # Protected routes
│   ├── api/               # API routes
│   ├── catalog/           # Public course catalog
│   ├── course/            # Course pages
│   └── sign-in/           # Authentication pages
├── components/            # React components
│   ├── ui/               # Base UI components
│   ├── LessonPlayer.tsx   # Video player component
│   ├── LessonAssistant.tsx # AI chatbot component
│   ├── JitsiRoom.tsx     # Live class component
│   └── QuizComponent.tsx  # Quiz component
├── lib/                   # Utility functions
│   ├── db.ts             # Prisma client
│   ├── r2.ts             # Cloudflare R2 client
│   └── utils.ts          # Helper functions
├── prisma/               # Database schema
│   └── schema.prisma     # Prisma schema
└── public/               # Static assets
```

## 🎯 Core Features Explained

### Video Lessons
- YouTube video integration with custom player
- Support for course materials (PDFs, documents)
- Progress tracking per lesson

### Live Classes
- Jitsi Meet integration for video conferencing
- Room-based sessions per course
- Participant management and controls

### AI Tutor
- WebLLM-powered chatbot running in browser
- Context-aware responses based on lesson content
- No server costs - runs entirely client-side

### Progress Tracking
- Course completion percentages
- Quiz scores and attempts
- Certificate generation upon completion

## 🔒 Authentication & Authorization

- **Clerk Integration**: Handles user registration, login, and profile management
- **Role-based Access**: Admin, Instructor, and Learner roles
- **Protected Routes**: Middleware protection for authenticated pages
- **User Profiles**: Automatic profile creation on first login

## 💾 Database Schema

The application uses a comprehensive database schema with the following main entities:

- **UserProfile**: User information and roles
- **Course**: Course metadata and settings
- **Lesson**: Individual lessons within courses
- **Enrollment**: User course enrollments
- **Quiz**: Quiz questions and attempts
- **LiveSession**: Live class scheduling

## 🚀 Deployment

See [DEPLOYMENT.md](./DEPLOYMENT.md) for detailed deployment instructions to Vercel.

## 🆓 Free Tier Usage

This application is designed to work within free tier limits:

- **Vercel**: 100GB bandwidth, 100 builds/month
- **Neon**: 3GB database, auto-sleep when inactive
- **Clerk**: 10,000 monthly active users
- **Cloudflare R2**: 10GB storage, unlimited egress

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## 🙏 Acknowledgments

- [Next.js](https://nextjs.org/) for the React framework
- [Clerk](https://clerk.com/) for authentication
- [Neon](https://neon.tech/) for serverless Postgres
- [Cloudflare](https://cloudflare.com/) for R2 storage
- [Jitsi](https://jitsi.org/) for video conferencing
- [WebLLM](https://webllm.mlc.ai/) for browser-based AI
- [Prisma](https://prisma.io/) for database ORM
- [Tailwind CSS](https://tailwindcss.com/) for styling

---

Built with ❤️ for modern education technology.