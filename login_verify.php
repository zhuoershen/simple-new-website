<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['verified']) and $_SESSION['verified'] and isset($_SESSION['username'])) {
	header("Location inddex.php");
} else {
	require 'database.php';
	$username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $stmt = $mysqli->prepare("select count(*), username, password from users where username=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->bind_result($cnt, $u,$p);
    $stmt->fetch();
    if($cnt==0){
    	echo "invalid username or password, redirecting\n";
    	usleep(5);
    	$_SESSION['verified'] = false;
    	// header("Location: index.php");
    	exit;
    }
    if( $cnt == 1 && crypt($password, $p)==$p){
		$_SESSION['username'] = $u;
    	$_SESSION['verified'] = true;
    	// echo "login in as ".$u;
    	// echo $p;
    	$stmt->close();
    	header("Location: index.php");
        exit;
	}else{
		// Login failed; redirect back to the login screen
		$stmt->close();
		header("Location: index.php");
        exit;	
		// echo "from DB".$p;
		// echo "<br>";
		// echo "local hashed".crypt($password, $p);
		// echo "<br>";
		// echo "last invalid username or password, redirecting\n";
	}
}
?>