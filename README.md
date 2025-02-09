# ExamIn - College Hall Ticket Management System

A comprehensive web application for managing college hall tickets, student registrations, and exam schedules. This system provides separate interfaces for administrators and students, handling everything from user authentication to exam management.

## Features

### User Management
- **Multi-role Authentication System**
  - Student and Admin roles
  - Secure login system with role-based access control
  - Password encryption for security
  - Session management for authenticated users

### Student Management
- **Registration System**
  - Student profile creation with personal details
  - Photo upload functionality
  - Automatic username generation based on admission number
  - Email notification system for credentials

### Course & Academic Management
- **Course Management**
  - Course registration and tracking
  - Semester-wise organization
  - Subject allocation per course
  - Batch management system

### Exam Management
- **Hall Ticket System**
  - Automated hall ticket generation
  - Exam schedule management
  - Subject-wise exam timing allocation
  - Duration and date management for exams

### Form Validations
- **Client-side Validation**
  - Real-time input validation using JavaScript
  - Form field requirements checking
  - Data format validation (email, phone numbers, etc.)

- **Server-side Validation**
  - PHP validation for all form submissions
  - File upload validation for images
  - Data sanitization to prevent SQL injection
  - Input length and format verification

## Technology Stack

### Frontend
- HTML5
- CSS3
- JavaScript
- Modern UI with responsive design
- Font Awesome icons for enhanced UI

### Backend
- PHP 7+
- MySQL Database
- Apache Server
- Session Management

### External Libraries & APIs
- **PHPMailer (v6.9)**
  - Used for sending automated emails
  - SMTP integration for reliable email delivery
  - HTML email template support

### Database Structure
- **Key Tables**
  - users (user authentication and roles)
  - students (student information)
  - courses (course details)
  - subjects (subject information)
  - exams (exam schedules)
  - exam_subjects (subject-wise exam details)

## Installation & Setup

1. **Prerequisites**
   - PHP 7+ 
   - MySQL
   - Apache Server
   - Composer (for PHPMailer installation)

2. **Database Setup**
   ```sql
   CREATE DATABASE examin;
   ```
   - Import the provided SQL file from the database folder

3. **Configuration**
   - Configure database connection in `config.php`
   - Set up email credentials in the PHPMailer configuration
   - Adjust file upload paths in relevant PHP files

4. **Dependencies Installation**
   ```bash
   composer require phpmailer/phpmailer
   ```

## Directory Structure 

ExamIn/
├── Login-Signup/
│ ├── Login/
│ └── Request/
├── teacher-page/
│ ├── php/
│ └── images/
├── student-page/
│ └── ...
└── database/
```

## Security Features
- Password encryption
- Session management
- SQL injection prevention
- XSS attack prevention
- File upload validation
- Input sanitization

## Form Validations
### Student Registration
- Required fields validation
- Email format verification
- Phone number format checking
- Image file type and size validation
- Admission number uniqueness check

### Login System
- Username verification
- Password validation
- Role-based access control
- Session timeout handling

## API Integration
### PHPMailer Implementation
- SMTP configuration
- HTML email templates
- Error handling
- Queue management for bulk emails

## Future Enhancements
- PDF generation for hall tickets
- Online exam module
- Payment integration
- Mobile application
- Advanced reporting system

## Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

## License
This project is currently not under any license.

## Support
For support, email [ajintsdev@gmail.com]

## Authors
- [Ajin TS]

## Acknowledgments
- PHPMailer team for the email functionality
- Font Awesome for the icons
- All contributors who have helped with the project