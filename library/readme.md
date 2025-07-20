# Library Management System

A professional, scalable Library Management System for MCA Advanced Web Technologies project submission.

## Features
- **Authentication:** Admin & user login, registration, profile management, secure password hashing
- **Dashboard:** Responsive Bootstrap UI, dynamic book display
- **Cart & Checkout:** Add to cart, checkout, admin approval queue
- **Admin Approval:** Approve/deny requests, notifications, email-ready
- **Reservation & Queue:** Reserve unavailable books, queue system, notifications
- **Return & Fine:** Mark as returned, fine calculation, admin/user return
- **Search & Filter:** AJAX search by title, author, ISBN, publisher, department filter
- **Ratings & Reviews:** User ratings (1–5 stars), reviews, average rating
- **Analytics Dashboard:** Total/issued/available books, top issued books (Chart.js)
- **Bulk Upload:** Admin upload via CSV
- **QR Code Generation:** QR for each book
- **Activity Log:** Track login, logout, checkout, return
- **Dark Mode:** CSS variables, JS toggle
- **Chatbot:** JS help desk for user guidance

## Technology Stack
- **Frontend:** HTML, CSS, Bootstrap, JavaScript (AJAX, dark mode, chatbot)
- **Backend:** PHP (modular, OOP/procedural)
- **Database:** MySQL

## File Structure
```
/css         # Stylesheets (Bootstrap, dark mode)
/js          # JavaScript modules (AJAX, chatbot, dark mode)
/php         # Backend scripts (auth, db, CRUD, cart, approvals, returns, logs)
/uploads     # Book images
/admin       # Admin dashboard modules
/includes    # Header, footer, navbar
index.php    # Home/dashboard
readme.md    # Project documentation
database.sql # Database schema
```

## Setup & Usage
1. **Clone or copy the project to your XAMPP `htdocs` directory.**
2. **Import `database.sql` into your MySQL server.**
3. **Configure database credentials in `php/db.php` if needed.**
4. **Start Apache & MySQL in XAMPP.**
5. **Access the app at `http://localhost/library/`**
6. **Default Admin Login:**
   - Email: `admin@library.com`
   - Password: `admin123` (change after first login)

## Contribution
- Fork the repo, create a branch, and submit pull requests.
- Ensure code is modular, commented, and follows best practices.

## License
MIT 