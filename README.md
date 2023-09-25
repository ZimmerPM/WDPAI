# LibraApp

[Read this in Polish :poland:](README-PL.md)

## Introduction
LibraApp is an application designed for managing library collections. It allows users to borrow books, manage their account, and browse available titles. The project was created for learning and practicing with web technologies.

### Application Views
![Login View](/public/img/screenshots/login-screen.JPG)
![Login View - Mobile Version](/public/img/screenshots/login-screen-mobile.JPG)
![Catalog View](/public/img/screenshots/catalog-view.jpg)
![Catalog View - Mobile Version](/public/img/screenshots/catalog-view-mobile.JPG)
![Loans Management View](/public/img/screenshots/loans-view.JPG)
![Loans Management View - Mobile Version](/public/img/screenshots/loans-view-mobile.JPG)

## Technologies
- PHP 7.4.3
- PostgreSQL 15.4
- HTML 5
- CSS
- JavaScript
- Docker 24.0.5

## Getting Started
To run the project locally, follow these steps:
1. Download Docker from [Docker](https://www.docker.com/) and install it.
2. Clone the repository.
3. Set up the database connection in the `config.php` file.
4. In the terminal, set the project directory as the current one and start the Docker container using the command: `docker-compose up`.
5. Open a web browser and enter the address: http://localhost:8080
6. To stop the application, use the command in the terminal: `docker-compose down`.

## Functionalities
### Reader's Account:
- User registration and login with the possibility of changing the password at any given time.
- Browsing the catalog of available books along with a search feature.
- Reserving books for borrowing.
- Access to the history of personal loans.

### Administrator’s Account:
- Managing the book catalog - adding, deleting, and editing entries.
- Managing the process of borrowing books by readers - approving or canceling reservations, loans, and returns.
- Managing application users - editing user data and deleting user accounts.

## Additional Information
Author: Piotr Zimirski  
Repository Link: [GitHub](https://github.com/ZimmerPM/)

Thank you for your interest in the LibraApp project!
