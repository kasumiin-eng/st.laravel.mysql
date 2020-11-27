<?php 

  //phpMyAdmin接続
  $user = 'root';
  $password = 'root';
  $db = 'stnews_database'; 
  $host = 'localhost';
  $port = 3306;
  $link = mysqli_init();
  //mysqlサーバとの接続をオープンする
  $success = mysqli_real_connect(
      $link,
      $host,
      $user,
      $password,
      $db,
      $port
  );



  //$dsn = 'mysql:host=localhost;dbname=stnews_database;charset=utf8';
  //データベースのユーザー名
  //$user = 'root';
  //データベースのパスワード
  //$password = 'root';
  //tryにPDOの処理を記述
  //try{
      //PDOインスタンスを生成
      //$dbh = new PDO($dsn, $user, $password);
      //エラーが発生したときの処理を記述
    //} catch (PDOException $e) {
        //エラーメッセージを表示させる
       // echo 'データベースにアクセスできません！' . $e->getMessage();
        //強制終了
        //exit;
    //}


  //[[id1,name1,message1],[id2,name2,message2], ... ,[id_n,name_n,message_n]]
  $NEWS_BOARD = [];
  $name = '';
  //My SQLからデータを取得
  $query = "SELECT * FROM `news_table`";
  if($success) {
      $result = mysqli_query($link,$query);
      while ($row = mysqli_fetch_array($result)) {
          $NEWS_BOARD[] = [$row['id'], $row['name'], $row['message']];
      }
  }
  //何かが投稿されたという意味
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if(!empty($_POST['name'])){
          //名前の追加用のQueryを書く
          $name = $_POST['name'];
          $insert_query = "INSERT INTO `news_table`(`id`, `name`, `message`) VALUES (null,'{name}','{message}')";
          mysqli_query($link, $insert_query);
          header('Location: ' . $_SERVER['SCRIPT_NAME']);
          exit;
      } else if (isset($_POST['del'])) {
        //削除ボタンを押したときの処理を書く。
      $delete_query = "DELETE FROM `news_table` WHERE `id` = '{POST['del']}'";
      mysqli_query($link, $delete_query);
      header('Location: ' . $SERVER['SCRIPT_NAME']);
      exit; 
    }  
  }  

  
  

  
// メッセージを保存するファイルのパス設定
define( 'FILENAME', './message.txt');
// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
// null:特殊な値で変数が値を持たない
$error_message = array();



if( !empty($_POST['btn_submit']) ) {

    //タイトル、記事入力されてない時にエラーメッセージ出す
    if( empty($_POST['view_name']) ) {
        $error_message[] = 'タイトルを入力してください。';
    }
    if( empty($_POST['message']) ) {
        $error_message[] = '記事を入力してください。';
    }
    if( empty($error_message) ) {
        if( $file_handle = fopen( FILENAME, "a") ) {
            // 書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");
        
            // 書き込むデータを作成
            $data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";
        
            // 書き込み
            fwrite( $file_handle, $data);
        
            // ファイルを閉じる
            fclose($file_handle);
            
            $success_message = 'メッセージを書き込みました。';
    
        }
    }
	
}


if( $file_handle = fopen( FILENAME,'r') ) {
    while( $data = fgets($file_handle) ){
        $split_data = preg_split( '/\'/', $data);

        $message = array(
            'view_name' => $split_data[1],
            'message' => $split_data[3],
            'post_date' => $split_data[5]
        );
        array_unshift( $message_array, $message);
    }

    // ファイルを閉じる
    fclose( $file_handle);
}



?>

<!DOCTYPE html>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>st assignmentarticle</title>
</head>

<body>
    <div class="header">
        <p>Laravel News</p>
        <p>さあ　最新ニュースをシェアしましょう</p>
    </div> 

    <?php if( !empty($success_message) ): ?>
    <p class="success_message"><?php echo $success_message; ?></p>
    <?php endif; ?> 

    <?php if( !empty($error_message) ): ?>
	<ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
			<li><?php echo $value; ?></li>
		<?php endforeach; ?>
	</ul>
　　<?php endif; ?>

    <!--<?php if(! empty($error_message) ): ?> -->
    <!--<p class="error_message"><?php echo $error_message; ?> </p> -->
    <!--<?php endif; ?> -->
    
    <!--入力フォーム-->
    <form method="post">
        <div class="title">
        
		    <p>タイトル：</p>
		    <input id="view_name" type="text" name="view_name" value="">
	    </div>
	    <div class="article">
		    <p>記事：</p>
		    <textarea name="message" rows="10" cols="60"></textarea>
	    </div>
        <div class="submit">
	        <input type="submit" name="btn_submit" value="投稿">
        </div>    
    </form>
	
　　 
    <!--投稿されたメッセージを表示 -->
    <hr>
    <section>
    <?php if( !empty($message_array) ): ?>
    <?php foreach( $message_array as $value ): ?>
    <article>
        <div class="info">
            <h2><?php echo $value['view_name']; ?></h2>
            <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
        </div>
        <p><?php echo $value['message']; ?></p>
        <p>
            <a href="http://localhost/comment.php">記事全文・コメントを見る</a>
        <p>
    </article>
    <?php endforeach; ?>
    <?php endif; ?>
    </section>


</body>

</html>




