# School Management System

A simple, lightweight School Management System built with PHP 8, MySQL, and Bootstrap 5.

## Features
- **Authentication**: Role-based login (Admin, Teacher, Student, Parent).
- **Student Management**: Enroll students, manage profiles.
- **Dashboard**: Overview of school stats.
- **MVC Architecture**: Custom lightweight framework.

## Setup Instructions

### Local Development (XAMPP/Windows)

1. **Clone the repo** to `htdocs/school_system`.
2. **Database Setup**:
   - Create a database named `school_system`.
   - Update credentials in `config/database.php` if different from default (`root`/``).
   - Run migrations:
     ```bash
     # If 'php' is in your PATH:
     php database/migrate.php
     
     # If using XAMPP standard install:
     c:\xampp\php\php.exe database/migrate.php
     ```
3. **Run the Application**:
   - Serve via XAMPP Apache, access at: `http://localhost/school_system/public/`
   - OR use PHP built-in server:
     ```bash
     cd public
     # Standard
     php -S localhost:8000
     # XAMPP Full Path
     c:\xampp\php\php.exe -S localhost:8000
     ```

### Deployment (Render/Vercel)

- **Render**:
    - Deploy as a **Web Service** with PHP environment.
    - Set environment variables for database connection.
    - Update `config/database.php` to use `getenv()` for production credentials.

## Git & GitHub Setup
1. Initialize local repo: `git init`
2. Add files: `git add .`
3. Commit: `git commit -m "Initial commit"`
4. Add Remote repository (Get URL from GitHub): `git remote add origin https://github.com/YOUR_USERNAME/school_system.git`
5. Push: `git push -u origin main`

## Credentials
- **Admin**: `admin@school.com` / `admin123`
- **Student**: `student@school.com` / `student123`

## Directory Structure
- `/public`: Web root (CSS, JS, index.php)
- `/src`: PHP Source (Controllers, Models, Core)
- `/views`: HTML Templates
- `/config`: Configuration
