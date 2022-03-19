# QuickTrack

![Initial GIF](/frontend/assets/final.gif)

QuickTrack is a cross-platform GUI and CLI based tool that helps you to monitor your IP addresses, identify the services running on the open ports as well as help discover any security vulnerabilities in them. It consumes the <a href="https://internetdb.shodan.io/">InternetDB API</a> of Shodan for the scanning phase.

QuickTrack offers a web-based platform which you can try by visiting <a href="https://quicktrack.dev/">quicktrack.dev</a>. The website has 2 main functionalities, either the user can explicitly provide an IP address which they would like us to scan or let the platform fetch the user's IP address automatically. Apart from this, the user also has to provide us with an email address where QuickTrack can send an auto-generated report regarding the findings. The IP addresses and emails are automatically saved in our database and will be scanned every week to send fresh reports to the users.

Currently, we offer to monitor only IPv4 addresses and we scan for stuff such as:
 * Hostnames
 * Open Ports (Only top 1500)
 * Services Running on These Ports
 * Publicly Known Vulnerabilities Related to Service Versions
 * We also allocate tags to the target IP based on services identified

An auto-generated report which is sent to the user after the first scan and then every consecutive week, would look like <a href="./frontend/assets/report.PNG">this</a>.


# Setup
In order to run QuickTrack locally on your machine either in CLI or GUI mode, you should first meet these requirements:
* ## Golang
    In order to compile the tool/backend, you will have to make sure that you have Go installed on your system. You can head over to <a href="https://go.dev/doc/install">their website</a> for the same. At the time of development, our Go version was: <b>go1.13.8 linux/amd64</b>.

* ## PHP (Only for GUI / Website)
   Our web dashboard works on PHP, so you need to make sure that you have installed PHP on your local system or is used by your local web server before you serve the front-end. Setting up PHP on Linux is relatively easy (sudo apt install php), but for Windows and Mac OS you can refer to <a href="https://www.php.net/manual/en/install.php">their website</a>.

*  ## Web Server (Only for GUI / Website)
   The next thing you require is web server software for serving your frontend. For Linux, you can check out Apache (sudo apt install apache2) since we used it a **LOT** during the development phase. But for Windows and Mac OS, you can check out <a href="https://www.apachefriends.org/index.html">XAMPP</a>.

* ## SQL Database
   We require a database for saving the email and IP addresses for future scans. Make sure that you create a table having 2 columns which shall be:
    * Email [VARCHAR(50)]
    * IP [VARCHAR(16)]

* ## Email Account
   The next thing you are required to do is set up an email account through which the reports would be sent by the tool. We are using <a href="https://mail.google.com/">Gmail</a> for our website and so far it works perfectly!

# Config
The only file which would require manual configuration is <a href="./backend/main.go">main.go</a>.

* ## Edit 1
  You have to edit line no. 64, which contains the SMTP configuration for the email.

  Example Config:
  ```go
  d := gomail.NewDialer("smtp.gmail.com", 587, "johndoe@gmail.com", "secretpassword123")
  ```

* ## Edit 2
  You have to edit line no. 203 that stores the SQL DB configuration for the tool's database.

  Example Config:
  ```go
   db, err := sql.Open("mysql", "root:mydbpassword@tcp(127.0.0.1:3306)/mydatabase")
  ```
  
* ## Compiling
  And finally, you are supposed to compile the backend/tool! You can do so by navigating to the <a href="./backend/">backend directory</a> and running:
  ```sh
   go build
  ```
  and you will finally see a binary/executable file in the backend directory named quicktrack.

# CLI Mode
Once you have edited and compiled the code into a binary/executable, it becomes relatively easy to run QuickTrack in a CLI mode without having to configure any web-related stuff.

 * ## Platform  Support
 
  | Windows | Linux | Mac | Termux |
  | ------- | ----- | --- | ------ |
  | ✔ | ✔ | ✔ | ✔ |

  ✔ - Tested 
  ❔ - Not tested

  Right now we cover 2 scan modes for the users,
  * ## Individual Scan
    This is where you provide QuickTrack with an email address (to send the report to) and an IP address (the one to be scanned). This has to be done with the help of flags when running the binary/executable. 

    ### Usage
     * #### Windows
       ```sh
       ./quicktrack.exe -ip=<ip-address> -email=<email-address>

       Example:
       ./quicktrack.exe -ip=127.0.0.1 -email=johndoe@gmail.com
       ```
    * #### Linux / Mac
      ```
      ./quicktrack -ip=<ip-address> -email=<email-address>

      Example:
      ./quicktrack -ip=127.0.0.1 -email=johndoe@gmail.com
      ```

    <b>Note:</b> In both of the cases, the entries will be saved into the database that you had configured earlier.

  * ## Database-wide Scan
    This is where you let QuickTrack pull all the email and IP addresses from the configured database and run scans on all of the entries one by one. Reports will be sent as soon as the scan for the specific entry is finished.

    ### Usage
     * #### Windows
       ```sh
       ./quicktrack.exe -ip=full
       ```
    * #### Linux / Mac
      ```
      ./quicktrack -ip=full
      ```

# Willing to contribute?
You can make a contribution to this repo by creating a pull request! Make sure you include the following details:

* Feature introduced
* What does the feature do?
* List of External Modules/Packages if used

# Support:
You can reach us out at <a href="mailto:support@quicktrack.dev">support@quicktrack.dev</a> for any queries, complaints, sugestions/feedbacks etc.

# Quick Disclaimer
The information provided by QuickTrack on our website or through Github is for general informational purposes only. All information is provided in good faith, regarding the accuracy, adequacy, validity, reliability, availability or completeness of any information on the Site.
Under no circumstance shall we have any liability to you for any loss or damage of any kind incurred as a result of the use of the website/tool or reliance on any information provided on the aforementioned. Your use of the platform or tool and your reliance on any information on them is solely at your own risk. 
QuickTrack takes no responsibility for any intentional misuse of our products and will be willing to share any form of log data if required by the court of law or any law enforcement body.
