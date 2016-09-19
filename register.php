<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	require "database.php";
	$username = $_POST["username"];
    $stmt = $mysqli->prepare("select count(*) from users where username=?");
    if(!$stmt){
        printf("Query Prep Failed when check for dup: %s", $mysqli->error);
        $mysqli->close();
        exit;
    }
    $stmt->bind_param('s',$username);
    $result = $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
	if ($result!=0) {
		   echo "username ".$username." already been take!";

	} else {
        $stmt->close();
		$password = $_POST["password"];
		$cost = 10;
        $salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 22);
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		$hash = crypt($password, $salt);
        $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
        if(!$stmt){
            printf("Query Prep Failed when inserting: %s", $mysqli->error);
            $stmt->close();
            $mysqli->close();
            exit;
        }
        $stmt->bind_param('ss',$username,$hash);
        $stmt->execute();
		if(!$stmt){
            printf("Insertion Failed: %s", $mysqli->error);
            $stmt->close();
            $mysqli->close();
            exit;
        } else {
        	$_SESSION['verified'] = true;
        	$_SESSION['username'] = $username;
            $stmt->close();
            $mysqli->close();
            header("Location: index.php");
            exit;
        }		
	}
?>