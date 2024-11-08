# **Drive-in Cinema Admin Dashboard**

This repository contains the source code for a responsive admin dashboard developed in vanilla PHP and CSS. The dashboard is tailored for managing a drive-in cinema website, allowing administrators to efficiently manage movies, customers, bookings, and general information.

## **Features**

- **Responsive Design**: Adapts seamlessly to both desktop and mobile devices, ensuring a consistent user experience.
- **Modular Card Layout**: Different management sections, such as movies, bookings, and customers, are organized in card-based layouts for clear navigation.
- **Admin Tools**: Allows admins to create, edit, and delete movies, customers, actors, and news entries with simple form submissions.
- **Automated Updates**: Can be configured to automatically archive expired movies into the news section based on their premiere dates.
  
## Installation
To get the project up and running locally:

1. **Clone the Repository**:
   ```bash
   `git clone https://github.com/yourusername/drive-in-cinema-admin-dashboard.git`
   `cd drive-in-cinema-admin-dashboard`


### Set up XAMPP (or your preferred local server):

Ensure that XAMPP is running Apache and MySQL services.

Place the cloned repository in your htdocs folder.

### Database Configuration:

Import the included .sql file into your MySQL database to set up the necessary tables.

Update the connection.php file in the includes directory with your database credentials.

### Run the Application:

Open a browser and go to [GitHub] (http://localhost/drive-in-cinema-admin-dashboard).
Usage

After logging in, you can access various admin functionalities to manage movies, customers, actors, and news entries.
You can also use the settings section to update general information, such as opening hours, contact details, and more.
Automated Tasks

This project supports automatic updates for expired movies. On a live server, you can set up a cron job (Linux), launchd (macOS), or Task Scheduler (Windows) to periodically run the script that moves expired movies to the news section.

### Contributing

Feel free to fork this repository, submit issues, or contribute with pull requests to improve this project.

### License

This project is licensed under the MIT LicenseTrigger redeploy manually
