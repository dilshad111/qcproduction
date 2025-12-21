# Corrugation Plant Time Tracking System - User Guide

## Overview
The corrugation plant time tracking system allows you to record work sessions manually at the end of each day or shift. This is perfect for tracking jobs that span multiple days with different work periods.

## How It Works

### Starting a New Job
1. Click "Manage" on a job from the Corrugation Plant Jobs list
2. Select the machine(s) and operator
3. Click "Start Job"
4. **Time Entry Modal Appears**: Enter the actual work times
   - **Start Time**: When work actually began (e.g., 5:00 PM)
   - **End Time**: When work ended (e.g., 8:00 PM)
   - **Notes**: Optional description (e.g., "Day 1 evening shift")
5. Click "Save & Continue"

### Multi-Day Jobs Example

**Scenario**: A job that takes 2 days to complete

**Day 1 (Evening Shift)**
- Work Period: 5:00 PM to 8:00 PM
- At end of shift, you already entered this in the initial modal
- Session 1: 3 hours recorded

**Day 2 (Morning Shift)**
- Work Period: 10:00 AM to 1:30 PM
- Click "Add Work Time Session" button
- Enter start: 10:00 AM, end: 1:30 PM
- Add note: "Day 2 morning shift"
- Session 2: 3.5 hours recorded

**Result**: Total work time = 6.5 hours

### Adding Additional Work Sessions

If a job spans multiple days or shifts:

1. Open the job management page
2. Click the green **"Add Work Time Session"** button
3. Enter the work period details:
   - **Start Time**: When this work period started
   - **End Time**: When this work period ended
   - **Notes**: Optional description to identify the session
4. Click "Add Session"

The system will automatically calculate the duration and add it to the total.

### Viewing Work Sessions

All recorded sessions are displayed in a table showing:
- Start date and time
- End date and time
- Duration in minutes
- Notes
- **Total Work Time** at the bottom

### Finishing the Job

When the job is complete:

1. Enter the **Total Sheets Produced**
2. Click **"Finish Job"**
3. The system will:
   - Calculate total work time from all sessions
   - Subtract any recorded downtime
   - Calculate average production speed
   - Display the results

## Key Features

### ✅ **Accurate Time Tracking**
- Record exact work periods, not just calendar time
- Jobs spanning multiple days are tracked correctly
- Example: 3 hours on Day 1 + 3.5 hours on Day 2 = 6.5 hours total

### ✅ **Flexible Entry**
- Enter all times at the end of the day
- No need to track live or use pause/resume
- Add multiple sessions as needed

### ✅ **Clear History**
- See all work sessions in one place
- Each session shows start, end, and duration
- Notes help identify different shifts/days

### ✅ **Accurate Metrics**
- Speed calculated based on actual work time
- Downtime is properly subtracted
- Results show both speed and total work hours

## Example Workflow

### Single Day Job
1. Start job at 9:00 AM
2. Initial modal: Enter 9:00 AM to 5:00 PM
3. Finish job: Enter sheets produced
4. Result: 8 hours work time

### Two Day Job
1. **Day 1**: Start job
   - Initial modal: 5:00 PM to 8:00 PM (3 hours)
2. **Day 2**: Resume work
   - Click "Add Work Time Session"
   - Enter: 10:00 AM to 1:30 PM (3.5 hours)
3. Finish job: Enter sheets produced
4. Result: 6.5 hours total work time

### Job with Break
1. Start job
   - Initial modal: 9:00 AM to 12:00 PM (3 hours)
2. After lunch break
   - Click "Add Work Time Session"
   - Enter: 1:00 PM to 5:00 PM (4 hours)
3. Finish job
4. Result: 7 hours total work time

## Tips

- **Be Accurate**: Enter the actual work times, not approximate
- **Use Notes**: Add descriptions like "Day 1", "Night shift", "Weekend" to identify sessions
- **Multiple Sessions**: You can add as many sessions as needed for a job
- **Review Before Finishing**: Check the total work time before finishing the job

## Benefits

1. **No Live Tracking Required**: Enter all data at end of day
2. **Multi-Day Support**: Jobs can span multiple days easily
3. **Accurate Calculations**: Speed based on real work time
4. **Audit Trail**: All sessions are recorded and visible
5. **Simple Interface**: Just enter start and end times

## Technical Notes

- All times are stored in the database
- Duration is automatically calculated
- Total work time = sum of all session durations
- Speed = (Total meters produced) / (Total work time - Downtime)
- Sessions can be added anytime before finishing the job
