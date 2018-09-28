<?php

    session_start();
    session_destroy();
    header("location: user_input_form.php");

?>
