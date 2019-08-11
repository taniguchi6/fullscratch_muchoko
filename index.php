<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge"><title>アンケートフォーム</title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen,tv" />
</head>
<body>
  <div>
    <table>
    <?php for( $row_i = 1; $row_i <= 9; $row_i++ ): ?>
      <tr>
      <?php for( $i = 1; $i <= 9; $i++ ): ?>
        <?php if ($row_i%2 === 0 || $i%2 === 0) :?>
          <td class="even"><?php echo $row_i * $i; ?></td>
        <?php else: ?>
          <td class="odd"><?php echo $row_i * $i; ?></td>
        <?php endif; ?>
      <?php endfor;?>
      </tr>
    <?php endfor;?>
    </table>
  </div>
  <p class="copy">
  &copy; 2010 PHP for Web Designer. All rights reserved.
  </p>
</body>
<style>
table {
  text-align: center;
}
td {
  width: 30px;
  padding: 5px;
}
.even {
  background-color: #44eeaa;
}
.odd {
  background-color: #aaee44;
}
</style>
</html>
