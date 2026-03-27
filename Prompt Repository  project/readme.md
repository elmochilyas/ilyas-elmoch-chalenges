# Prompt Repository

A PHP-based web application for managing and sharing prompts organized by categories.

## Features

- **User Authentication**: Register, login, and logout functionality
- **Prompt Management**: Create, edit, and delete prompts
- **Categories**: Organize prompts by category (Code, Marketing, DevOps, SQL, Testing)
- **Role-based Access**: User and admin roles with admin dashboard
- **Admin Dashboard**: View top contributors and manage categories

## Tech Stack

- PHP
- MySQL
- HTML/CSS

## Database

The application uses MySQL with the following tables:
- `users` - User accounts with roles (user/admin)
- `categories` - Prompt categories
- `prompts` - User-generated prompts linked to users and categories

Import the database using `PromptRepository.sql`.

## Configuration

Database connection in `db.php`:
- Host: localhost
- Database: PromptRepository
- User: root
- Password: (empty)

## Files

| File | Description |
|------|-------------|
| `index.php` | Main page displaying all prompts |
| `create_prompt.php` | Form to create new prompts |
| `edit.php` | Edit existing prompts |
| `delete.php` | Delete prompts |
| `login.php` | User login |
| `register.php` | User registration |
| `logout.php` | User logout |
| `admin.php` | Admin dashboard |
| `admin_categories.php` | Category management |
| `db.php` | Database connection |
| `style.css` | Styling |

## Setup

1. Create a MySQL database named `PromptRepository`
2. Import `PromptRepository.sql`
3. Configure `db.php` with your database credentials
4. Start a PHP server (e.g., XAMPP)

## Default Categories

- Code
- Marketing
- DevOps
- SQL
- Testing

## Admin Access

Set a user's email to `admin@gmail.com` to grant admin privileges.
