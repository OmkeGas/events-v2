# Event Management System

## Overview
This project is an Event Management System designed for both users and administrators. It provides features for event registration, ticket generation, and event management. The system is divided into two main roles: **User** and **Admin**.

---

## Features

### User Features
#### Priority 1 (Core Features - Must Have):
1. **Authentication**
   - Register
   - Login
   - Logout
   - Edit basic profile (name, email, password)
2. **Event Management**
   - View event details
   - Register for events
   - View registered event tickets
   - Print tickets (with QR Code)
   - Print certificates (for attended events)

#### Priority 2 (Important Features - If Time Permits):
1. **User Dashboard**
   - List upcoming events
   - List past events
   - Registration status

#### Priority 3 (Additional Features - Nice to Have):
1. Reminder notifications
2. Social features (share, invite friends, forum)
3. Data export (Excel/PDF)
4. Detailed reports and analytics

### Admin Features
#### Priority 1 (Core Features - Must Have):
1. **Event Management**
   - CRUD events
   - Publish/unpublish events
   - Manage event categories
2. **Registration Management**
   - View registrant list
   - Validate attendance (QR Code scan/manual input)
   - Generate certificates

#### Priority 2 (Important Features - If Time Permits):
1. **Admin Dashboard**
   - Basic statistics (number of events, participants)
   - List of recent events
   - List of recent registrations
2. **User Management**
   - View user list
   - Activate/deactivate users
   - Reset user passwords

#### Priority 3 (Additional Features - Nice to Have):
1. Advanced analytics and reporting

---

## Implementation Suggestions

### Development Steps
1. Start with:
   - Complete authentication system
   - CRUD events for admin
   - Event list and details for users
   - Event registration system

2. Then proceed with:
   - Ticket system with QR Code
   - Attendance validation
   - Certificate generation and printing

3. Once the core system is functional, add:
   - User and admin dashboards
   - User management
   - Additional features

### Tips for Development
1. Focus on core functionality first.
2. Ensure all primary features work correctly before adding new ones.
3. Use existing templates and libraries to speed up development.
4. Test each feature thoroughly.
5. Document the code properly.

---

## Project Structure
```
app/
    Config/         # Configuration files
    Controllers/    # Application controllers
    Core/           # Core application logic
    Models/         # Database models
public/
    css/            # Public CSS files
    images/         # Public images
resources/
    css/            # Resource CSS files
    views/          # Blade templates
```

---

## Installation
1. Clone the repository.
2. Set up the database and import the `events.sql` file.
3. Configure the application in `app/Config/app.php`.
4. Run the application using a local server (e.g., Laragon).

---

## License
This project is licensed under the MIT License.
