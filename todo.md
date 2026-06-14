# SUTMS Project TODO List

## Phase 1: Project Setup & Foundation (Milestone 1)
- [x] Initialize Laravel project
- [x] Configure Database (MySQL or PostgreSQL)
- [x] Install and configure `spatie/laravel-permission`
- [x] Implement Authentication system
    - [x] Admin Login
    - [x] Teacher Login
    - [x] Role-based access control (ADMIN, TEACHER)
- [x] Academic Structure CRUD
    - [x] Level management
    - [x] Class Group management
    - [x] Subject management (id, code, name, credit_hours)
- [x] Teacher Management CRUD
    - [x] Teacher profiles (INTERNAL/EXTERNAL)
    - [x] Link users to teacher profiles
- [x] Teaching Assignment Module
    - [x] Interface to assign subjects to teachers and class groups
    - [x] Track required hours per assignment

## Phase 2: Availability & Calendar (Milestone 2 - Part 1)
- [x] Semester & Academic Week Management
    - [x] Academic Year setup
    - [x] Semester setup
    - [x] Weekly breakdown
- [x] Teacher Availability Module
    - [x] Availability matrix UI for teachers
    - [x] Backend storage for availability slots
- [x] Academic Calendar Module
    - [x] Holiday management
    - [x] Campus events and blocked periods

## Phase 3: Scheduling Engine & Timetable Management (Milestone 2 - Part 2)
- [x] Weekly Timetable Generator Service
    - [x] Implement Greedy Constraint Scheduling algorithm
    - [x] Logic for: Teacher availability, Class availability, Room availability (if applicable), Remaining hours
- [x] Weekly Schedule UI (Admin)
    - [x] Grid view of the generated schedule
    - [x] Conflict detection indicators
- [x] Manual Timetable Adjustments
    - [x] Ability for Admin to override/move assignments in the grid

## Phase 4: Publishing & Features (Milestone 3)
- [x] Publishing Workflow
    - [x] Draft, Published, and Archived statuses for schedules
- [x] Teacher Timetable View
    - [x] View personalized timetable once published
- [x] Notifications System
    - [x] Notify teachers when schedules are published or modified
- [x] Progress Tracking
    - [x] Calculate and display percentage of teaching hours completed vs. required
- [x] Reporting & Audit Logs
    - [x] Teacher workload reports
    - [x] Subject progress reports

## Phase 5: Student Access & Refinement
- [x] Student Timetable Lookup (No Auth)
    - [x] Select class and view published timetable
- [x] UI/UX Polishing
    - [x] Enhance grids and dashboards
- [x] Final Testing & Bug Fixes
- [x] Documentation update
