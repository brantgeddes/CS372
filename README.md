# README #

Information on working with GIT and setting up project

### What is this repository for? ###

CS372 Stock Market Simulator App

### How do I get set up? ###

* Summary of set up
Create account at https://bitbucket.org/
Install GIT:
  Hercules comes with it
  Rasberry Pi running Rasbian: 
    sudo apt-get update
    sudo apt-get upgrade  //May take awhile depending on last time update happened
    sudo apt-get install git
    
    git config --global user.name "<your-name>"
    git config --global user.email "<your-email>"
    
navigate to the folder you want to use for the project, and in command line:

GIT CLONE https://<your-username>@bitbucket.org/geddes2b/cs372.git //Be sure to use your bitbucket username

Enter password for your bitbucket account. 

This will pull from the repository and place the project into the folder. Now all files can be viewed 
and modified. 

### Ignoring files in GIT ###

If you have files that you do not want uploaded to the repository, for example files that contain password information or
any shell scripts, you can add them to the exclude file located in .git/info/exclude

This is the contents of my exlude file:

*.sh
routes/tokens.php

Add any files or file extensions that you don't want pushed to this repository

### SQL Table Setup ###

List of SQL commands to setup project tables:

CREATE TABLE Users (id INT AUTO_INCREMENT, email VARCHAR(30), password VARCHAR(255), username VARCHAR(30), type VARCHAR(10), balance INT, PRIMARY KEY(id));
CREATE TABLE Stocks (id INT AUTO_INCREMENT, name VARCHAR(50), symbol VARCHAR(15), sector VARCHAR(255), industry VARCHAR(255), PRIMARY KEY(id)); 
CREATE TABLE Portfolio (id INT AUTO_INCREMENT, user_id INT, stock_id INT, PRIMARY KEY(id), FOREIGN KEY(user_id) REFERENCES Users(id), FOREIGN KEY(stock_id) REFERENCES Stocks(id));

### tokens.php ###

This file is included in includes.php, and contains my database information and other account information.
Create your own and set it to:

<?php

  $DB_USERNAME = "<database-username>";
  $DB_PASSWORD = "<database-password>";
  $DB_NAME = "<database-name>";

?>

### Contribution guidelines ###

Useful tutorial: https://www.atlassian.com/git/tutorials/syncing

To get an up-to-date project:
GIT PULL origin
<enter password>

To start a new feature:
GIT CHECKOUT -b <branch-name> //branch-name can be anything to identify the feature

To switch branches:
GIT CHECKOUT <branch-name>

To see active branches:
GIT BRANCH

To add or remove a file for git to track:
GIT ADD <file-name>
GIT RM <file-name>

To finalize a feature:
GIT COMMIT -m "<message>" //message is a quick description of what happened since the last commit

To add feature into main code: (ONLY WHEN IT WORKS!!!!)
GIT CHECKOUT Master
GIT MERGE <branch-name> //Name of branch where new feature was developed

Typical workflow:

GIT PULL origin //Pull new code form repository
<enter password>

GIT CHECKOUT -b <branch-name> //Start working on new feature

*create new files for feature if needed
GIT ADD <file-name> //If any new files are needed

*work on features, test code
GIT COMMIT -m "<message>" //Commit at appropriate checkpoints, like when a certain part of the feature works

*After feature is tested and works, merge into main code
GIT CHECKOUT Master
GIT MERGE <branch-name>

*Push to repo code for everyone else to use
GIT PUSH Origin Master


### Who do I talk to? ###

* Repo owner or admin
brantgeddes@gmail.com
* Other community or team contact