<?php
// Vikram Sunil Bajaj (vsb259), Ameya Shanbhag (avs431)
error_reporting(0);

// start a session
session_start();

// create connection
$conn = mysql_connect("localhost", "root", "") or die("Could not connect: " . mysql_error());

mysql_query("CREATE DATABASE IF NOT EXISTS nyu_student_network") or die(mysql_error());
mysql_select_db('nyu_student_network') or die(mysql_error());

mysql_query("SET SESSION explicit_defaults_for_timestamp=false");  // allows timestamp values to be null

// create tables
mysql_query("CREATE TABLE IF NOT EXISTS Student
(
 student_id INT NOT NULL AUTO_INCREMENT,
 student_username VARCHAR(20),
 password VARCHAR(20) NOT NULL,
 name VARCHAR(20),
 age INT,
 group_id_list JSON DEFAULT NULL,
 friends_list JSON DEFAULT NULL,
 gender VARCHAR(20),
 city VARCHAR(20),
 last_login_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
 last_logout_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
 profile_pic BLOB,
 liked_locations_list JSON,
 PRIMARY KEY(student_username),
 INDEX(student_id)
)") or die("Could not create table Student: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS Location
(
   location_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   location_name VARCHAR(1000) NOT NULL,
   latitude_longitude VARCHAR(1000) NOT NULL,
   like_counter INT
)") or die("Could not create table Location: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS Logs
(
   student_id INT,
   login_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   logout_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   location_id INT,
   FOREIGN KEY (student_id) REFERENCES Student(student_id),
   FOREIGN KEY (location_id) REFERENCES Location(location_id)
)") or die("Could not create table Logs: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS PrivacySetting
(
   privacy_id INT PRIMARY KEY NOT NULL,
   privacy_type VARCHAR(20) NOT NULL
)") or die("Could not create table PrivacySetting: " . mysql_error());

mysql_query("INSERT IGNORE INTO PrivacySetting VALUES (0, 'private')");
mysql_query("INSERT IGNORE INTO PrivacySetting VALUES (1, 'friends')");
mysql_query("INSERT IGNORE INTO PrivacySetting VALUES (2, 'fof')");
mysql_query("INSERT IGNORE INTO PrivacySetting VALUES (3, 'public')");

mysql_query("CREATE TABLE IF NOT EXISTS DiaryEntry
(
 name VARCHAR(20) NOT NULL ,
 diary_entry_id INT NOT NULL AUTO_INCREMENT,
 title VARCHAR(50),
 timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
 diary_entry_text TEXT,
 diary_entry_multimedia BLOB,
 privacy_id INT NOT NULL,
 location_name VARCHAR(1000),
 like_counter INT,
 dislike_counter INT,
 PRIMARY KEY (diary_entry_id),
 FOREIGN KEY (privacy_id) REFERENCES PrivacySetting(privacy_id)
)") or die("Could not create table DiaryEntry: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS Comments
(
   student_username VARCHAR(40) NOT NULL,
   diary_entry_id INT,
   comment_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
   comment_body VARCHAR(1000) NOT NULL,
   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
   FOREIGN KEY (diary_entry_id) REFERENCES DiaryEntry(diary_entry_id)
)") or die("Could not create table Comments: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS Friends
(
   student_name_1 VARCHAR(40) NOT NULL,
   student_name_2 VARCHAR(40) NOT NULL,
   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
   status VARCHAR(10) NOT NULL,
   PRIMARY KEY (student_name_1, student_name_2)
)") or die("Could not create table Friends: " . mysql_error());

//mysql_query("CREATE TABLE IF NOT EXISTS Activity
//(
//   from_student_id INT,
//   to_diary_entry_id INT,
//   activity_id INT PRIMARY KEY,
//   timestamp TIMESTAMP,
//   activity_type VARCHAR(20),
//   privacy_id INT,
//   FOREIGN KEY (from_student_id) REFERENCES Student(student_id),
//   FOREIGN KEY (privacy_id) REFERENCES PrivacySetting(privacy_id),
//   FOREIGN KEY (to_diary_entry_id) REFERENCES DiaryEntry(diary_entry_id)
//)") or die("Could not create table Activity: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS Activity
(
    activity_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    activity_detail VARCHAR(500),
    activity_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
)") or die("Could not create table Activity: " . mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS Groups
(
   group_id INT PRIMARY KEY NOT NULL,
   group_name VARCHAR(20) NOT NULL,
   student_id INT NOT NULL,
   members_list JSON,
   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
   privacy_id INT NOT NULL,
   FOREIGN KEY (student_id) REFERENCES Student(student_id),
   FOREIGN KEY (privacy_id) REFERENCES PrivacySetting(privacy_id)
)") or die("Could not create table Groups: " . mysql_error());

if (isset($_POST['action'])) {
    $username = $_POST['student_username'];
    $password = $_POST['student_password'];
    
    // check if username exists in Student
    $sql = "SELECT student_username FROM Student WHERE student_username='$username'";
    $result = mysql_query($sql) or die(mysql_error());

    if(mysql_num_rows($result)>0){
        // student_username exists
        $r = mysql_query("SELECT * FROM Student WHERE student_username='$username' AND password='$password'");
        if (mysql_num_rows($r)==1){
            // matching username and password exists
            //echo "<script>alert('Login Successful!')</script>";
            $_SESSION["student_username"] = $username;
//            $session_user = $_SESSION["student_username"];
//            echo "<script>alert('$session_user')</script>";
            header("Location: home.php");
        }
        else {
            // invalid password
            echo "<script>alert('Invalid Password!')</script>";
        }
    }else{
        // student_username does not exist in Student
        echo "<script>alert('Invalid Username!')</script>";
    }
}
?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Login</title>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <style>
            .btn {
                background: #FFFFFF;
                color: #502580;
            }

            .nav-wrapper {
                background: #502580;
            }

            .nav-wrapper a img {
                max-width: 100%;
                max-height: 100%;
            }

            body {
                background: url(images/nyc.jpg) no-repeat center top fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

            i {
                color: #502580;
            }

            i:focus {
                color: #8901E1;
            }

        </style>

        <!-- favicons -->
        <link rel="apple-touch-icon" sizes="57x57" href="icons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="icons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="icons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="icons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="icons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="icons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="icons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="icons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="icons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="icons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
        <link rel="manifest" href="icons/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="icons/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Tab Color -->
        <!-- Chrome, Firefox OS and Opera -->
        <meta name="theme-color" content="#502580">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#502580">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    </head>

    <body>
        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>

        <nav>
            <div class="nav-wrapper">
                <a href="#"><img src="images/logo.png" alt="Logo"></a>
                <a href="#" class="brand-logo center">NYU Network</a>
                <a href="#" data-activates="slide-out" class="button-collapse right"><i class="material-icons" style="color: #FFFFFF;">menu</i></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="signup.php" class="waves-effect waves-light btn"><i class="material-icons right">person_add</i>Sign Up</a></li>
                </ul>
                <ul id="slide-out" class="side-nav">
                    <li><a href="signup.php" class="waves-effect waves-light btn" style="color: #502580;"><i class="material-icons right" style="color: #502580;">person_add</i>Sign Up</a></li>
                </ul>
            </div>
        </nav>
        <main>
            <div class="row">
                <div class="col s12 m6 l6 push-m3 push-l3">
                    <div class="card hoverable">
                        <div class="card-content">
                            <div class="row">
                                <form class="col s12" method="post" action="">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">person_outline</i>
                                            <input id="student_username" name="student_username" type="text" required="required">
                                            <label for="student_username" data-error="Invalid">Username</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">vpn_key</i>
                                            <input id="student_password" name="student_password" type="password" required="required">
                                            <label for="student_password">Password</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <button class="btn waves-effect waves-light right" type="submit" name="action">Log In<i class="material-icons right">lock_open</i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".button-collapse").sideNav({
                    menuWidth: 240,
                    edge: 'right',
                    closeOnClick: true,
                    draggable: true
                });
            });

        </script>
    </body>

    </html>
