import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  console.log('🌱 Seeding database...')

  // Create sample course
  const course = await prisma.course.create({
    data: {
      title: "Introduction to Web Development",
      slug: "intro-web-dev",
      description: "Learn the fundamentals of modern web development with HTML, CSS, and JavaScript. Perfect for beginners who want to start their coding journey.",
      published: true,
      lessons: {
        create: [
          {
            title: "HTML Fundamentals",
            description: "Learn the building blocks of web pages with HTML",
            youtubeId: "pQN-pnXPaVg", // Replace with actual video ID
            order: 1,
            quiz: {
              create: {
                title: "HTML Basics Quiz",
                questions: {
                  create: [
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
                    }
                  ]
                }
              }
            }
          },
          {
            title: "CSS Styling",
            description: "Make your web pages beautiful with CSS",
            youtubeId: "1Rs2ND1ryYc", // Replace with actual video ID
            order: 2,
            quiz: {
              create: {
                title: "CSS Basics Quiz",
                questions: {
                  create: [
                    {
                      prompt: "What does CSS stand for?",
                      a: "Computer Style Sheets",
                      b: "Creative Style Sheets",
                      c: "Cascading Style Sheets",
                      d: "Colorful Style Sheets",
                      correct: "c"
                    }
                  ]
                }
              }
            }
          },
          {
            title: "JavaScript Basics",
            description: "Add interactivity to your web pages with JavaScript",
            youtubeId: "W6NZfCO5SIk", // Replace with actual video ID
            order: 3
          }
        ]
      }
    }
  })

  // Create another course
  const course2 = await prisma.course.create({
    data: {
      title: "React Development",
      slug: "react-dev",
      description: "Build dynamic user interfaces with React. Learn component-based architecture and modern development practices.",
      published: true,
      lessons: {
        create: [
          {
            title: "Components and JSX",
            description: "Learn the basics of React components and JSX syntax",
            order: 1
          },
          {
            title: "State and Props",
            description: "Manage component state and pass data with props",
            order: 2
          },
          {
            title: "Hooks",
            description: "Use React hooks for state management and side effects",
            order: 3
          }
        ]
      }
    }
  })

  console.log('✅ Sample courses created:')
  console.log(`- ${course.title} (${course.slug})`)
  console.log(`- ${course2.title} (${course2.slug})`)
  console.log('')
  console.log('🎉 Database seeded successfully!')
  console.log('')
  console.log('You can now:')
  console.log('1. Start the development server: npm run dev')
  console.log('2. Visit http://localhost:3000')
  console.log('3. Sign up for an account')
  console.log('4. Browse and enroll in the sample courses')
}

main()
  .catch((e) => {
    console.error('❌ Error seeding database:', e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
