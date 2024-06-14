<?php
session_start();
require_once 'config.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($id, $username, $hashed_password);
  $stmt->fetch();

  if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    header("Location: index.php");
  } else {
    echo "Felaktigt användarnamn eller lösenord";
  }
}

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $email = $_POST['email'];

  $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $password, $email);

  if ($stmt->execute()) {
    echo "Registrering lyckades, du kan nu logga in.";
  } else {
    echo "Registrering misslyckades: " . $stmt->error;
  }
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logga In / Registrera</title>
  <link rel="stylesheet" href="master.css">
</head>

<body>
  <h2>Logga In</h2>
  <form method="post" action="">
    <input type="text" name="username" placeholder="Användarnamn" required><br>
    <input type="password" name="password" placeholder="Lösenord" required><br>
    <button type="submit" name="login">Logga In</button>
  </form>

  <h2>Registrera</h2>
  <form method="post" action="">
    <input type="text" name="username" placeholder="Användarnamn" required><br>
    <input type="password" name="password" placeholder="Lösenord" required><br>
    <input type="email" name="email" placeholder="E-post" required><br>
    <button type="submit" name="register">Registrera</button>
  </form>
</body>

</html>