<?php 

    include('config.php');
    session_start();
    $uid = $_SESSION['myusermasid'];
    $sql="delete from mas_user_online where usermasid = '$uid';";
    mysql_query($sql);
    // First we execute our common code to connection to the database and start the session 
    
    
    // We remove the user's data from the session 
    unset($_SESSION['myusername']); 
     
    // We redirect them to the login page 
    header("Location: index.php"); 
    die("Redirecting to: index.php");
    
   
?>