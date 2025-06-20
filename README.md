<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Application Concept Introduction

The **Employee Management System** is a web-based application developed as part of a training program. It is designed to streamline and automate various administrative tasks related to employee management, including:

- Generation of work certificates
- Generation of salary certificates
- Leave request management
- Leave decision tracking

This application centralizes and simplifies human resources operations, offering an efficient solution to manage personnel-related processes. By automating routine tasks, it improves productivity and ensures smooth day-to-day HR management.

## Authentication

This application includes two main roles:

- Admin
The administrator is responsible for managing all system data and features, including:
- Employee records
- Leave types and statuses
- Certificate generation
- Approving or rejecting leave requests
- Managing users and permissions

**Credentials**:
- Username: `admin`  
- Password: `admin`

- Employee
Employees can:
- Submit leave requests
- View the status of their requests
- Download work or salary certificates
- Edit their profile

**Credentials**:
- Username: `employee`  
- Password: `employee`

## Setting Up the Project

**Requirements**:
- PHP >= 8.1.1
- Composer >= 2.2.4
- MySQL
- Git

**Installation Steps**:

1. Open your terminal or command prompt.
2. Navigate to your development folder:
   ```bash
   cd C:\xampp\htdocs

<hr>
- Clone the project:

bash
Copy
Edit

git clone https://github.com/your-username/employee-management-system.git

<hr>
- Go into the project directory:

bash
Copy
Edit
cd employee-management-system

<hr>
- Install PHP dependencies:

bash
Copy
Edit
composer install

<hr>
## #Copy and configure the environment file:

bash
Copy
Edit
`cp .envexample .env` 

<hr>
- Update your .env file with the correct database credentials.

<hr>
- Generate the application key:

bash
Copy
Edit
`php artisan key:generate` 

<hr>
- Run migrations and seed the database:

bash
Copy
Edit
`php artisan migrate --seed` 
Start the application:

bash
Copy
Edit
php artisan serve

You can now access the application at http://localhost:8000.

<hr>
- Screenshots


<hr>
License
This application is open-source and provided under the MIT license.
All assets used are free and for demonstration purposes only.
