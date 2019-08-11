<?php
 //ob_start();
 session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>login</title>
</head>
<body>
  <h2>ログイン画面</h2>
  <div>
  <?php
    $msg = '';

    if(isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
      
      if($_POST['username'] == 'tutorialspoint' && $_POST['password'] == '1234') {
        //valid?
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = 'tutorialspoint';

        $msg = 'You have entered valid use name and password';
      }else {
        $msg = 'Wrong username or password';
      }
    }
  ?>
  </div>
  <div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <p><?php echo $msg; ?></p>
      <input type="text" name="username" placeholder="username = tutorialspoint" required autofocus><br>
      <input type="password" name = "password" placeholder="password = 1234" required>
      <button type="submit" name="login">Login</button>
    </form>

    <P>Click here to clean <a href="logout.php" title="Logout">Session.</a></p>
  </div>
</body>
</html>