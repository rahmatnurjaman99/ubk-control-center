# Academic Year Implementation Notes

## Relationships & Foreign Keys
- **Classroom**: `classrooms.academic_year_id` (each cohort belongs to a single year).
- **Subject**: `subjects.academic_year_id` (allows curriculum adjustments year-to-year).
- **ClassroomStaff**: `classroom_staff.academic_year_id` (history of teacher assignments per year).
- **Schedule / Timetable**: `schedules.academic_year_id` (weekly plan resets each year).
- **Attendance / Grade**: `attendances.academic_year_id`, `grades.academic_year_id` (ensures reports scoped to the correct year).
- **Fees / Transactions / Payroll**: `fees.academic_year_id`, `transactions.academic_year_id`, `payrolls.academic_year_id` for financial snapshots.
- **Student Enrollment Bridge**: `student_classrooms.academic_year_id` to track promotions.

Every dependent table should reference `academic_years.id`, index the foreign key, and cascade deletes cautiously (generally restrict to avoid removing historical data).

## Duplication Workflow Sketch
1. **Action Entry Point**: `AcademicYearResource` gets an action "Create from previous year" that asks for:
   - Source year (`AcademicYear` selector).
   - Destination metadata (code, name, dates, set current?).
   - Optional toggles (which domains to copy: classes, subjects, fee structure, etc.).
2. **Service Class**: `App\\Support\\AcademicYear\\DuplicateAcademicYearAction` handles the clone within a DB transaction:
   - Create the destination `AcademicYear` record.
   - Iterate over requested domains. For each, clone records with adjusted `academic_year_id` and regenerate unique fields (e.g., classroom code).
   - Copy nested relations where needed (e.g., classroom subjects, schedules).
3. **Progress Reporting**: Return a DTO summarizing what was copied (counts per domain) so the UI can show a notification or infolist.
4. **Extensibility**: Service exposes dedicated protected methods per domain (`duplicateClassrooms`, `duplicateSubjects`, ...), keeping logic isolated and easy to test once models exist.
5. **Validation**: Block duplication when source and destination overlap dates, or when a destination with the same code exists.

This outline keeps the duplication logic centralized and ready for expansion as soon as the dependent resources are created.
