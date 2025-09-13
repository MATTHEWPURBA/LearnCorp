# 🚀 Quick Setup Guide

Follow these steps to get your LMS showcase running locally in under 10 minutes!

## Prerequisites

- Node.js 18+ installed
- Git installed
- A code editor (VS Code recommended)

## Step 1: Install Dependencies

```bash
npm install
```

## Step 2: Set Up Services (5 minutes)

### 2.1 Neon Database (Free)
1. Go to [neon.tech](https://neon.tech) and sign up
2. Create a new project
3. Copy the connection string (use the pooled one ending with `-pooler.neon.tech`)
4. Add `?sslmode=require&channel_binding=require` to the end

### 2.2 Clerk Authentication (Free)
1. Go to [clerk.com](https://clerk.com) and sign up
2. Create a new application
3. Copy your Publishable Key and Secret Key

### 2.3 Cloudflare R2 (Free)
1. Go to [cloudflare.com](https://cloudflare.com) and sign up
2. Navigate to R2 Object Storage
3. Create a bucket named "lms-assets"
4. Generate API token with read/write permissions
5. Copy your endpoint URL and credentials

## Step 3: Environment Setup

Create a `.env.local` file:

```env
# Database
DATABASE_URL="postgresql://USER:PASS@ep-xxxx-pooler.neon.tech/neondb?sslmode=require&channel_binding=require"

# Clerk Auth
NEXT_PUBLIC_CLERK_PUBLISHABLE_KEY=pk_test_...
CLERK_SECRET_KEY=sk_test_...

# Cloudflare R2
R2_ACCESS_KEY_ID=your_access_key
R2_SECRET_ACCESS_KEY=your_secret_key
R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
R2_BUCKET=lms-assets
R2_PUBLIC_URL=https://pub-your-account-id.r2.dev
```

## Step 4: Database Setup

```bash
# Generate Prisma client
npx prisma generate

# Run database migrations
npx prisma migrate dev --name init

# Seed with sample data
npm run db:seed
```

## Step 5: Start Development Server

```bash
npm run dev
```

Visit [http://localhost:3000](http://localhost:3000) 🎉

## Step 6: Test the Features

1. **Sign up** for a new account
2. **Browse courses** in the catalog
3. **Enroll** in a course
4. **Watch videos** and take quizzes
5. **Join live classes** (Jitsi integration)
6. **Chat with AI tutor** (WebLLM)

## 🎯 What You'll See

- **Homepage**: Beautiful landing page with feature overview
- **Course Catalog**: Browse available courses
- **Dashboard**: Track your learning progress
- **Lesson Player**: YouTube videos with AI assistant
- **Live Classes**: Interactive video conferencing
- **Quizzes**: Interactive assessments with instant feedback

## 🚀 Deploy to Production

See [DEPLOYMENT.md](./DEPLOYMENT.md) for detailed Vercel deployment instructions.

## ❓ Need Help?

- Check the [README.md](./README.md) for detailed documentation
- Review [DEPLOYMENT.md](./DEPLOYMENT.md) for production setup
- Check browser console for any errors
- Ensure all environment variables are set correctly

## 🎉 You're Ready!

Your modern LMS showcase is now running locally with:
- ✅ Next.js 14 with App Router
- ✅ TypeScript for type safety
- ✅ Clerk authentication
- ✅ Neon Postgres database
- ✅ Cloudflare R2 file storage
- ✅ YouTube video integration
- ✅ Jitsi live classes
- ✅ WebLLM AI chatbot
- ✅ Tailwind CSS styling

Happy learning! 🚀
