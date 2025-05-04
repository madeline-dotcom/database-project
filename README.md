# ANT IT Company

## Description
A way for ANT IT Company to manage its clients and their device support requests. 

## Users

Client:
- Submit device support tickets
- View all past tickets

Employee:
- Search and view tickets
- Claim open tickets and manage their progress
- View, add, and remove client devices

Admin:
- Manage clients and employees
- Add and remove user accounts
- View, add, and remove client devices
- Submit and assign device support tickets
- Search and view tickets

## Installation

### Database Setup:
1. Install [MySQL community server](https://dev.mysql.com/downloads/mysql/).
2. Open the terminal and navigate to the project's directory.
3. To create the database: ```mysql -u root -p < path to schema.sql ```
4. To load data into the database: ```mysql -u root -p < path to load.sql```

To view the database, sign into MySQL: ```mysql -u root -p```

### Launch Application:
1. Open a new terminal window.
1. Install php: ```brew install php``` (install [Homebrew](https://brew.sh/) if needed)
2. Start the server: ```php -S localhost:8000```
3. Visit http://localhost:8000 in your browser

## How to Use the Application
Login with your credentials (if you do not already have a login, you must ask the admin to create your account).
