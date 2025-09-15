import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  console.log('🌱 Seeding database...')

  // Clear existing data
  await prisma.quizAttempt.deleteMany()
  await prisma.quizQuestion.deleteMany()
  await prisma.quiz.deleteMany()
  await prisma.enrollment.deleteMany()
  await prisma.lesson.deleteMany()
  await prisma.course.deleteMany()

  // Create comprehensive course catalog
  const courses = [
    {
      title: "Introduction to Web Development",
      slug: "intro-web-dev",
      description: "Learn the fundamentals of modern web development with HTML, CSS, and JavaScript. Perfect for beginners who want to start their coding journey.",
      thumbnail: "https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "HTML Fundamentals",
          description: "Learn the building blocks of web pages with HTML",
          youtubeId: "pQN-pnXPaVg",
          order: 1,
          quiz: {
            title: "HTML Basics Quiz",
            questions: [
              {
                prompt: "What does HTML stand for?",
                a: "HyperText Markup Language",
                b: "High Tech Modern Language",
                c: "Home Tool Markup Language",
                d: "Hyperlink and Text Markup Language",
                correct: "a"
              },
              {
                prompt: "Which tag is used to create a paragraph?",
                a: "<para>",
                b: "<p>",
                c: "<paragraph>",
                d: "<text>",
                correct: "b"
              },
              {
                prompt: "What is the correct HTML for creating a hyperlink?",
                a: "<a href='url'>Link</a>",
                b: "<link>url</link>",
                c: "<a>url</a>",
                d: "<url>Link</url>",
                correct: "a"
              }
            ]
          }
        },
        {
          title: "CSS Styling",
          description: "Make your web pages beautiful with CSS",
          youtubeId: "1Rs2ND1ryYc",
          order: 2,
          quiz: {
            title: "CSS Basics Quiz",
            questions: [
              {
                prompt: "What does CSS stand for?",
                a: "Computer Style Sheets",
                b: "Creative Style Sheets",
                c: "Cascading Style Sheets",
                d: "Colorful Style Sheets",
                correct: "c"
              },
              {
                prompt: "Which property is used to change the text color?",
                a: "text-color",
                b: "color",
                c: "font-color",
                d: "text-style",
                correct: "b"
              }
            ]
          }
        },
        {
          title: "JavaScript Basics",
          description: "Add interactivity to your web pages with JavaScript",
          youtubeId: "W6NZfCO5SIk",
          order: 3,
          quiz: {
            title: "JavaScript Basics Quiz",
            questions: [
              {
                prompt: "Which keyword is used to declare a variable in JavaScript?",
                a: "var",
                b: "let",
                c: "const",
                d: "All of the above",
                correct: "d"
              }
            ]
          }
        }
      ]
    },
    {
      title: "React Development Masterclass",
      slug: "react-masterclass",
      description: "Build dynamic user interfaces with React. Learn component-based architecture, hooks, state management, and modern development practices.",
      thumbnail: "https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "Components and JSX",
          description: "Learn the basics of React components and JSX syntax",
          order: 1,
          quiz: {
            title: "React Components Quiz",
            questions: [
              {
                prompt: "What is JSX?",
                a: "A JavaScript extension",
                b: "A syntax extension for JavaScript",
                c: "A separate programming language",
                d: "A CSS framework",
                correct: "b"
              }
            ]
          }
        },
        {
          title: "State and Props",
          description: "Manage component state and pass data with props",
          order: 2
        },
        {
          title: "Hooks Deep Dive",
          description: "Use React hooks for state management and side effects",
          order: 3
        },
        {
          title: "Context API",
          description: "Manage global state with React Context",
          order: 4
        }
      ]
    },
    {
      title: "Python for Data Science",
      slug: "python-data-science",
      description: "Master Python programming for data analysis, visualization, and machine learning. Learn pandas, numpy, matplotlib, and scikit-learn.",
      thumbnail: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "Python Basics",
          description: "Introduction to Python programming fundamentals",
          order: 1,
          quiz: {
            title: "Python Basics Quiz",
            questions: [
              {
                prompt: "Which of the following is not a Python data type?",
                a: "list",
                b: "tuple",
                c: "array",
                d: "dictionary",
                correct: "c"
              }
            ]
          }
        },
        {
          title: "NumPy Arrays",
          description: "Working with numerical data using NumPy",
          order: 2
        },
        {
          title: "Pandas DataFrames",
          description: "Data manipulation and analysis with pandas",
          order: 3
        },
        {
          title: "Data Visualization",
          description: "Creating charts and graphs with matplotlib and seaborn",
          order: 4
        },
        {
          title: "Machine Learning Basics",
          description: "Introduction to scikit-learn and ML algorithms",
          order: 5
        }
      ]
    },
    {
      title: "Full-Stack JavaScript",
      slug: "fullstack-javascript",
      description: "Build complete web applications using JavaScript on both frontend and backend. Learn Node.js, Express, MongoDB, and modern deployment.",
      thumbnail: "https://images.unsplash.com/photo-1627398242454-45a1465c2479?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "Node.js Fundamentals",
          description: "Server-side JavaScript with Node.js",
          youtubeId: "fBNz5xF-Kx4",
          order: 1
        },
        {
          title: "Express.js Framework",
          description: "Building RESTful APIs with Express",
          youtubeId: "L72fhGm1tfE",
          order: 2
        },
        {
          title: "MongoDB Database",
          description: "Working with NoSQL databases",
          youtubeId: "-0X8mr6Q8QQ",
          order: 3
        },
        {
          title: "Authentication & Security",
          description: "Implementing user authentication and security best practices",
          youtubeId: "sakQbeRjgwg",
          order: 4
        },
        {
          title: "Deployment & DevOps",
          description: "Deploying applications to production",
          youtubeId: "eB0nUzAI7M8",
          order: 5
        }
      ]
    },
    {
      title: "UI/UX Design Principles",
      slug: "ui-ux-design",
      description: "Learn the fundamentals of user interface and user experience design. Master design thinking, wireframing, prototyping, and user research.",
      thumbnail: "https://images.unsplash.com/photo-1558655146-d09347e92766?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "Design Thinking Process",
          description: "Understanding users and their needs",
          youtubeId: "pXtN4y3O35M",
          order: 1
        },
        {
          title: "Wireframing & Prototyping",
          description: "Creating low and high-fidelity prototypes",
          youtubeId: "9wBcS0tFrew",
          order: 2
        },
        {
          title: "Visual Design Principles",
          description: "Color, typography, and layout fundamentals",
          youtubeId: "ZbrzdMcgHhQ",
          order: 3
        },
        {
          title: "User Research Methods",
          description: "Conducting effective user research",
          youtubeId: "QckIzHC99Xc",
          order: 4
        }
      ]
    },
    {
      title: "Cloud Computing with AWS",
      slug: "aws-cloud-computing",
      description: "Master Amazon Web Services cloud platform. Learn EC2, S3, Lambda, RDS, and other essential AWS services for scalable applications.",
      thumbnail: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "AWS Fundamentals",
          description: "Introduction to cloud computing and AWS services",
          youtubeId: "SOTamWNgDKc",
          order: 1
        },
        {
          title: "EC2 Virtual Machines",
          description: "Creating and managing virtual servers",
          youtubeId: "iHX-JU3r3jY",
          order: 2
        },
        {
          title: "S3 Storage Solutions",
          description: "Object storage and file management",
          youtubeId: "e2r7qF4u4lQ",
          order: 3
        },
        {
          title: "Lambda Serverless Functions",
          description: "Building serverless applications",
          youtubeId: "eOBq__h4OJ4",
          order: 4
        },
        {
          title: "Database Services",
          description: "RDS, DynamoDB, and other database options",
          youtubeId: "8yqJ2i6a3Ak",
          order: 5
        }
      ]
    },
    {
      title: "Mobile App Development",
      slug: "mobile-app-development",
      description: "Build cross-platform mobile applications using React Native. Learn to create iOS and Android apps with a single codebase.",
      thumbnail: "https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "React Native Setup",
          description: "Setting up your development environment",
          youtubeId: "0-S5a0eXPoc",
          order: 1
        },
        {
          title: "Components & Navigation",
          description: "Building screens and navigation flows",
          youtubeId: "qSRrxpdMpVc",
          order: 2
        },
        {
          title: "State Management",
          description: "Managing app state with Redux and Context",
          youtubeId: "9boMnm5X9ak",
          order: 3
        },
        {
          title: "API Integration",
          description: "Connecting to backend services",
          youtubeId: "6WQrQ0n4dNI",
          order: 4
        },
        {
          title: "App Store Deployment",
          description: "Publishing to iOS and Google Play stores",
          youtubeId: "H4Jt7uUz4vE",
          order: 5
        }
      ]
    },
    {
      title: "Cybersecurity Fundamentals",
      slug: "cybersecurity-fundamentals",
      description: "Learn essential cybersecurity concepts, threat detection, network security, and ethical hacking techniques to protect digital assets.",
      thumbnail: "https://images.unsplash.com/photo-1563206767-5b18f218e8de?w=500&h=300&fit=crop",
      published: true,
      lessons: [
        {
          title: "Security Fundamentals",
          description: "Core concepts of information security",
          youtubeId: "inWWhr5tnEA",
          order: 1
        },
        {
          title: "Network Security",
          description: "Protecting network infrastructure",
          youtubeId: "qiQR5rTSshw",
          order: 2
        },
        {
          title: "Threat Detection",
          description: "Identifying and responding to security threats",
          youtubeId: "Dk-ZqQ-bfy4",
          order: 3
        },
        {
          title: "Ethical Hacking",
          description: "Penetration testing and vulnerability assessment",
          youtubeId: "3Kq1MIfTWCE",
          order: 4
        }
      ]
    }
  ]

  // Create all courses
  for (const courseData of courses) {
    const { lessons, ...courseInfo } = courseData
    
    const course = await prisma.course.create({
      data: {
        ...courseInfo,
        lessons: {
          create: lessons.map(lesson => ({
            title: lesson.title,
            description: lesson.description,
            youtubeId: (lesson as any).youtubeId || null,
            order: lesson.order,
            quiz: lesson.quiz ? {
              create: {
                title: lesson.quiz.title,
                questions: {
                  create: lesson.quiz.questions
                }
              }
            } : undefined
          }))
        }
      }
    })
    
    console.log(`✅ Created course: ${course.title}`)
  }

  console.log('')
  console.log('🎉 Database seeded successfully!')
  console.log('')
  console.log('You can now:')
  console.log('1. Start the development server: npm run dev')
  console.log('2. Visit http://localhost:3000')
  console.log('3. Sign up for an account')
  console.log('4. Browse and enroll in the courses')
}

main()
  .catch((e) => {
    console.error('❌ Error seeding database:', e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
