<?php
 //ob_start();
 session_start();
 ini_set('display_errors', 1);
?>
<?php
  if(isset($_POST['submit']) && !empty($_post['post'])) {
    $text_post = $_POST['post'];
    $user_id = $_SESSION['id'];
    var_dump($text_post);
  }

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
    var_dump($_SESSION['id']);
    echo '<br>';
    $id = $_SESSION['id'];

    if(isset($_POST['submit']) && !empty($_POST['post'])) {
      $text_post = $_POST['post'];
      $user_id = $_SESSION['id'];
      var_dump($text_post);

      $prepare = $dbh->prepare("INSERT INTO text_posts (post_id, user_id, text, date) VALUES (NULL, :user_id, :text_post, CURRENT_TIMESTAMP)");
      $prepare->bindValue(':user_id', (int)$user_id, PDO::PARAM_INT);
      $prepare->bindValue(':text_post', $text_post, PDO::PARAM_STR);
      $prepare->execute();
    }

    // 削除処理
    if(isset($_POST['delete_submit']) && !empty($_POST['delete_id'])) {
      $delete_id = $_POST['delete_id'];
      var_dump($delete_id);

      $prepare = $dbh->prepare("DELETE FROM text_posts WHERE text_posts.post_id = ?");
      $prepare->bindValue(1, (int)$delete_id, PDO::PARAM_INT);
      $prepare->execute();
    }

    // 全ての投稿を取得
    $prepare = $dbh->prepare('SELECT * FROM users, text_posts WHERE users.id = text_posts.user_id');
    $prepare->execute();

    $text_posts = $prepare->fetchAll();
    print_r($text_posts);
    echo '<br>';

  } catch (PDOException $e) {

    $error = $e->getMessage();
    echo $error;
  }
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
  <h2>ようこそ<?php echo $_SESSION['username'];?>さん</h2>
  <div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <label for="post">投稿内容</label>
      <input name="post" id="post" cols="30" rows="10">
      <button type="submit" name="submit">投稿</button>
    </form>
  </div>
  <div>
    <P><a href="logout.php" title="Logout">ログアウト</a></p>
  </div>
  <div class="post-wrapper">
  
    <?php foreach($text_posts as $text_post): ?>
      <div class="post">
        <p>
          ユーザー名：<?php echo $text_post['name'];?>
        </p>
        <p>
          <?php echo $text_post['text']; echo '<br>';?>
        </p>
        <p class="time">
          <?php echo $text_post['date']; ?>
        </p>
        <?php if($text_post['user_id'] == $_SESSION['id']): ?>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="delete_id" value="<?php echo $text_post['post_id']; ?>">
            <input type="submit" value="削除" name="delete_submit">
          <a href="">投稿を削除</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>

<style>
.post {
  border: 2px solid #efacde;
  margin-top: 20px;
}
</style>
</html>