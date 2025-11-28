# Book Sharing Community

## Project Overview
This is a Laravel-based web application developed for the Server Side Systems (CW02) module. The application allows users to register, log in, and share books with the community. It demonstrates CRUD operations, user authentication, and file handling within an MVC architecture.

## Features
- **User Authentication:** Secure registration and login system (Bcrypt hashing).
- **Book Management:** Users can Create, Read, Update, and Delete (CRUD) book entries.
- **File Uploads:** Integration with AWS storage for uploading book cover images.
- **MVC Architecture:** Follows standard Model-View-Controller design patterns.
- **Security:** Implements CSRF protection and input validation.

## Tech Stack
- **Framework:** Laravel 10 (PHP)
- **Database:** MySQL
- **Server:** AWS Ubuntu (LAMP Stack)
- **Frontend:** Blade Templates, Tailwind CSS/Bootstrap

## Installation
1. Clone the repository.
2. Run `composer install`.
3. Configure `.env` file with database credentials.
4. Run migrations: `php artisan migrate`.
5. Serve the application: `php artisan serve`.

## Author
* **Name:** DUHENG
* **Student ID:** [B01819825]
