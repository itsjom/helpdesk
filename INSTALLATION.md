# Installation & Setup Guide

Follow these steps to set up the Helpdesk on your local machine.

## Prerequisites
Before you begin, ensure you have the following installed:
- **PHP 8.2+**
- **Composer**
- **Node.js & NPM**
- **MySQL** (Recommended: [Laragon](https://laragon.org/) for Windows users)

---

## 1. Clone the Repository
Clone the project to your local web server directory (e.g., `C:\laragon\www\`):
```bash
git clone <repository-url>
cd helpdesk
```

## 2. Install Dependencies
Install the required PHP and JavaScript packages:

### Backend (PHP)
```bash
composer install
```
*Note: If `composer` is not in your global path but exists in the project root, use `php composer.phar install`.*

### Frontend (JS)
```bash
npm install --legacy-peer-deps
```

---

## 3. Environment Configuration
Create your local environment file from the template:
```powershell
copy .env.example .env
```

Open the `.env` file and configure your database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helpdesk
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Initialize the Application
Run these commands to set up the encryption key and the database:

### Generate App Key
```bash
php artisan key:generate
```

### Create Database
Make sure you have created an empty database named `helpdesk` in your MySQL server (via Laragon, phpMyAdmin, or MySQL CLI).

### Run Migrations & Seeders
This will create all tables and the default admin/user accounts:
```bash
php artisan migrate:fresh --seed
```

### Link Storage
This is **REQUIRED** to make uploaded profile photos visible:
```bash
php artisan storage:link
```

---

## 5. Running the Application
To start the system, you need to run two processes in separate terminals:

### Start the Backend (PHP)
```bash
php artisan serve
```

### Start the Frontend (Vite)
```bash
npm run dev
```

The application will be available at `http://127.0.0.1:8000`.

---

---

## 6. Profile Photo Configuration
The system is configured to allow profile photo uploads up to **10MB**.

### Important: PHP Settings
If you are using **Laragon**, you must ensure your PHP configuration allows 10MB uploads:
1.  Right-click **Laragon** > **PHP** > **php.ini**.
2.  Find and update these lines:
    ```ini
    upload_max_filesize=10M
    post_max_size=10M
    ```
3.  **Restart** your Laragon services (Apache/Nginx) for the changes to take effect.

---

## Default Credentials
After seeding, you can log in with:

| Role  | Username | Password |
|-------|----------|----------|
| Admin | `admin`  | `password` |
| User  | `shande` | `password` |

---

## Troubleshooting
- **Missing Vite command**: Ensure you ran `npm install`.
- **Database Connection Error**: Check your `.env` credentials and ensure the database exists.
- **Login Failed**: Ensure you ran the seeder (`php artisan db:seed`).
- **Upload Failed**: If profile photos won't upload, check your `php.ini` limits as mentioned in step 6.
