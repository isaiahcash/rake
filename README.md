#Craigslist Vehicle Web Scraper

Front End:<br>
HTML<br>
JavaScript<br>

Back End:<br>
PHP 7.0<br>
MySQL<br>

Included Packages:<br>
PHP Simple HTML DOM Parser 1.8.1<br>
jQuery 3.3.1<br>
Bootstrap 4.1.3<br>
DataTables 1.10.18 (with Bootstrap 4 styling)<br>
FontAwesome 5.6.3<br>

Host: <br>
Amazon EC2 RH Instance<br>
Apache 2.4<br>

This site combines auto listings from Craigslist in 20+ cities into a refined, dynamic database. 
The code visits the 'Cars & Trucks' of each city and scrapes each listing for make, model, information, images, mileage, and much more.

The site is centered on a main page, providing all listings in one table or grid. 
Here the user has the the ability to filter by Year, Make, Model and other attributes such as Price, Mileage, Title Status, and Transmission type. 
Preselected filters for recent, possible duplicates, and unknown listings are also available.
When viewing the listings in the table, users have the ability to search, sort, and show and hide columns. 
Users can favorite and delete listings as needed.

<hr>
The site utilizes cron jobs on the server to automate the site. 
<br>
Web Scraping: A Craigslist city is scraped for new 'Cars & Trucks' listings. 
If new listings are found, each individual listing is visited for information. 
The make and model is captured and compared to a predefinted dataset. 
If found, the listing information is saved into the MySQL database. 
Otherwise, the listing will be removed from all future cron tasks.
Images from the listings are saved server side.
<br>
Database Automation: As Craigslist listings expire, they are removed from the database. 
Listings are visited each day to check for activity, updates, and potentially deletion or expiration. 
Images are removed from the server when a listing expires.<br>
Since Craigslist users post the same listing for maximum coverage, the code will flag all potential duplicate listings. 
This is done through a set of checks on fields such as VIN, Mileage, and Distance.
<br>
<br>
Email Alerts: As these automated processes run, emails are sent to a designated email address. 
These include the number of new listings save and their respective cities, expired listings, and database size.
<hr>









