<?php
/*Main page*/
session_start();
require 'configure.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

        <style>
        body {
            background-image: url("milkcar.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .center {
            position: absolute;
            height: 200px;
            width: 400px;
            margin: -100px 0 0 -200px;
            top: 50%;
            left: 50%;
        }
        </style>

        <title>Login Form</title>
    </head>

    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST['login'])) { //user logging in
            require 'user_login.php';
        }
    }
    ?>

    <body>
        <div class="center">
        <div class="w3-panel w3-round-xlarge w3-padding-24">

            <div class="form-group">

                <div id="login">   

                    <form action="user_input_form.php" method="post" autocomplete="off">

                        <div class="field-wrap">
                            <label>
                                <span class="req"></span>
                            </label>
                            <input type="text" required autocomplete="off" class="form-control form-control-lg" placeholder="Enter School ID" name="schoolID"/>
                        </div>
                        <br>
                        <button class="btn btn-primary btn-lg btn-block" name="login" style="display: block; margin: 0 auto;"/>Log In</button>
                        
                    </form>

                </div>
            </div>
        </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>
