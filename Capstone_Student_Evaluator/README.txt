This is the README file for the Capstone Student Evaluator application

How to utilize PHP and MySQL using XAMPP for database development/testing
for the Student Evaluator app:

1. Ensure XAMPP installation location. 
	- Move capstone project files to C:\xampp\htdocs\Capstone_Student_Evaluator
	(If you installed XAMPP to default C drive in Windows.)
2. Start XAMPP in order for testing/development
	- Start program in Windows. Select 'Start' for both Apache and MySQL modules.
	(Both must be running for PHP and Database functionality in Web App)
3. Create MySQL database and Users Table (This step is only necessary once)
	- In web browser, go to http://localhost/phpmyadmin (ensure XAMPP is running)
	- Click 'Databases' in menu
	- Create a new database named 'peer_review_db'
	- Click on 'SQL' tab in menu, run the following code:

		CREATE TABLE users ( 
			id INT(11) AUTO_INCREMENT PRIMARY KEY, 
			username VARCHAR(50) NOT NULL UNIQUE, 
			password VARCHAR(255) NOT NULL, 
			role ENUM('student', 'instructor') NOT NULL, 
			time_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
		); 
	
	- The above code will create a users table in the peer_review_db database, 
	you should now see the table created in the phpmyadmin page.
	- 'id' auto increments a unique id for each user
	- 'username' ensures a unique username must be created
	- 'password' stores hashed password
	- 'role ENUM' creates both necessary roles
	- 'time_created' records account creation time.

4. 'db_connection.php' can be utilized in necessary php files within the app so 
we can simply link each page to this file instead of rewriting a connection script each time.
Example: if you are developing the apps registration page (registration.php) its code must look like:
<?php
include 'db_connection.php';

(All necessary code goes after include statement)

?>

5. Testing code in all web app files from now on should be done through localhost within the web browser
with XAMPP running (especially for PHP functionality testing,) 
	For example: to use the test_db_connection.php file
	you would type http://localhost/Capstone_Student_Evaluator/test_db_connection.php 
	in your web browser. All other files should work this way as well.
