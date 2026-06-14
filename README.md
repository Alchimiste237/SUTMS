# SUTMS - Smart University Timetable Management System

SUTMS is a professional, high-performance university management portal designed to automate and streamline the complex task of academic scheduling. Built with **Laravel 11**, **Livewire 3**, and **Flux UI**, it provides a conflict-aware scheduling engine that balances teacher availability with class requirements.

## 🚀 Project Overview

The system transitions from manual, error-prone scheduling to an automated, class-centric model. 
*   **For Admins**: Comprehensive tools to manage academic structures, faculty, and time calendars. One-click schedule generation ensures no teacher or class is ever double-booked.
*   **For Teachers**: A personalized portal to set availability and view individual weekly timetables.
*   **For Students**: A public, mobile-friendly lookup tool to view up-to-date class schedules.

---

## 🛠️ Setup & Installation Guide

Follow these steps to get SUTMS running on your local machine.

### 1. Prerequisites
Ensure you have the following installed:
*   **PHP 8.2+**
*   **Composer**
*   **Node.js & NPM**
*   **SQLite** (or your preferred database engine)

### 2. Clone the Repository
```bash
git clone <repository-url>
cd SUTMS
```

### 3. Backend Configuration
Install PHP dependencies and set up your environment:
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
The system uses SQLite by default for easy setup.
```bash
# Create the database file (Windows)
type nul > database/database.sqlite

# Run migrations and seed the Administrator account
php artisan migrate --seed
```
*   **Default Admin**: `admin@sutms.com` / `password`

### 5. Frontend Assets
Install and compile the UI components:
```bash
npm install
npm run build
```

### 6. Launch the Application
Start the local development server:
```bash
php artisan serve
```
Visit `http://localhost:8000` to see the landing page.

---

## 🗺️ Implementation Roadmap

### ✅ Phase 1: Foundation (Completed)
- [x] Professional Branding & UI Redesign (SUTMS Identity).
- [x] Responsive Sidebar & Dashboard Navigation.
- [x] Dark/Light mode support with Flux UI.

### ✅ Phase 2: Academic Management (Completed)
- [x] **Academic Structure**: Levels, Class Groups, and Subjects management.
- [x] **Faculty Management**: Teacher profile creation and secure user accounts.
- [x] **Time Management**: Academic Years, Semesters, and Auto-generating Weekly calendars.

### ✅ Phase 3: Scheduling Engine (Completed)
- [x] **Teacher Availability**: Interactive grid for teachers to set their free hours.
- [x] **Teaching Assignments**: Intelligent linking of teachers to subjects and classes with automated hour tracking.
- [x] **Conflict-Aware Generator**: Engine that prevents teacher/class double-booking while filling required weekly loads.

### ✅ Phase 4: Access & Visibility (Completed)
- [x] **Teacher Portal**: Personalized "My Timetable" view.
- [x] **Public Lookup**: Student-facing timetable portal (No login required).
- [x] **Publishing Workflow**: Admin control over Draft, Published, and Archived schedules.

### 🔜 Phase 5: Future Enhancements (Planned)
- [ ] Room/Resource management (Assigning specific classrooms).
- [ ] PDF Export for timetables.
- [ ] Email notifications for schedule changes.
- [ ] Student enrollment tracking.

---

## 📝 Credentials for Testing
| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | admin@sutms.com | password |


---
*Developed for modern academic environments.*
