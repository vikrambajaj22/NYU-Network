<?php
// Vikram Sunil Bajaj (vsb259), Ameya Shanbhag (avs431)
error_reporting(0);

session_start(); // to ensure that you are using the same session
$current_username = $_SESSION["student_username"];

// get student details from student table
// create connection
$conn = mysql_connect("localhost", "root", "") or die("Could not connect: " . mysql_error());

mysql_select_db('nyu_student_network') or die(mysql_error());

mysql_query("SET SESSION explicit_defaults_for_timestamp=false");  // allows timestamp values to be null

$get_student_name=mysql_query("SELECT name FROM student WHERE student_username='$current_username'");

if (isset($get_student_name)){
    if(mysql_num_rows($get_student_name)>0){
        while($row = mysql_fetch_assoc($get_student_name)){
            $current_user = $row['name'];
            }
        }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Search</title>
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

            i {
                color: #502580;
            }

            i:focus {
                color: #8901E1;
            }

            .tabs {
                overflow-x: hidden;
            }

            #profile-pic-container:hover .pic-overlay {
                opacity: .9;
                transition: opacity .5s;
            }

            /* for Google Map */

            #mapCanvas {
                width: 500px;
                height: 400px;

            }

            #infoPanel {
                margin-left: 10px;
            }

            #infoPanel div {
                margin-bottom: 5px;
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
                <a href="home.php" class="brand-logo center hide-on-med-and-down">NYU Network</a>
                <a href="#" data-activates="slide-out" class="button-collapse right"><i class="material-icons" style="color: #FFFFFF;">menu</i></a>
                <ul id="nav-mobile" class="right">
                    <li>
                        <form action="search.php" method="post">
                            <div class="input-field" style="position: relative">
                                <input id="search" name="keywords" type="search" placeholder="Search..." style="background:white; height:36px; margin-top:15px; padding-left: 5px; color:#502580;" required>
                                <button class="btn waves-effect waves-light" type="submit" name="action" style="position:absolute; top:0px; right:-4px; width:10px;"><i class="material-icons" style="position: relative; bottom:12px; right:12px; margin-left:0;">search</i></button>
                            </div>
                        </form>
                    </li>
                    <li class="hide-on-med-and-down"><a href="logout.php" class="waves-effect waves-light btn"><i class="material-icons right">last_page</i>Log Out</a></li>
                </ul>
                <ul id="slide-out" class="side-nav">
                    <li><a href="logout.php" class="waves-effect waves-light btn" style="color: #502580;"><i class="material-icons right" style="color: #502580;">last_page</i>Log Out</a></li>
                </ul>
            </div>

            <div class="nav-content" style="background: #5c2b94">
                <ul class="tabs tabs-transparent">
                    <li class="tab" style="width: 50%"><a href="#posts_tab">Posts</a></li>
                    <li class="tab" style="width: 50%"><a href="#students_tab">Students</a></li>
                </ul>
            </div>

        </nav>

        <main>

            <div id="posts_tab" style="margin-top: 75px;">
                <?php
if (isset($_POST['keywords'])){
    $keywords = mysql_real_escape_string($_POST['keywords']);
    $query = mysql_query("SELECT * FROM diaryentry WHERE diary_entry_text LIKE '%{$keywords}%' OR title LIKE '%{$keywords}%' AND privacy_id>0 ORDER BY timestamp DESC");

    if(isset($query)){
        if(mysql_num_rows($query)){
            while ($row = mysql_fetch_assoc($query)){
                // get friends list and check if post author is in friends list of current user
                $sql = "SELECT JSON_SEARCH(friends_list, 'one', '".$row['name']."') as if_exists FROM Student WHERE name='".$current_user."'";
                $temp = mysql_query($sql);
                
                $temp2 = "";
                while($row1 = mysql_fetch_assoc($temp)){
                    $temp2 = $row1['if_exists'];
                }
                
                if (isset($temp2)==1 || $row['name']==$current_user) {// post author in current users friend list or author is the current user
                    echo '
                    <div class="row">
                        <div class="col s12 m6 l6 push-m3 push-l3">
                        
                            <div class="card horizontal">
                                <div class="card-image waves-effect waves-block waves-light">
                                    <img class="activator" src="'.$row['diary_entry_multimedia'].'" style="height: auto;width: 150px">
                                </div>
                                
                                <div class="card-content">
                                    <span class="card-title activator grey-text text-darken-4">'.$row['title'].'</span>at <b>'.$row['location_name'].'</b><br><span style="opacity: 0.4">'.$row['timestamp'].'</span>
                                    <p style="opacity: 0.7"><b>Posted By</b>: <span style="opacity: 1"><b>'.$row['name'].'</b></span></p>
                                    </div>
                                <div class="card-reveal">
                                    <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i>'.$row['title'].'</span>
                                    <p>'.$row['diary_entry_text'].'</p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                } } } } ?>
            </div>

            <div id="students_tab" style="margin-top: 75px;">
                <?php
    if (isset($_POST['keywords'])){
        $keywords = mysql_real_escape_string($_POST['keywords']);
        $query = mysql_query("SELECT * FROM Student WHERE name LIKE '%{$keywords}%'");

    if(isset($query)){
        if(mysql_num_rows($query)){
            while ($row = mysql_fetch_assoc($query)){
                $sql = "SELECT JSON_SEARCH(friends_list, 'one', '".$row['name']."') as if_exists FROM Student WHERE name='".$current_user."'";
                $temp = mysql_query($sql);
                
                $temp2 = "";
                while($row1 = mysql_fetch_assoc($temp)){
                    $temp2 = $row1['if_exists'];
                }
                
                //echo isset($temp2);
                
                // dont show add_friend floating button for friends or self
                if (isset($temp2)==1 || $row['name']=="$current_user"){
                
                echo '
                    <div class="row">
                        <div class="col s12 m6 l6 push-m3 push-l3">
                        
                            <div class="card horizontal">
                                <div class="card-image waves-effect waves-block waves-light">
                                    <img class="activator" src="'.$row['profile_pic'].'" style="height: auto;width: 150px">
                                </div>
                                <div class="card-content">
                                    <span class="card-title activator grey-text text-darken-4">'.$row['name'].'</span>
                                    <p style="opacity: 0.7"></p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                
                // if he is not a friend then show floating button
                else{
                
                echo '
                    <div class="row">
                        <div class="col s12 m6 l6 push-m3 push-l3">
                        
                            <div class="card horizontal">
                                <div class="card-image waves-effect waves-block waves-light">
                                    <img class="activator" src="'.$row['profile_pic'].'" style="height: auto;width: 150px">
                                </div>
                                <form method="post" action="">
                                <button class="btn-floating halfway-fab waves-effect waves-light red" type="submit" name="add_friend" value = "'.$row['name'].'" onclick="setFriendName(this.value,this.form)"><i class="material-icons">person_add</i></button>
                                <input type="hidden" name = "friend_name" id = "friend_name">
                                </form>
                                
                                
                                <div class="card-content">
                                    <span class="card-title activator grey-text text-darken-4">'.$row['name'].'</span>
                                    <p style="opacity: 0.7"></p>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }            
                    
                } } }
            }
            ?>
            </div>
        </main>
        <?php
        if(isset($_POST["add_friend"])){
            $friend = $_POST["friend_name"];
            mysql_query("INSERT INTO Friends VALUES ('$current_user', '$friend', NULL, 'Pending')")or die(mysql_error());
            echo "<script>alert('Friend Request Sent!');window.location.href='home.php'</script>";
        }
        ?>
            <script>
                function setFriendName(button_value, form_data) {

                    form_data.friend_name.value = button_value;

                }

            </script>
    </body>

    </html>
