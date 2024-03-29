<?php
 session_start();
 ini_set('display_errors', 1);
?>
<?php
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

    // 投稿されたデータの処理ここから
    if(isset($_POST['submit']) && !empty($_POST['post'])) {
      $text_post = $_POST['post'];
      $user_id = $_SESSION['id'];
      //var_dump($text_post);

      // 画像を保存
      if(!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_type = $_FILES['image']['type'];
        $image_content = file_get_contents($_FILES['image']['tmp_name']);
        $image_size = $_FILES['image']['size'];

        $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size, created_at, image_user_id) VALUES (:image_name, :image_type, :image_content, :image_size, now(), :image_user_id)';
        $prepare = $dbh->prepare($sql);
        $prepare->bindValue(':image_name', $image_name, PDO::PARAM_STR);
        $prepare->bindValue(':image_type', $image_type, PDO::PARAM_STR);
        $prepare->bindValue(':image_content', $image_content, PDO::PARAM_STR);
        $prepare->bindValue(':image_size', $image_size, PDO::PARAM_INT);
        $prepare->bindValue(':image_user_id', $user_id, PDO::PARAM_INT);
        $prepare->execute();
      }

      // テキストの投稿をDBに登録
      $prepare = $dbh->prepare("INSERT INTO text_posts (post_id, user_id, text, date) VALUES (NULL, :user_id, :text_post, CURRENT_TIMESTAMP)");
      $prepare->bindValue(':user_id', (int)$user_id, PDO::PARAM_INT);
      $prepare->bindValue(':text_post', $text_post, PDO::PARAM_STR);
      $prepare->execute();
    }

    // 削除処理
    if(isset($_POST['delete_submit']) && !empty($_POST['delete_id'])) {
      $delete_id = $_POST['delete_id'];

      $prepare = $dbh->prepare("DELETE FROM text_posts WHERE text_posts.post_id = ?");
      $prepare->bindValue(1, (int)$delete_id, PDO::PARAM_INT);
      $prepare->execute();
    }

    // 全ての投稿を取得
    $prepare = $dbh->prepare('SELECT * FROM users, text_posts, images WHERE users.id = text_posts.user_id AND users.id = images.image_user_id');
    $prepare->execute();

    $text_posts = $prepare->fetchAll();

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
  <title>メイン画面</title>
</head>
<body>
  <h1>ようこそ<?php echo $_SESSION['username'];?>さん</h1>

  <div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
      <p><label for="post">投稿内容</label></p>
      <textarea name="post" id="post" cols="30" rows="10"></textarea>
      <p><label for="image">画像をアップロード</label></p>
      <input id="image" type="file" name="image" accept="image/png,image/jpeg">
      <button type="submit" name="submit">投稿</button>
    </form>
  </div>

  <p><a href="logout.php" title="Logout">ログアウト</a></p>

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

        <?php if($text_post['image_id']): ?>
          <?php
            $DB_PIC = $text_post['image_content'];

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $DB_PIC);
            finfo_close($finfo);
          ?>
        <?php endif; ?>

        <!-- ログインユーザーの投稿に削除ボタン追加 -->
        <?php if($text_post['user_id'] == $_SESSION['id']): ?>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="delete_id" value="<?php echo $text_post['post_id']; ?>">
            <input type="submit" value="投稿を削除" name="delete_submit">
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