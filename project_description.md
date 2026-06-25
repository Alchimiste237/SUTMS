# SUTMS (Smart University Timetable Management System) — Project Description

## Overview
SUTMS is a Laravel 11 + Livewire 3 web system for creating and managing university timetables. It automates weekly schedule generation using a constraint-aware engine that:
- respects **teacher availability**
- prevents **double-booking** of teachers
- prevents **class-group conflicts** (a class group cannot be scheduled in more than one activity at the same time)
- supports **draft / published / archived** weekly schedules

The UI uses Flux UI components and Livewire components for interactive scheduling screens.

---

## Primary Actors and What They Do

### 1) Administrator (Admin)
**Goal:** Maintain academic data, generate timetables, and publish schedules.

**What the administrator does**
- **Manage academic structure**
  - levels and **class groups**
  - **subjects** and their required weekly teaching workload inputs (through teaching assignments)
- **Manage faculty**
  - creates **teacher user accounts** and **teacher profiles**
  - assigns Spatie roles (e.g., role name `TEACHER`)
- **Create teaching assignments**
  - links a **teacher** to a **class group** and **subject** for a specific semester
  - stores **required_hours** per teaching assignment
- **Manage academic time**
  - creates/maintains academic years, semesters, and academic weeks
- **Create and manage weekly schedules**
  - creates a `WeeklySchedule` for a selected academic week in `DRAFT`
  - generates schedule entries for the week
  - performs manual grid assignments by placing teaching assignments into day/period slots
  - fills empty teacher slots with **personal working hours**
  - **publishes** schedules so students can view them
  - **archives** schedules that should no longer be the current student-visible option

**How the administrator does it (implementation-level)**
- Uses Livewire components (notably `ScheduleManager`) to drive scheduling workflows.
- Calls the scheduling engine via `ScheduleGeneratorService::generate($scheduleId)`.
- Uses `ScheduleManager` methods for manual assignment and publishing:
  - `createSchedule()` → creates `WeeklySchedule` with status `DRAFT`
  - `generate($scheduleId)` → runs engine + fills personal working hours
  - `assign($assignmentId)` → validates conflicts, removes existing class-slot entry, inserts a new `ScheduleEntry`
  - `markAsPersonalWorkingHour($teacherId)` → inserts an `is_personal_working_hour` slot if not conflicting
  - `publish($scheduleId)` / `archive($scheduleId)` → updates `WeeklySchedule.status`

---

### 2) Teacher
**Goal:** Define availability and view their personal timetable.

**What the teacher does**
- **Sets availability** for each weekday and period (e.g., Mon–Fri, P1–P4).
- **Views weekly timetable entries** assigned to them (teacher-specific view), typically from published schedules.

**How the teacher does it (implementation-level)**
- Availability editing is performed by `TeacherAvailabilityManager`:
  - identifies the teacher profile via authenticated user id (`Teacher::where('user_id', Auth::id())`)
  - loads `TeacherAvailability` rows and exposes a boolean matrix indexed by `(day_of_week, period_id)`
  - toggles availability by calling `TeacherAvailability::updateOrCreate(...)`
- Personal timetable display is performed by `TeacherTimetable`:
  - loads `WeeklySchedule` records for which entries exist (statuses include `PUBLISHED` and `DRAFT`)
  - filters `ScheduleEntry` records so only entries where the teacher is involved are loaded

---

### 3) Student (Public user / No-auth visitor)
**Goal:** View a class-group timetable for a specific published academic week.

**What the student does**
- Selects an academic week schedule.
- Selects a class group.
- Views the timetable grid showing the scheduled subject activities and the assigned teachers.

**How the student does it (implementation-level)**
- Uses the public Livewire component `StudentTimetableLookup` accessible via the `timetable` route.
- The component loads only `WeeklySchedule` records with `status = 'PUBLISHED'`.
- It filters schedule entries to only those where `teachingAssignment.class_group_id` matches the selected class group.

---

## System Modules and How They Work

### A) Academic Structure Module
**Responsibilities**
- Provide foundational entities for scheduling:
  - levels, class groups, subjects

**How it works**
- The system stores these in corresponding models and uses them in teaching assignments and schedule entries.

---

### B) Teacher Management Module
**Responsibilities**
- Create teacher accounts and profiles.

**How it works**
- `TeacherManager` Livewire component creates:
  - a `User` (auth identity)
  - assigns a Spatie role (creates/uses `Role` named `TEACHER`)
  - a `Teacher` profile linked to the user (`user_id`)

---

### C) Teaching Assignments Module
**Responsibilities**
- Define which teacher teaches which subject to which class group (per semester) and how many weekly hours are required.

**How it works**
- `TeachingAssignment` records provide:
  - semester scoping
  - `required_hours`
  - links to teacher, class group, and subject

---

### D) Teacher Availability Module
**Responsibilities**
- Store the teacher’s weekly open periods.

**How it works**
- `TeacherAvailability` rows represent availability per `(teacher_id, day_of_week, period_id)`.
- `TeacherAvailabilityManager` renders and toggles those availability entries.

---

### E) Weekly Schedule Management (Admin)
**Responsibilities**
- Create schedules, generate them, allow manual edits, and publish/archvive.

**How it works**
- The `ScheduleManager` Livewire component manages:
  - listing schedules and available weeks
  - schedule generation trigger
  - manual assignment operations
  - personal working hours filling
  - publishing and archiving

Key operations in `ScheduleManager`:
- `generate($scheduleId)`:
  - runs `ScheduleGeneratorService`
  - fills remaining teacher-free slots with `is_personal_working_hour = true`
- `assign($assignmentId)`:
  - checks teacher conflict and class-group conflict for the chosen slot
  - removes any existing schedule entry for the class/day/period
  - inserts a new `ScheduleEntry` linking the slot to the teaching assignment
- `publish($scheduleId)`:
  - sets `WeeklySchedule.status = 'PUBLISHED'`
  - this is what enables student visibility

---

### F) Scheduling Engine (ScheduleGeneratorService)
**Responsibilities**
- Populate `ScheduleEntry` rows for a weekly schedule.
- Respect constraints.

**How it works**
Implemented in `ScheduleGeneratorService::generate($weeklyScheduleId)`:
1. Load `WeeklySchedule` and delete prior `ScheduleEntry` rows for that week.
2. Determine the semester from the schedule’s academic week.
3. Load `TeachingAssignment` rows for that semester, ordered by `required_hours` (descending).
4. Iterate through days (Mon–Fri) and periods (P1–P4) and place assignments greedily until required weekly hours are reached.
5. Before creating a schedule entry, enforce constraints:
   - teacher must be available (`TeacherAvailability.available = true`)
   - teacher must not already be busy at that day/period within the weekly schedule
   - class group must not already be busy at that day/period within the weekly schedule
6. Generation occurs inside `DB::transaction(...)` for consistency.

---

## End-to-End Workflow Summary
1. **Admin** creates academic structure and teaching assignments (including `required_hours`).
2. **Teachers** define availability.
3. **Admin** creates a `WeeklySchedule` in `DRAFT`.
4. **Admin** runs generation:
   - `ScheduleGeneratorService` produces class/teacher slots using availability + conflict constraints
   - remaining free teacher slots become personal working hours
5. **Admin** publishes the schedule (`status = PUBLISHED`).
6. **Teachers** view their personal timetable filtered to teacher-related entries.
7. **Students** view the class-group timetable via public lookup, only using published schedules.

