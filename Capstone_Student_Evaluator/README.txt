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





***Edits to the DB to add in first_name and last_name for use on the signup page.

1. Go to the localhost/phpMyAdmin page

2. Go to the users DB and click on SQL tab at top of navigation bar

3. Erase the lines it wants you to type and write this code to add these columns to the table.
	ALTER TABLE users
	ADD COLUMN first_name VARCHAR(50) NOT NULL,
	ADD COLUMN last_name VARCHAR(50) NOT NULL;

4. Press go

5. That should add the new columns first_name and last_name to the DB to be able to identify users by their first and last name and not just by username/ID. You now should also be able to add entries using the signup.php page to start adding students and teachers to the database

***Edits for including submission function***

1. Go to localhost/phpmyadmin

2. Click on SQL tab in page

3. When the blank query textbox pops up, paste this:

	USE peer_review_db;

	CREATE TABLE submissions ( 
	id INT AUTO_INCREMENT PRIMARY KEY, 
	review_id INT NOT NULL, 
	student_id INT NOT NULL, 
	q1_rating INT, 
	q1_comment TEXT, 
	q2_rating INT, 
	q2_comment TEXT, 
	q3_rating INT, 
	q3_comment TEXT, 
	q4_rating INT, 
	q4_comment TEXT, 
	q5_rating INT, 
	q5_comment TEXT, 
	FOREIGN KEY (review_id) REFERENCES users(id), 
	FOREIGN KEY (student_id) REFERENCES users(id) 
	); 
4. Press Go

5. Now with this and the updated files from the GitHub. Reviews are able to be submitted by any logged in students, and passwords are now hashed when signing up. NOTE: you cannot use any old created users that don't have hashed passwords after the changes I've made. For testing, you will need make a new user. It will not let you log in with users that don't have a hashed password -HV

**Edits for pdf generation 

1. Go to this website: http://www.fpdf.org This will have you download the fpdf library that allows us to generate pdfs easier.

2.Go to Downloads

3. Download the first zip file that is in English. This should be the version v1.86 (2023-06-25).

4. Unzip the file and make sure to put it into the same file as the Capstone_Student_Evaluator, this will make sure the code can find the files in the right spot.

5. All of the FPDF files should be in a file named fpdf within the Capstone_Student_Evaluator folder. 
