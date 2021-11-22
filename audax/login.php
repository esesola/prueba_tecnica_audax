<?php
include('config.php');


  
if ($mysqli->connect_error) {
  echo 'Errno: '.$mysqli->connect_errno;
  echo '<br>';
  echo 'Error: '.$mysqli->connect_error;
  exit();
}

$usuario_login = $_POST["txtusuario"];
$password_login = $_POST["txtpassword"];

if ($stmt = $mysqli->prepare("SELECT password FROM users WHERE email = ?")) {
    $stmt->bind_param("s", $usuario_login);
    $stmt->execute();
    $stmt->bind_result($password_db);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password_login, $password_db)){
        header("Location: tabla.html", TRUE, 301);
        
    }else {
        header("Location: login.html?error&usuario=".$usuario_login);
    }
}else{
    header("Location: login.html?error&usuario=".$usuario_login);
}

$mysqli->close();
exit();
?>