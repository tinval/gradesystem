# Gradesystem
A primitive grade entering system

## Set up
- Do 'Datenexport (Zip/Text)' in edoz and save .txt file to working folder
- Set up a MySQL database at 'https://itshop.ethz.ch/' under 'Service Catalog' 
- Fill out the rest of the configuration file 'config.json':
  - Enter your MySQL database details
  - There are two 'tablename's, one for the first and one for the second semester. There are also two 'exercise' configurations where you can enter the number of exercise sheets and which is the first exercise. Be careful in choosing the number of exercises. You need to add an extra column by hand to the database if you want one more exercise later
  - Enter the edoz file (you can leave the edoz file blank for the second semester if you do not yet have the data)
  - Add the lowest limit of solved exercises to still get zero bonus and add the minimal number to get the full bonus. This is separate for the first and the second semester and can be adjusted at any time
  - 'semester2view' specifies whether the total bonus (the mean of the two boni) is already shown. This probably makes more sense if the second semester already started
- Copy the config.json alos to the folders 'analysis1', 'analysis2' and to 'grades'
- Try 
```python
python3 create_database1.py
```
Maybe you need to install additional libraries to let this command run. You can run this command multiple times. When your edoz file gets longer it just adds the new students (comparing to the already added students via unique leginummer
- Add the names of your assistents to the file 'assistents.txt' in the folder 'analysis1' 
- Put the folders 'analysis1' and 'grades' to your personal 'www' folder (the one where your personal website data is hosted. The metaphor webpage is static, so unfortunately no .php scripts can be executed). You can of course rename the folder 'analysis1' if you want to without breaking a link. 

## Security
See <https://blogs.ethz.ch/isgdmath/webpage-with-password/>
- To stop the access to you 'config.json' from outside add to 'www/.htaccess' the following lines
```
<Files ~ "\.inc$">  
Order Allow,Deny
Deny from All
</Files>
```
- To make the site to enter the grades password protected add to 'www/analysis1/.htaccess' by entering your eth username where your website is hosted
```
AuthName "Please enter username and pw..."
AuthType Basic
AuthUserFile /hg/w/www1/users/[USERNAME]/www/.htpasswd
Require valid-user
```
- Create a user and set a password. You will later distribute this password to your teaching assistents such that they can enter the grades
```
htpasswd -c ~/www/.htpasswd user
```
- Finally check that all permissions are set correctly and change them to read access from outside:
```
ls -al www/grades
chmod +644 www/grades/*
chmod +755 www/grades
chmod +644 www/analysis1/*
chmod +666 www/grades/logs.txt
chmod +755 www/analysis1
chmod +644 www/.htpasswd
chmod +644 www/analysis1/.htaccess
chmod +644 www/grades/.htaccess
```

## How it works
The teaching assistents go to your website <https://people.math.ethz.ch/~[USERNAME]/analysis1/>, enter the password and choose their name. The list is already filtered by their name, so it looks empty the first time. In the filter textbox they can delete their name to see all the students of the course. Once they entered the points for a student the assistent is set to the current assistent. This has the advantage that students can change the assistent. The assistent column is always set to the latest assistent. As an organizer you can track when and who entered the points under <https://people.math.ethz.ch/~[USERNAME]/analysis1/logs.txt>.
The students go to the website <https://people.math.ethz.ch/~[USERNAME]/grades2/?leginr=20-000-000&name=kuerzel>. They should replace in the URL the Leginummer '20-000-000' by their Leginummer and 'kuerzel' by their ETH Kuerzel.
 
## Backup
- You can manually download the database by the command
```python
python3 download_database1.py
```
- I recommend to set a cronjob to regularly save the database. In Linux
```
crontab -e
```
Add the following line to download the database everyday at 11am: 
```
00 11 * * * python3 /userdata/programming/python/gradesystem/download_database1.py
```
