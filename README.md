# File Modification Checker

Compares the date and time of each file since the last edit and returns a list of modified files

# How it works

This is a Linux & PHP console application (PHP >= 5.4)

1. **Edit string "$project_url"**<br>
   Enter the absolute or relative path to the files whose date you want to compare.<br>
   (Example: public $project_url = "/var/www/vhosts/you-site.com/www";)


2. **Run ./fmc.php init**<br>
   This will create "@current.txt" file in the directory with your files.<br>
   It contains the exact date and time of run this command.


3. **Edit/create one or more files in your project**

4. **Run ./fmc.php check**<br>
   This will display a list of modified files, the date of which modify is<br>
   greater than the date stored in "@current.txt"

* To remove "@current.txt" run **./fmc.php clear**

