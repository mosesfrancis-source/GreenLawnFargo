# Green Lawn Fargo Page Explanations

## Core Site Pages

### index.php
This is the homepage of the application. It introduces Green Lawn Fargo, explains the purpose of the website, and gives users quick access to important actions such as viewing services, registering, or logging in.

### about.php
This page explains what the Green Lawn Fargo project is about. It describes the system, the purpose of the application, and includes links or cards for the team members’ roles.

### services.php
This page displays all lawn care services from the database. Each service shows its name, description, price, duration, and image. Logged-in customers can use this page to begin requesting services.

### contact.php
This page allows visitors or customers to send messages to Green Lawn Fargo. It includes contact information such as phone number, email, location, and business hours.

### header.php
This is the shared navigation file used across the website. It displays the navbar and changes the menu based on whether the user is a customer, admin, or guest.

### footer.php
This is the shared footer file. It displays company information, quick links, contact details, business hours, copyright information, and Bootstrap JavaScript.

## Customer Account Flow

### register.php
This page allows new customers to create an account. It collects customer information, hashes the password using `password_hash()`, and stores the account securely in the database.

### login.php
This page allows existing customers to log in. It checks the customer email using a prepared statement and verifies the password using `password_verify()`. If successful, it starts a customer session.

### logout.php
This page ends the current session and logs the user out. It works for both customer and admin sessions, then redirects the user back to the homepage.

### profile.php
This page allows logged-in customers to view and update their account information, such as first name, last name, email, phone, and address.

### update_account.php
This page acts as a compatibility page or redirect page. It sends users to `profile.php`, where account updates are actually handled.

### auth_check.php
This file protects customer-only pages. If a user is not logged in as a customer, it redirects them to the login page.

### booking_history.php
This page displays the logged-in customer’s previous and current bookings. It shows booking date, time, status, provider, final price, and confirmation message.

### calendar.php
This page is used as a placeholder or simple calendar feature. It points users toward their estimates and booking history for service scheduling.

## Service Request and Booking Flow

### request_service.php
This is one of the main customer pages. It allows logged-in customers to select one or more services, choose yard size, enter a preferred date, add notes, and submit a service request. The system calculates the estimate and stores the request.

### estimate.php
This page shows the customer’s service requests and pricing details. It displays base total, discount percentage, final estimate, status, and a button to continue to booking.

### book_service.php
This page turns a service request into an actual booking. It lets customers choose a booking time and saves the final booking in the database.

### confirmation.php
This page displays the booking confirmation after a booking is created. It shows confirmation details and helps the customer know the booking was successfully submitted.

## Feedback and Messaging

### feedback.php
This page allows customers to submit feedback and ratings. It can also display existing feedback to show customer opinions about the service.

### feedback_new.php
This is an alternative feedback page. It has a similar purpose as `feedback.php` and can be removed or merged later if the team wants to avoid duplication.

### admin_feedback.php
This admin page allows administrators to review customer feedback. It helps the business monitor customer satisfaction.

## Admin Area

### admin_login.php
This page allows administrators to log in separately from customers. It checks admin credentials and starts an admin session using `AdminID`.

### admin_check.php
This file protects admin-only pages. If a user is not logged in as an admin, it redirects them to `admin_login.php`.

### admin_dashboard.php
This is the main admin page. It shows summary information such as total customers, total services, total requests, and total bookings.

### admin_bookings.php
This page allows admins to view and manage customer bookings. It uses booking data from the database and can use the `vw_booking_summary` view for cleaner queries.

### admin_services.php
This page allows admins to add and manage lawn services. Any new service added here can appear dynamically on the public services page.

### make_admin.php
This is a temporary setup file used to create the first admin account. After it is used once, it should be deleted for security reasons.

## Shared and Team Pages

### db.php
This file connects the application to the MySQL database. Other PHP files include it whenever they need database access.

### functions.php
This file is reserved for reusable helper functions. It is currently empty but can later store shared logic such as formatting prices or checking status values.

### moses.php
This page describes Moses Francis’s role in the project. It explains backend development, database design, security, booking logic, and GitHub coordination.

### jesleen.php
This page describes Jesleen Mulbah’s role in the project. It explains frontend design, Bootstrap styling, responsive layouts, and user interface improvements.