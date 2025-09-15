# Laravel LMS with Jetstream Livewire

A comprehensive Learning Management System built with Laravel, Jetstream Livewire, featuring YouTube video integration, Jitsi live sessions, WebLLM AI chat, and Cloudflare R2 storage.

## Features

- **🎓 Course Management**: Create and manage courses with lessons, quizzes, and certificates
- **📹 Video Learning**: YouTube video integration for video-on-demand content
- **🎥 Live Sessions**: Jitsi Meet integration for live video conferencing
- **🤖 AI Assistant**: WebLLM-powered in-browser AI chat for learning support
- **📊 Quiz System**: Auto-grading quizzes with detailed feedback
- **📜 Certificates**: Automatic certificate generation upon course completion
- **☁️ Cloud Storage**: Cloudflare R2 integration for file storage
- **👥 User Management**: Jetstream authentication with teams support
- **📱 Responsive Design**: Modern, mobile-friendly interface

## Tech Stack

- **Backend**: Laravel 12.x with Jetstream Livewire
- **Database**: PostgreSQL (Neon)
- **Storage**: Cloudflare R2
- **Video**: YouTube API, Jitsi Meet
- **AI**: WebLLM (in-browser)
- **Frontend**: Tailwind CSS, Alpine.js
- **Deployment**: Render (Docker)

## Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL (or Neon account)

### Installation

1. **Clone and install dependencies**:
   ```bash
   git clone <repository-url>
   cd lms-laravel
   composer install
   npm install
   ```

2. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database configuration**:
   Update `.env` with your database credentials:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=your-db-host
   DB_PORT=5432
   DB_DATABASE=your-db-name
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   ```

4. **Cloudflare R2 setup**:
   ```env
   FILESYSTEM_DISK=r2
   R2_KEY=your-r2-key
   R2_SECRET=your-r2-secret
   R2_BUCKET=lms-assets
   R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
   R2_PUBLIC_URL=https://pub-your-account-id.r2.dev
   ```

5. **Run migrations and seed data**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**:
   ```bash
   npm run build
   ```

7. **Start development server**:
   ```bash
   php artisan serve
   ```

## Configuration

### Cloudflare R2 Setup

1. Create a Cloudflare R2 bucket
2. Generate API tokens with R2 permissions
3. Update environment variables with your credentials
4. The system will automatically use R2 for file storage

### YouTube Integration

- Add YouTube video IDs to lessons
- Videos will be embedded with proper security settings
- No API key required for basic embedding

### Jitsi Live Sessions

- Uses Jitsi Meet's free service
- Automatic room name generation
- Instructor controls for session management

### WebLLM AI Chat

- Runs entirely in the browser
- No server costs or API keys required
- Graceful fallback for unsupported browsers

## Deployment on Render

1. **Connect your repository** to Render
2. **Create a PostgreSQL database** (free tier available)
3. **Set environment variables**:
   - All database credentials
   - R2 storage credentials
   - `APP_KEY` (generate with `php artisan key:generate --show`)
4. **Deploy**: Render will automatically build and deploy using the Dockerfile

### Environment Variables for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-generated-key
DB_CONNECTION=pgsql
DB_HOST=your-render-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-username
DB_PASSWORD=your-password
FILESYSTEM_DISK=r2
R2_KEY=your-r2-key
R2_SECRET=your-r2-secret
R2_BUCKET=lms-assets
R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://pub-your-account-id.r2.dev
```

## Usage

### For Students

1. **Register/Login**: Create an account or sign in
2. **Browse Courses**: View available courses in the catalog
3. **Enroll**: Click "Enroll Now" on any course
4. **Learn**: Watch videos, take quizzes, interact with AI assistant
5. **Track Progress**: Monitor your learning progress
6. **Join Live Sessions**: Participate in scheduled live classes
7. **Earn Certificates**: Receive certificates upon course completion

### For Instructors

1. **Create Courses**: Add course content, lessons, and quizzes
2. **Schedule Live Sessions**: Set up live video sessions
3. **Monitor Progress**: Track student enrollment and progress
4. **Manage Content**: Upload materials to R2 storage

## API Endpoints

- `GET /courses` - Course catalog
- `GET /courses/{course}` - Course details
- `GET /lessons/{lesson}` - Lesson player
- `GET /live-sessions/{session}` - Live session

## File Structure

```
app/
├── Livewire/           # Livewire components
├── Models/            # Eloquent models
└── ...
database/
├── migrations/        # Database migrations
└── seeders/          # Database seeders
resources/
├── views/
│   └── livewire/     # Livewire component views
└── ...
docker/               # Docker configuration
├── nginx.conf
└── supervisord.conf
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support and questions, please open an issue in the repository.

---

**Built with ❤️ using Laravel, Jetstream, and modern web technologies.**