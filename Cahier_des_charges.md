# Specifications - Silent

## 1. Project Overview

### 1.1 Context
Silent is a web application that enables users to organize real-time anecdote sharing sessions. The concept draws inspiration from collaborative board games, transposed into a digital environment.

### 1.2 Objectives 
- Create an interactive and fun social platform
- Encourage experience sharing between users
- Provide a smooth and engaging gaming experience
- Ensure participant anonymity during voting phases

## 2. Core Features

### 2.1 User Management
- Email and username registration
- Email confirmation
- Secure authentication
- Customizable user profiles 
- Role system (user, administrator)
- Activity log export

### 2.2 Room Management
- Public and private room creation
- Participant limit (2-10 players)
- Invitation code system for private rooms
- Room administration interface
- Room statuses (waiting, in progress, completed)

### 2.3 Game System
The game unfolds in several phases:

#### Preparation Phase
- Random theme selection
- Player waiting period (10 seconds)
- Countdown display

#### Game Phase
- Time limit (30 seconds)
- Player anecdote input
- Automatic submission when time expires

#### Voting Phase
- Sequential anecdote presentation
- Binary voting system (positive/negative)
- Cannot vote on own anecdote
- Time limit per anecdote (20 seconds)

#### Results Phase
- Anecdote rankings
- Score display
- Author reveal
- Option to start new game

## 3. Technical Aspects

### 3.1 Architecture
- Symfony Framework
- PostgreSQL database
- Twig and Tailwind CSS user interface
- Real-time communication

### 3.2 Security
- Secure authentication
- CSRF protection
- User data validation
- Permission management (voters)
- Action logging

### 3.3 Performance
- Optimized caching
- Asynchronous requests
- Efficient multiplayer session handling

## 4. User Interface

### 4.1 Design
- Modern responsive interface
- Dark theme with gradients
- Smooth animations
- Visual action feedback
- Progress indicators

### 4.2 Navigation
- Intuitive main menu
- Quick feature access
- Contextual action buttons
- Confirmation/error messages

## 5. Administration

### 5.1 Admin Panel
- User management
- Room moderation
- Theme management
- Log visualization
- Usage statistics

### 5.2 Content Management
- Theme creation/modification
- Game session tracking
- Data maintenance

## 6. Testing and Quality

### 6.1 Automated Testing
- Unit tests
- Functional tests
- Integration tests

### 6.2 Code Quality
- PSR standards compliance
- Code documentation
- Code review
- Continuous integration

## 7. Future Development

### 7.1 Planned Features
- Global ranking system
- Additional game modes
- Advanced room customization
- Media integration
- Mobile application

### 7.2 Technical Improvements
- Performance optimization
- Enhanced scalability
- New integrations
- Public API

## 8. Support and Maintenance

### 8.1 Documentation
- Installation guide
- Technical documentation
- User guide
- Maintenance procedures

### 8.2 Maintenance
- Security updates
- Bug fixes
- Regular backups
- System monitoring