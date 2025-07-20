# Servo Motors Control Panel

This project provides a web-based interface to control up to four servo motors. It allows you to set the angle of each servo, save the positions to a database, and load them back. The interface is designed to be simple and intuitive.

<img width="987" height="648" alt="image" src="https://github.com/user-attachments/assets/25a97e5d-5943-4134-a234-2d8115900631" />


## File Structure

*   `index.php`: The main entry point and view layer. It displays the control panel and saved positions.
*   `logic.php`: Handles all the server-side logic, including database connections, saving, loading, and removing positions.
*   `style.css`: Contains all the styling for the web interface.
*   `script.js`: Includes the client-side JavaScript for real-time updates of the slider values.

## Database Setup

The control panel uses a MySQL database named `servo`. It automatically creates two tables:

*   `angles`: Stores the saved servo positions.
*   `status`: (Not currently used in the UI) Can be used to track the status of the servos.

## How to Use

1.  Make sure you have a web server with PHP and a MySQL database (like XAMPP or WAMP).
2.  Create a database named `servo`.
3.  Place the project files in your web server's root directory (e.g., `htdocs`).
4.  Open `index.php` in your web browser.

## Showcase

![showcase](https://github.com/user-attachments/assets/7ebe3817-ebf7-4f42-a019-e5359dac91b3)
