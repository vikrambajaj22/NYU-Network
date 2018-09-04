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

$sql = "SELECT name, age, group_id_list, friends_list, gender, city, profile_pic FROM Student WHERE student_username='$current_username'";
$student_details = mysql_query($sql) or die(mysql_error());

$get_student_id=mysql_query("SELECT student_id FROM student WHERE student_username='$current_username'");

if (isset($get_student_id)){
    if(mysql_num_rows($get_student_id)>0){
        while($row = mysql_fetch_assoc($get_student_id)){
            $temp = $row['student_id'];
            }
        }
    }

$get_student_name=mysql_query("SELECT name FROM student WHERE student_username='$current_username'");

if (isset($get_student_name)){
    if(mysql_num_rows($get_student_name)>0){
        while($row = mysql_fetch_assoc($get_student_name)){
            $current_user = $row['name'];
            }
        }
    }

$retrieve_posts = mysql_query("SELECT * FROM diaryentry WHERE name='$current_user' ORDER BY timestamp DESC");

// profile updation
if (isset($_POST['update_profile'])) {
    //$new_name = $_POST['student_name'];
    $new_password = $_POST['student_password'];
    $new_age = $_POST['student_age'];
    $new_city = $_POST['student_city'];
    
    if ($new_name==="" && $new_password==="" && $new_age==="" && $new_city===""){
        echo "<script>alert('Update at least one field!');</script>";
    }
    
//    if ($new_name!=""){
//        mysql_query("UPDATE student SET name='$new_name' WHERE student_username='$current_username'");
//        $current_user = $new_name;
//    }
    
    if ($new_password!=""){
        mysql_query("UPDATE student SET password='$new_password' WHERE student_username='$current_username'");
    }
    
    if ($new_age!=""){
        if ($new_age<=0) {
            echo "<script>alert('Age must be a positive integer!');</script>";
        }
        else{
            mysql_query("UPDATE student SET age='$new_age' WHERE student_username='$current_username'");
        }
    }
    
    if ($new_city!=""){
        mysql_query("UPDATE student SET city='$new_city' WHERE student_username='$current_username'");
    }
    
    // refresh page
    echo "<script>window.location.href = 'home.php';</script>";
}
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Home</title>
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
        <!-- for the Google Map -->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhLEzlhoJvU1bTLjjFcbOQHRhPj2HMckY&libraries=places"></script>
        <script>
            function init() {
                var input = document.getElementById('locationTextField');
                var autocomplete = new google.maps.places.Autocomplete(input);
            }

            google.maps.event.addDomListener(window, 'load', init);

        </script>


        <script type="text/javascript">
            var geocoder = new google.maps.Geocoder();

            function geocodePosition(pos) {
                geocoder.geocode({
                    latLng: pos
                }, function(responses) {
                    if (responses && responses.length > 0) {
                        updateMarkerAddress(responses[0].formatted_address);
                    } else {
                        updateMarkerAddress('Cannot determine address at this location.');
                    }
                });
            }

            function updateMarkerStatus(str) {
                //document.getElementById('markerStatus').innerHTML = str;
            }

            function updateMarkerPosition(latLng) {
                //                document.getElementById('info').innerHTML = [
                //                    latLng.lat(),
                //                    latLng.lng()
                //                ].join(', ');
                document.getElementById('info').value = [
                    latLng.lat(),
                    latLng.lng()
                ].join(', ');
            }

            function updateMarkerAddress(str) {
                //document.getElementById('address').innerHTML = str;
                document.getElementById('address').value = str;
            }

            function initialize() {
                var latLng = new google.maps.LatLng(40.7295, -73.9965);
                var map = new google.maps.Map(document.getElementById('mapCanvas'), {
                    zoom: 8,
                    center: latLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var marker = new google.maps.Marker({
                    position: latLng,
                    title: 'Point A',
                    map: map,
                    draggable: true
                });

                // Update current position info.
                updateMarkerPosition(latLng);
                geocodePosition(latLng);

                // Add dragging event listeners.
                google.maps.event.addListener(marker, 'dragstart', function() {
                    updateMarkerAddress('Dragging...');
                });

                google.maps.event.addListener(marker, 'drag', function() {
                    updateMarkerStatus('Dragging...');
                    updateMarkerPosition(marker.getPosition());
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    updateMarkerStatus('Drag ended');
                    geocodePosition(marker.getPosition());
                });
            }

            // Onload handler to fire off the app.
            google.maps.event.addDomListener(window, 'load', initialize);

        </script>
        <!-- end of Google Map Script -->


        <nav>
            <div class="nav-wrapper">
                <a href="#"><img src="images/logo.png" alt="Logo"></a>
                <a href="home.php" class="brand-logo center hide-on-med-and-down">NYU Network</a>
                <a href="#" data-activates="slide-out" class="button-collapse right"><i class="material-icons" style="color: #FFFFFF;">menu</i></a>
                <ul id="nav-mobile" class="right">
                    <li>
                        <form method="post" action="search.php">
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
                    <li class="tab" style="width: 50%"><a href="#profile_tab">Profile</a></li>
                    <li class="tab" style="width: 50%"><a href="#timeline_tab">Timeline</a></li>
                </ul>
            </div>
        </nav>
        <main>
            <div id="profile_tab" style="margin-top: 75px;">
                <div class="row">
                    <div class="col s12 m6 l6 push-m3 push-l3">
                        <div class="card hoverable">
                            <div class="card-image" id="profile-pic-container" style="position: relative">
                                <?php
                                    if (isset($student_details)) {
                                        while($row=mysql_fetch_assoc($student_details)){
                                            echo "<img src='".$row['profile_pic']."' style='height:150px; width:150px; display : block; margin : auto;'>";
                                            echo "<div class='pic-overlay' style='opacity: 1; text-align: center'><a href='#newpicmodal' class='waves-effect waves-light btn modal-trigger'><i class='material-icons center'>edit</i></a></div>";
                                            echo "</div>";
                                            echo "<div class='card-content'>";
                                            echo "<h5 style='text-align: center'>".$row['name']."</h5>";
                                            echo "<p style='text-align: center'>".ucfirst($row['gender'])." | ".$row['age']." | ".$row['city']."</p>";
                                            echo "<b>Friends</b>: ".$row['friends_list'];
//                                            echo "<br>";
//                                            echo "<b>Groups</b>: ".$row['group_id_list'];
                                         }
                                    }
                                    ?>
                                    <!-- New Pic Modal Structure -->
                                    <div id="newpicmodal" class="modal">
                                        <div class="modal-footer">
                                            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons right">close</i></a>
                                        </div>
                                        <div class="modal-content">
                                            <h5>New Pic</h5>
                                            <p><b>Note</b>: Image must be in the images folder.</p>
                                            <div class="row">
                                                <form class="col s12" method="post" action="" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <input id="new_pic" name="new_pic" type="file" accept="image/*">
                                                        </div>
                                                        <div class="row">
                                                            <button class="btn waves-effect waves-light right" type="submit" value="Upload" name="update_pic"><i class="material-icons right">file_upload</i>Upload</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- end pic update modal -->
                            <?php
                            if (isset($_POST["update_pic"])){
                                // Get image name
  	                            $image = $_FILES['new_pic']['name'];
                                // image file directory
  	                            $target = "images/".basename($image);
                                
                                // check if file is an image
                                $check = getimagesize($_FILES["new_pic"]["tmp_name"]);
                                if($check !== false) {
                                    // it is an image
                                    mysql_query("UPDATE student SET profile_pic='$target' WHERE student_username='$current_username'");
                                    // add to activity
                                    mysql_query("INSERT INTO Activity VALUES(null, 'Profile pic of $current_user was updated', null)");
                                    
                                    // refresh page
                                        echo "<script>window.location.href = 'home.php';</script>";
                                }
                                else {
                                    echo "<script>alert('File uploaded was not an image!');</script>";
                                }
                            }
                            ?>
                        </div>
                        <div class="card-content">
                            <a href="#profileupdatemodal" class="waves-effect waves-light btn modal-trigger"><i class="material-icons right">person</i>Update Profile</a>
                            <!-- Profile Update Modal Structure -->
                            <div id="profileupdatemodal" class="modal">
                                <div class="modal-footer">
                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons right">close</i></a>
                                </div>
                                <div class="modal-content">
                                    <h5>Update Profile Details</h5>
                                    <div class="row">
                                        <form class="col s12" method="post" action="">
                                            <!--
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <i class="material-icons prefix">person</i>
                                                    <input id="student_name" name="student_name" type="text">
                                                    <label for="student_name" data-error="Invalid">Name</label>
                                                </div>
                                            </div>
-->
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <i class="material-icons prefix">vpn_key</i>
                                                    <input id="student_password" name="student_password" type="password">
                                                    <label for="student_password">Password</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <i class="material-icons prefix">date_range</i>
                                                    <input id="student_age" name="student_age" type="number">
                                                    <label for="student_age">Age</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <i class="material-icons prefix">public</i>
                                                    <input id="student_city" name="student_city" type="text">
                                                    <label for="student_city">City</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn waves-effect waves-light right" type="submit" name="update_profile">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end profile update modal -->

                            <a href="#friendmodal" class="waves-effect waves-light btn modal-trigger center" style="margin-left:70px;"><i class="material-icons right">person</i>Friends</a>
                            <!-- friend modal -->
                            <div id="friendmodal" class="modal">
                                <nav>
                                    <div class="modal-footer">
                                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons right">close</i></a>
                                    </div>
                                    <div class="nav-content" style="background: #5c2b94">
                                        <ul class="tabs tabs-transparent">
                                            <li class="tab" style="width: 50%"><a href="#accepted_tab">Friends</a></li>
                                            <li class="tab" style="width: 50%"><a href="#pending_tab">Pending Requests</a></li>
                                        </ul>
                                    </div>
                                </nav>

                                <div id="accepted_tab" style="margin-top: 75px;">
                                    <?php
                                    
                                    $get_all = mysql_query("SELECT JSON_EXTRACT(friends_list,'$') as q FROM Student WHERE name = '$current_user'");
                                    
                                    while($row=mysql_fetch_array($get_all)){
                                        echo $row['q'];
                                    }
                                    ?>

                                </div>

                                <div id="pending_tab" style="margin-top: 75px;">

                                    <?php
                                        $get_all_friend = mysql_query("SELECT * FROM Friends");
                                        if (mysql_num_rows($get_all_friend)>0){
                                            while($row = mysql_fetch_assoc($get_all_friend)){
                                                if($row['status']=="Pending" && $row['student_name_2']=="$current_user"){ 
                                                    echo '
                                                    <div class="row" id="'.$row['student_name_1'].'">
                                                        <div class="col s12 m6 l6 push-m3 push-l3">

                                                            <div class="card">
                            
                                                                <form method="post" action="">
                                                                
                                                                <button class="btn-floating halfway-fab waves-effect waves-light red" type="submit" name="decline_friend" value = "'.$row['student_name_1'].'" onclick="declineFriend(this.value,this.form)" style="margin:left;"><i class="material-icons">clear</i>
                                                                </button>
                                                            
                                                                
                                                                <button class="btn-floating halfway-fab waves-effect waves-light green" type="submit" name="add_friend" value = "'.$row['student_name_1'].'" onclick="setFriendName(this.value,this.form)" style="margin-right:75px;"><i class="material-icons">check</i>
                                                                </button>
                                                                
                                                                <input type="hidden" name = "friend_name" id = "friend_name">
                                                                
                                                                </form>

                                                                <div class="card-content">
                                                                    <span class="card-title activator grey-text text-darken-4">'.$row['student_name_1'].'</span>
                                                                    <p style="opacity: 0.7"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';  
                                                
                                                }
                                            }    
                                        
                                        }
                                    ?>
                                </div>
                            </div>
                            <!-- end friend modal -->
                            <?php

                                if(isset($_POST["add_friend"])){

                                     $friend = $_POST["friend_name"];
                                     mysql_query("UPDATE Friends SET status='Accepted' WHERE student_name_1='$friend' AND student_name_2='$current_user'") or die(mysql_error());
                                    
                                    mysql_query("UPDATE Student SET friends_list= JSON_ARRAY_APPEND(friends_list, '$', '$friend') WHERE name='$current_user' ");
                                    mysql_query("UPDATE Student SET friends_list= JSON_ARRAY_APPEND(friends_list, '$', '$current_user') WHERE name='$friend' ");
                                    
                                    mysql_query("INSERT INTO Activity VALUES (null, '$friend and $current_user are now friends!', null)");
                                    
                                    // refresh page
                                    echo "<script>window.location.href = 'home.php';</script>";
                                }
                            
                                if(isset($_POST["decline_friend"])){

                                         $friend = $_POST["friend_name"];
                                         mysql_query("UPDATE Friends SET status='Declined' WHERE student_name_1='$friend' AND student_name_2='$current_user'") or die(mysql_error());

                                        // refresh page
                                        echo "<script>window.location.href = 'home.php';</script>";
                                    }
                            ?>

                                <script>
                                    function setFriendName(button_value, form_data) {

                                        form_data.friend_name.value = button_value;

                                        // set display: none for row
                                        var e = document.getElementById(button_value);
                                        e.style.display = "none";
                                    }

                                    function declineFriend(button_value, form_data) {

                                        form_data.friend_name.value = button_value;

                                        // set display: none for row
                                        var e = document.getElementById(button_value);
                                        e.style.display = "none";
                                    }

                                </script>

                                <a href="#newentrymodal" class="waves-effect waves-light btn modal-trigger right"><i class="material-icons right">create</i>New Post</a>
                                <!-- New Entry Modal Structure -->
                                <div id="newentrymodal" class="modal">
                                    <div class="modal-footer">
                                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons right">close</i></a>
                                    </div>
                                    <div class="modal-content">
                                        <h5>New Post</h5>
                                        <div class="row">
                                            <form class="col s12" method="post" action="" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="post_title" name="post_title" type="text">
                                                        <label for="post_title" data-error="Invalid">Post Title</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <textarea id="post_content" name="post_content" class="materialize-textarea"></textarea>
                                                        <label for="post_content">Post</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <div id="mapCanvas"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="info" name="info" type="text">
                                                        <label for="info" data-error="Invalid">Current Marker Position</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="address" name="addr" type="text">
                                                        <label for="address" data-error="Invalid">Address</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="privacy_option">
                                                      <option value="0">Private</option>
                                                      <option value="1">Visible to Friends</option>
                                                      <option value="2">Visible to FOF</option>
                                                      <option value="3">Public</option>
                                                    </select>
                                                        <label>Privacy</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <b>Add an Image to your post:</b>
                                                    <div class="input-field col s12">
                                                        <input id="post_pic" name="post_pic" type="file" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <button class="btn waves-effect waves-light right" type="submit" name="new_post">Post</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end new entry modal -->
                                <?php
                                if (isset($_POST["new_post"])){
                                    // Get image name
                                    $image = $_FILES["post_pic"]["name"];
                                    // image file directory
                                    $target = "images/".basename($image);
                                    // check if file is an image
                                    $check = getimagesize($_FILES["post_pic"]["tmp_name"]);
                                    if($check !== false) {
                                        // it is an image
                                        $title = $_POST["post_title"];
                                        $content = htmlspecialchars($_POST["post_content"]); // must not contain '
                                        $privacy = $_POST["privacy_option"];
                                        $location = $_POST["addr"];
                                        $latlong = $_POST["info"];
                                        
                                        mysql_query("INSERT INTO Location VALUES (null, '$location', '$latlong', 0)") or die(mysql_error());
                                        mysql_query("INSERT INTO DiaryEntry VALUES ('$current_user', null, '$title', null, '$content', '$target', $privacy, '$location', 0, 0)") or die(mysql_error());
                                        
                                        // insert into activity
                                        mysql_query("INSERT INTO Activity VALUES (null, '$current_user added a new post', null)");
                                        mysql_query("INSERT INTO Activity VALUES (null, '$current_user checked into $location', null)");
                                        // refresh page
                                        echo "<script>window.location.href = 'home.php';</script>";
                                    }
                                    else {
                                        echo "<script>alert('File uploaded was not an image!');</script>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <?php 
                if (isset($retrieve_posts)){
                    if (mysql_num_rows($retrieve_posts)>0){
                        while($row = mysql_fetch_assoc($retrieve_posts)){
                            echo "<div class='row'>";
                            echo "<div class='col s12 m6 l6 push-m3 push-l3'>";
                            echo "<div class='card horizontal white hoverable'>";
                            echo "<div class = 'card-image'>";
                            //echo "<img src='data:image/jpeg;base64,".base64_encode($row['diary_entry_multimedia'])."' style='width: 150px; height: auto'>";
                            echo "<img src='".$row['diary_entry_multimedia']."' style='width: 150px; height: auto'>";
                            echo "</div>";
                            echo "<div class='card-content black-text'>";
                            echo "<span class='card-title' style='display: inline-block; padding-right:5px'>".$row['title']."</span> at <b>".$row['location_name']."</b><br><span style='opacity: 0.4'>".$row['timestamp']."</span>";
                            echo "<p>".$row['diary_entry_text']."</p>";
                            echo"<br><b>Likes</b>: ".strval($row['like_counter']);
                            echo"<br><b>Dislikes</b>: ".strval($row['dislike_counter']);
                            echo "<br><br>";

                            echo "<form method='post' style='float: left'><button class='btn waves-effect waves-light' onclick='set_like_hidden_fields(this.value, this.form);' type='submit' name='like' value='".strval($row['diary_entry_id'])."'><i class='material-icons center'>thumb_up</i></button><input type='hidden' name='liked_post_id' id='liked_post_id'><input type='hidden' id='posted_by' name='posted_by' value='{$row['name']}'></form>";

                            //echo "<button style='float: right' class='btn waves-effect waves-light' onclick='open_comment_modal(this.value);' name='comment' value='".strval($row['diary_entry_id'])."'><i class='material-icons center'>comment</i></button><input type='hidden' name='comment_post_id' id='comment_post_id'>";

                            echo "<form method='post'><button class='btn waves-effect waves-light' onclick='set_dislike_hidden_fields(this.value, this.form);' type='submit' name='dislike' value='".strval($row['diary_entry_id'])."'><i class='material-icons center'>thumb_down</i></button><input type='hidden' name='disliked_post_id' id='disliked_post_id'><input type='hidden' id='posted_by' name='posted_by' value='{$row['name']}'></form>";

                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        }
                        }
                        ?>
                <!-- new comment modal -->
                <!--<div id="newcommentmodal" class="modal">
                    <div class="modal-footer">
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons right">close</i></a>
                    </div>
                    <div class="modal-content">
<?php
//                            $var = $_POST['comment_post_id'];
//                            echo $var;
//                            $get_comment = mysqli_query($conn,"SELECT student_id,comment_body FROM Comments WHERE diary_entry_id=".$var);
// if(isset($_POST['comment_post_id'])){echo "yay";}
//                                   echo $var;
//
//                               if(mysqli_num_rows($get_comment)>0){
//                                    while($row = mysqli_fetch_assoc($get_comment)){
//                                        echo $row["comment_body"];
//                                    }
//                                }
                        ?>
                        <h5>New Comment</h5>
                        <div class="row">
                            <form class="col s12" method="post" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea id="post_content" name="comment_content" class="materialize-textarea"></textarea>
                                        <label for="post_content">Write Comment</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <button class="btn waves-effect waves-light right" type="submit" name="new_comment">Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>-->
                <!-- end new comment modal -->
                <?php
                if (isset($_POST["like"])) {
                    $post_id = intval($_POST["liked_post_id"]);
                    $posted_by = $_POST["posted_by"];
                    //echo "<script>alert('Post ID: $post_id')</script>";
                    mysql_query("UPDATE DiaryEntry SET like_counter=like_counter+1 WHERE diary_entry_id=$post_id");
                    mysql_query("INSERT INTO Activity VALUES (null, '$current_user liked $posted_by\'s post', null)");
                    // refresh page
                    echo "<script>window.location.href = 'home.php';</script>";
                }
                
                if (isset($_POST["dislike"])) {
                    $post_id = intval($_POST["disliked_post_id"]);
                    $posted_by = $_POST["posted_by"];
                    //echo "<script>alert('Post ID: $post_id')</script>";
                    mysql_query("UPDATE DiaryEntry SET dislike_counter=dislike_counter+1 WHERE diary_entry_id=$post_id");
                    mysql_query("INSERT INTO Activity VALUES (null, '$current_user disliked $posted_by\'s post', null)");
                    // refresh page
                    echo "<script>window.location.href = 'home.php';</script>";
                }
                ?>
            </div>
            <div id="timeline_tab" style="margin-top: 75px;">
                <div class="row">
                    <!-- all posts -->
                    <div class="col s12 m6 l6">
                        <div class="card hoverable">
                            <div class="card-content">
                                <span class="card-title">Posts</span>
                                <?php
                $all_posts = mysql_query("SELECT * FROM DiaryEntry WHERE privacy_id>0 ORDER BY timestamp DESC") or die(mysql_error());
                if (isset($all_posts)){
                    if (mysql_num_rows($all_posts)>0){
                        while($row = mysql_fetch_assoc($all_posts)){
                            // get friends list and check if post author is in friends list of current user
                            $sql = "SELECT JSON_SEARCH(friends_list, 'one', '".$row['name']."') as if_exists FROM Student WHERE name='".$current_user."'";
                            $temp = mysql_query($sql);
                            $temp2 = "";
                            while($row1 = mysql_fetch_assoc($temp)){
                                $temp2 = $row1['if_exists'];
                            }

                            if (isset($temp2)==1 || $row['name']==$current_user) {// post author in current users friend list or author is the current user
                                echo "<div class='row'>";
                                echo "<div class='col s12 m12 l12'>";
                                echo "<div class='card horizontal white hoverable'>";
                                echo "<div class = 'card-image'>";
                                //echo "<img src='data:image/jpeg;base64,".base64_encode($row['diary_entry_multimedia'])."' style='width: 150px; height: auto'>";
                                echo "<img src='".$row['diary_entry_multimedia']."' style='width: 150px; height: auto'>";
                                echo "</div>";
                                echo "<div class='card-content black-text'>";
                                echo "<span class='card-title' style='display: inline-block; padding-right:5px'>".$row['title']."</span> at <b>".$row['location_name']."</b><br><p style='opacity: 0.7'><b>Posted By</b>: <span style='opacity: 1'><b>{$row['name']}</b></span></p><span style='opacity: 0.4'>".$row['timestamp']."</span>";
                                echo "<p>".$row['diary_entry_text']."</p>";
                                echo"<br><b>Likes</b>: ".strval($row['like_counter']);
                                echo"<br><b>Dislikes</b>: ".strval($row['dislike_counter']);
                                echo "<br><br>";

                                echo "<form method='post' style='float: left'><button class='btn waves-effect waves-light' onclick='set_like_hidden_fields(this.value, this.form);' type='submit' name='like' value='".strval($row['diary_entry_id'])."'><i class='material-icons center'>thumb_up</i></button><input type='hidden' name='liked_post_id' id='liked_post_id'><input type='hidden' id='posted_by' name='posted_by' value='{$row['name']}'></form>";

                                //echo "<button style='float: right' class='btn waves-effect waves-light' onclick='open_comment_modal(this.value);' name='comment' value='".strval($row['diary_entry_id'])."'><i class='material-icons center'>comment</i></button><input type='hidden' name='comment_post_id' id='comment_post_id'>";

                                echo "<form method='post'><button class='btn waves-effect waves-light' onclick='set_dislike_hidden_fields(this.value, this.form);' type='submit' name='dislike' value='".strval($row['diary_entry_id'])."'><i class='material-icons center'>thumb_down</i></button><input type='hidden' name='disliked_post_id' id='disliked_post_id'><input type='hidden' id='posted_by' name='posted_by' value='{$row['name']}'></form>";

                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                        }
                }
                ?>
                            </div>
                        </div>
                    </div>
                    <!-- activities -->
                    <div class="col s12 m6 l6">
                        <div class="card hoverable">
                            <div class="card-content">
                                <span class="card-title">Activities</span>
                                <?php
                $activities = mysql_query("SELECT * FROM Activity ORDER BY activity_timestamp DESC") or die(mysql_error());
                if (isset($activities)){
                    if (mysql_num_rows($activities)>0){
                        while($row = mysql_fetch_assoc($activities)){
                                echo "<div class='row'>";
                                echo "<div class='col s12 m12 l12'>";
                                echo "<div class='card hoverable'>";
                                echo "<div class='card-content black-text'>";
                                echo "{$row['activity_detail']}<br>";
                                echo "<span style='opacity: 0.4'>".$row['activity_timestamp']."</span>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                        }
                        }
                }
                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script type=" text/javascript ">
            $(document).ready(function() {
                $(".button-collapse ").sideNav({
                    menuWidth: 240,
                    edge: 'right',
                    closeOnClick: true,
                    draggable: true
                });
                $('.modal').modal();
                $('select').material_select();
            });

        </script>

        <script>
            function set_like_hidden_fields(button_value, form_data) {
                form_data.liked_post_id.value = button_value;
            }

            function set_dislike_hidden_fields(button_value, form_data) {
                form_data.disliked_post_id.value = button_value;
            }
            //            function open_comment_modal(button_value) {
            //                document.getElementById('comment_post_id').value = button_value;
            //                alert(document.getElementById('comment_post_id').value);
            //                $('#newcommentmodal').modal('open');
            //            }

        </script>
    </body>

    </html>
