1. Alter database to add team identification:
	Select peer_review_db in the databases menu in localhost/phpmyadmin/
	
	Click SQL tab in browser menu, enter the following:
		CREATE TABLE teams (
    			student_id INT,
    			team_id INT,
    			team_name VARCHAR(50),
    			student_name VARCHAR(50),
    			PRIMARY KEY (student_id, team_id),
    			FOREIGN KEY (team_id) REFERENCES users(id)
		);
	
	You should now have a teams table in the peer review database that you can view
	when uploading excel files to ensure team information is updated.

	You're done with the DB steps now

2. Install PHPSpreadsheet library to the project folder.

	You will need to install Composer to your system first if you don't already have it
	link: https://getcomposer.org/download/
	Just download the 'Composer-Setup.exe' labeled file from this page. Just install
	the program with default settings. No special options are needed

	(DO THIS STEP IF BELOW STEPS DON't WORK FOR YOU! IT DIDN't FOR ME WITHOUT DOING THIS)
	↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	You need to make some changes to PHP before installing PHPSpreadsheet
	Navigate to wherever your XAMPP PHP install and the ini file is, by default
	it should be here 'C:\xampp\php\php.ini' at least in my case.
	Open the ini file in a text editor, and edit the following:
	search for: ';extension=gd' and remove the semicolon
	search for: ';extension=zip' and remove the semicolon
	Save the php.ini file after making these changes. Now PHPSpreadsheet should be
	able to be installed fine

	To install PHPSpreadsheet, open the windows command prompt and CD to the
	project directory, example command: 'cd C:\xampp\htdocs\Capstone_Student_Evaluator' in my case
	run the command 'composer require phpoffice/phpspreadsheet'
	It should install the correct required files now for the excel upload function to work!

Now the updated index.php and new upload_teams.php files should work correctly. When navigating to 
localhost/Capstone_Student_Evaluator/upload_teams.php in browser, you will be able to play around
with spreadsheets structured exactly like the test one I'm including in the GitHub and it should
update the teams database accordingly with students team assignments when uploaded. There is currently no
requirement for admin to be logged in to use this page yet and no CSS associated but it is functional.
Now submitting reviews for students will only give them their teammates assigned to them as options
in the review submission page.

In the included excel file 'test.xlsx'. Columns A-D are labeled correctly with the required values
The information in the current file relates to users I've created on my machine, adjust
these accordingly to ones you've created in your database for functionality. Team name can be
anything, I just named them 'Capstone Group 1' or 2 respectively.
