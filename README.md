# Plain PHP and MySQL complete E-commerce Application
A plain PHP/MySQL complete E-commerce application with an Admin Panel, Login System, Registration, Validation and Authorization. It provides the functionality needed for running an online store, such as product listing, shopping cart, and order management and approvals through its Admin Panel. This project aims to demonstrate the implementation of an e-commerce system without relying on any external libraries or frameworks.

Frontend technologies used: jQuery and Bootstrap.

## Screenshots:
***Admin Panel Login***
![admin-panel-login](https://github.com/AhmedYahyaE/plain-php-ecommerce/assets/118033266/fd26152d-fd25-4367-9334-7f10c048bea7)

## Features:
1- User Registration, Authentication and Authorization.

2- Both Server-side and Client-side Validation.

3- Login System (Session Management).

4- CRUD Operations.

5- Admin Panel for the website owner (interactive Dashboard, user registration approval, member commemt approval, item and category approval, ...).

6- User Roles and Permissions.

7- File Upload.

## Application URLs:
1- Frontend: The public-facing website can be accessed at https://www.domain-example.com/index.php. This is where customers/users/members can browse products/items, add items to their cart, and comment on existing products, ...

2- Admin Panel: The Admin Panel for managing the E-commerce website is available at https://www.domain-example.com/admin/index.php. This is a secure area accessible only to authorized administrators. It provides functionalities for managing products/items, categories, orders, and user accounts and comments.

## Installation & Configuration:
1- Clone the project or download it.

2- Create a MySQL database named **\`shop\`** and import the database schema from [shop database - PhpMyAdmin Export.sql](<Database - shop/shop database - PhpMyAdmin Export.sql>) SQL Dump file. Navigate to '**`Database - shop`**/**`shop database - PhpMyAdmin Export.sql`**' SQL Dump file.

3- Navigate to the database connection configuration file in '**`admin/connect.php`**' file and configure/edit the file according to your MySQL credentials.

4- Navigate to the project root directory by using the **`cd`** terminal command and then start your PHP built-in Development Web Server by running the command: **`php -S localhost:8000`**.

5- In your browser, go to http://localhost:8000/index.php (**Frontend**) and http://localhost:8000/admin/index.php (**Admin Panel**).

6- A ready-to-use registered user account credentials (for both Frontend and Admin Panel):

> **Username**: **Ahmed**, **Password**: **123456**

## Contribution:
Contributions to my plain PHP/MySQL E-commerce application are most welcome! If you find any issues or have suggestions for improvements or want to add new features, please open an issue or submit a pull request.
