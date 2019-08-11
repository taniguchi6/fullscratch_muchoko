<?php
 //ob_start();
 session_start();
 ini_set('display_errors', 1);
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

    if(isset($_POST['login']) && !empty($_POST['email']) && !empty($_POST['password'])) {
      $usermail = $_POST['email'];
      var_dump($usermail);
      echo '<br>';
      try {

        $dbh = new PDO(
          'mysql:host=localhost;dbname=users;charset=utf8',
          'root',
          'root',
          array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
          )
        );
        
        $prepare = $dbh->prepare('SELECT * FROM users WHERE mail = ?');
        $prepare->bindValue(1,$usermail,PDO::PARAM_STR);
        $prepare->execute();
  
        $result = $prepare->fetch();
        print_r($result);
        echo '<br>';

        //ユーザー名が存在していればパスワードの認証をする
        if($result != false) {
          $password = $_POST['password'];
          echo $password;
          echo '<br>';
          $prepare = $dbh->prepare('SELECT * FROM users WHERE mail = ? AND password = ?');
          $prepare->bindValue(1,$usermail,PDO::PARAM_STR);
          $prepare->bindValue(2,$password,PDO::PARAM_STR);
          $prepare->execute();

          $result = $prepare->fetch();
          print_r($result);
          echo '<br>';
          
          if ($result != false) {
            echo 'ログイン成功<br>';
            echo $result['name'];
            echo '<br>';
            echo $result['id'];
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            //$_SESSION['usermail'] = $usermail;
            $_SESSION['username'] = $result['name'];
            $_SESSION['id'] = $result['id'];
            header('Refresh: 2; URL = main.php');
          } else {
            $msg = 'パスワードが違います。';
          }
        }else {
          $msg = 'ユーザー名が違います。';
        }
  
      } catch (PDOException $e) {
  
        $error = $e->getMessage();
        echo $error;
      }
      // if($_POST['email'] == 'tutorialspoint' && $_POST['password'] == 'sample') {
      //   //valid?
      //   $_SESSION['valid'] = true;
      //   $_SESSION['timeout'] = time();
      //   $_SESSION['username'] = 'tutorialspoint';

      //   $msg = 'You have entered valid use name and password';
      // }else {
      //   $msg = 'ユーザー名またはパスワードが間違っています。アカウントをお持ちでない方はユーザー登録してください';
      // }
    }
  ?>
  </div>
  <div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <p><?php echo $msg; ?></p>
      <p><?php var_dump($result); ?></p>
      <input type="mail" name="email" placeholder="email = sample@gmail.co" required autofocus><br>
      <input type="password" name = "password" placeholder="password = 1234" required>
      <button type="submit" name="login">Login</button>
    </form>

    <P>Click here to clean <a href="logout.php" title="Logout">Session.</a></p>
  </div>
</body>
</html>