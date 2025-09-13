# LearnCorp LMS - Deployment Guide

This guide will walk you through deploying your LMS showcase to production using the free-tier stack.

## 🚀 Quick Deploy Checklist

### 1. Set Up Neon Postgres Database

1. **Create Neon Account**: Go to [neon.tech](https://neon.tech) and sign up
2. **Create Project**: Create a new project called "lms-showcase"
3. **Get Connection String**: Copy the pooled connection string (ends with `-pooler.neon.tech`)
4. **Update Connection**: Add `?sslmode=require&channel_binding=require` to the end

### 2. Set Up Clerk Authentication

1. **Create Clerk Account**: Go to [clerk.com](https://clerk.com) and sign up
2. **Create Application**: Create a new application
3. **Get Keys**: Copy your Publishable Key and Secret Key
4. **Configure Domains**: Add your production domain in Clerk settings

### 3. Set Up Cloudflare R2

1. **Create Cloudflare Account**: Go to [cloudflare.com](https://cloudflare.com) and sign up
2. **Enable R2**: Go to R2 Object Storage in your dashboard
3. **Create Bucket**: Create a bucket named "lms-assets"
4. **Generate API Token**: Create R2 token with read/write permissions
5. **Get Endpoint**: Copy your R2 endpoint URL

### 4. Deploy to Vercel

1. **Push to GitHub**: Push your code to a GitHub repository
2. **Connect Vercel**: Go to [vercel.com](https://vercel.com) and import your repository
3. **Set Environment Variables**:
   ```
   DATABASE_URL=postgresql://...
   NEXT_PUBLIC_CLERK_PUBLISHABLE_KEY=pk_live_...
   CLERK_SECRET_KEY=sk_live_...
   R2_ACCESS_KEY_ID=your_r2_access_key
   R2_SECRET_ACCESS_KEY=your_r2_secret_key
   R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
   R2_BUCKET=lms-assets
   R2_PUBLIC_URL=https://pub-your-account-id.r2.dev
   ```
4. **Deploy**: Click deploy and wait for the build to complete

### 5. Run Database Migrations

After deployment, run the database migrations:

```bash
# In your local terminal
npx prisma migrate deploy
npx prisma generate
```

Or use Vercel's CLI:
```bash
vercel env pull .env.local
npx prisma migrate deploy
```

## 📝 Environment Variables Reference

### Required Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `DATABASE_URL` | Neon Postgres connection string | `postgresql://user:pass@ep-xxx-pooler.neon.tech/neondb?sslmode=require` |
| `NEXT_PUBLIC_CLERK_PUBLISHABLE_KEY` | Clerk publishable key | `pk_live_...` |
| `CLERK_SECRET_KEY` | Clerk secret key | `sk_live_...` |
| `R2_ACCESS_KEY_ID` | Cloudflare R2 access key | `your_access_key` |
| `R2_SECRET_ACCESS_KEY` | Cloudflare R2 secret key | `your_secret_key` |
| `R2_ENDPOINT` | R2 S3-compatible endpoint | `https://account-id.r2.cloudflarestorage.com` |
| `R2_BUCKET` | R2 bucket name | `lms-assets` |
| `R2_PUBLIC_URL` | R2 public URL for assets | `https://pub-account-id.r2.dev` |

## 🔧 Post-Deployment Setup

### 1. Seed Sample Data (Optional)

Create a simple script to add sample courses:

```typescript
// scripts/seed.ts
import { db } from '../lib/db'

async function seed() {
  const course = await db.course.create({
    data: {
      title: "Introduction to Web Development",
      slug: "intro-web-dev",
      description: "Learn the basics of HTML, CSS, and JavaScript",
      published: true,
      lessons: {
        create: [
          {
            title: "HTML Basics",
            description: "Learn the fundamentals of HTML",
            youtubeId: "example-video-id",
            order: 1,
          },
          {
            title: "CSS Styling",
            description: "Style your web pages with CSS",
            order: 2,
          },
        ]
      }
    }
  })
  
  console.log('Sample course created:', course)
}

seed().catch(console.error)
```

### 2. Test All Features

- [ ] User registration and login
- [ ] Course browsing and enrollment
- [ ] Video playback (YouTube)
- [ ] Live classes (Jitsi)
- [ ] AI chatbot (WebLLM)
- [ ] Quiz functionality
- [ ] File downloads (R2)

## 🆓 Free Tier Limits

### Vercel Hobby
- **Builds**: 100 builds/month
- **Bandwidth**: 100GB/month
- **Function Executions**: 100GB-hours/month

### Neon Free
- **Database Size**: 3GB
- **Compute**: 0.25 vCPU, 1GB RAM
- **Connections**: 100 concurrent connections

### Clerk Free
- **Monthly Active Users**: 10,000
- **Organizations**: 1
- **Custom Domains**: 1

### Cloudflare R2 Free
- **Storage**: 10GB
- **Class A Operations**: 1M/month
- **Class B Operations**: 10M/month
- **Egress**: Free (unlimited)

## 🔍 Troubleshooting

### Common Issues

1. **Database Connection Errors**
   - Ensure you're using the pooled connection string
   - Check that SSL mode is set to require

2. **Clerk Authentication Issues**
   - Verify domain is added in Clerk dashboard
   - Check that environment variables are set correctly

3. **R2 Upload Errors**
   - Verify API credentials are correct
   - Check bucket name and endpoint URL

4. **WebLLM Not Loading**
   - Ensure browser supports WebGPU
   - Check browser console for errors

### Getting Help

- Check the browser console for client-side errors
- Check Vercel function logs for server-side errors
- Review the individual service documentation:
  - [Neon Docs](https://neon.com/docs)
  - [Clerk Docs](https://clerk.com/docs)
  - [Cloudflare R2 Docs](https://developers.cloudflare.com/r2/)
  - [Vercel Docs](https://vercel.com/docs)

## 🎉 You're Live!

Your LMS showcase is now deployed and ready to use! Share the URL with others to demonstrate the full-stack learning management system with modern technologies.

Remember to monitor your usage to stay within free tier limits, and consider upgrading individual services as your needs grow.
