
# **Cruise Nights Cinema Admin Dashboard**

Welcome to the Cruise Nights Cinema Admin Dashboard repository, a comprehensive PHP-based platform designed to manage all aspects of the drive-in cinema website, *cruise-nights-cinema.dk*. This dashboard empowers administrators with tools to oversee movies, customers, bookings, actors, genres, and general site information in a secure and efficient environment.

## **Project Overview**

Cruise Nights Cinema is a dynamic platform that combines a responsive user interface with robust administrative capabilities. Developed using vanilla PHP and CSS, this project adheres to the Model-View-Controller (MVC) architecture and prioritizes data security, modularity, and ease of use for cinema staff.

## **Features**

### **Admin Dashboard**
- **Responsive Layout**: Designed to ensure an optimal experience on both desktop and mobile devices.
- **Modular Card Design**: Management sections are organized with card layouts, providing clear, intuitive navigation for administrators.

### **Core Functionalities**
- **Movies Management**: Add, edit, delete, and categorize movies. Manage associated genres and actors through join tables for a structured and relational database.
- **Customer and Booking Management**: Efficiently handle customer details and reservations, including secure booking options and customer reviews.
- **Actors and Genres Management**: Link actors and genres to movies, leveraging foreign keys and SQL joins for efficient data retrieval.
- **News and Updates**: Expired movies are automatically archived into a news section based on their release date, keeping the site content fresh.
  
### **Security and Privacy**
- **Secure Authentication**: Admin login system secured against unauthorized access.
- **Data Protection**: High security for sensitive data, especially in customer and booking sections. Uses UUIDs for specific data to prevent direct ID exposure.
- **CSRF Protection and HTTPS Compatibility**: Designed to support CSRF tokens and HTTPS for secure data transmission.

## **Getting Started**

### **Installation**

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/Missjessen/Exam-1sem-bio.git
   cd Exam-1sem-bio
   ```

2. **Set Up Your Server**:
   - Place the cloned repository in your XAMPP `htdocs` folder.
   - Ensure Apache and MySQL services are running.

3. **Database Configuration**:
   - Import the included `.sql` file into your MySQL database.
   - Update `config/connection.php` with your database credentials.


### **Automated Tasks**
To automate archival of expired movies, set up a scheduled task (cron, launchd, or Task Scheduler) on your server to periodically run the archival script.

## **Usage**

Once logged in, administrators can:
- Manage movies, customers, and booking details.
- Update general information such as operating hours and contact details.
- Archive expired movies automatically to keep news relevant.

## **Contributing**

Contributions are welcome! Feel free to fork the repository, submit issues, or create pull requests.

## **License**

This project is licensed under the MIT License.
