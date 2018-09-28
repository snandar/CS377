<?php
    session_start();
    require 'configure.php';
	$schoolID = $_POST['schoolID'];
	
        $instructorsql = "SELECT instructorID FROM instructor WHERE instructorID='$schoolID'";
	if($result = mysqli_query($conn, $instructorsql)){
                //if instructor
		if(mysqli_num_rows($result) > 0){
			$_SESSION['instructorID'] = $schoolID;
			header("location: instructor_view.php");
			$_SESSION['logged_in'] = true;
		}
                
                //if not instructor
                elseif(mysqli_num_rows($result) == 0){
                    $studentsql = "SELECT studentID FROM student WHERE studentID='$schoolID'";
                    if($result = mysqli_query($conn, $studentsql)){
                        //if student
                        if(mysqli_num_rows($result) > 0){
                            $_SESSION["studentID"] = $schoolID;
                            header("location: student_view.php");
                            $_SESSION['logged_in'] = true;

                        }
                        //if not student
                        elseif(mysqli_num_rows($result) == 0){

                            $chairsql = "SELECT chairID from department WHERE chairID = '$schoolID'";
                            if($result = mysqli_query($conn, $chairsql)){
                                //if chair
                                if(mysqli_num_rows($result) > 0){
                                    $_SESSION["chairID"] = $schoolID;
                                    header("location: chair_view.php");
                                    $_SESSION['logged_in'] = true;
                                }
                                
                                elseif(mysqli_num_rows($result) == 0){
                                    $_SESSION['message'] = "User with that ID doesn't exist!";
                                    header("location: error.php");
                                }

                            }
                            
                        }
                    }                    
                }
	}


	
?>
