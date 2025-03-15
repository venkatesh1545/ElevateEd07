const skillsData = {
    'web-development': {
        id: 'web-development',
        name: 'Web Development Roadmap',
        progress: 60,
        description: 'Your journey to becoming a Full Stack Developer',
        videos: [
            {
                id: 'v1',
                title: 'HTML & CSS Fundamentals',
                duration: '3 hours',
                completed: true,
                url: 'https://www.youtube.com/embed/qz0aGYrrlhU'
            },
            {
                id: 'v2',
                title: 'JavaScript Essentials',
                duration: '5 hours',
                completed: true,
                url: 'https://www.youtube.com/embed/W6NZfCO5SIk'
            },
            {
                id: 'v3',
                title: 'React.js Crash Course',
                duration: '4 hours',
                completed: false,
                url: 'https://www.youtube.com/embed/w7ejDZ8SWv8'
            }
        ],
        articles: [
            {
                id: 'a1',
                title: 'Modern Web Development',
                duration: '20 min read',
                completed: true,
                content: 'Web development has evolved significantly...'
            },
            {
                id: 'a2',
                title: 'Frontend Best Practices',
                duration: '15 min read',
                completed: false,
                content: 'Learn about the latest frontend development practices...'
            }
        ],
        certifications: [
            {
                id: 'c1',
                title: 'Full Stack Web Developer',
                provider: 'FreeCodeCamp',
                duration: '300 hours',
                completed: false,
                details: 'Comprehensive certification covering HTML, CSS, JavaScript, and more.'
            }
        ]
    },
    'machine-learning': {
        id: 'machine-learning',
        name: 'Machine Learning Roadmap',
        progress: 40,
        description: 'Your journey to becoming a Machine Learning expert',
        videos: [
            {
                id: 'v1',
                title: 'Python for Machine Learning',
                duration: '4 hours',
                completed: true,
                url: 'https://www.youtube.com/embed/WFr2WgN9_xE'
            },
            {
                id: 'v2',
                title: 'Neural Networks Fundamentals',
                duration: '8 hours',
                completed: false,
                url: 'https://www.youtube.com/embed/aircAruvnKk'
            }
        ],
        articles: [
            {
                id: 'a1',
                title: 'Introduction to ML',
                duration: '25 min read',
                completed: true,
                content: 'Machine Learning is transforming industries...'
            }
        ],
        certifications: [
            {
                id: 'c1',
                title: 'TensorFlow Developer Certificate',
                provider: 'Google',
                duration: '6 months',
                completed: false,
                details: 'Official Google TensorFlow certification for ML engineers.'
            }
        ]
    }
};