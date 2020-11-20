<?php 

  //phpMyAdmin接続
  $db_host = 'localhost';
  $db_user = 'root';
  $db_password = 'root';
  $db_db = 'information_schema';
  $db_port = 3306;

  $mysqli = new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );
	
  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }

  echo 'Success: A proper connection to MySQL was made.';
  echo '<br>';
  echo 'Host information: '.$mysqli->host_info;
  echo '<br>';
  echo 'Protocol version: '.$mysqli->protocol_version;

  $mysqli->close();


　// mysql:host=ホスト名;dbname=データベース名;charset=文字エンコード
  $dsn ='mysql:host=localhost;dbname=LaravelNews;charset=utf8';
　//データベースのユーザー名
　$user = 'user';
  //データベースのパスワード
　$password = 'pass';
　//PDOインスタンスを生成
　$dbh= new PDO($dsn, $user, $password);
  //例外が発生した時の処理を記述
} catch (PDOException $e) {
    //エラーメッセージを表示させる
    echo 'データベースにアクセスできません！' . $e->getMessage();
}
  //データベースとの接続を閉じる
  $dbh = null;






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



// INSERT文を変数に格納
//$sql = "INSERT INTO article (id, name, message.txt) VALUES ('$id', '$name', '$message')";
$sql = "INSERT INTO article (id, name, message.txt) VALUES (:id, :name, now())";
 
//挿入する値は空のまま、SQL実行の準備をする
$stmt = $dbh->prepare($sql);
 
// 挿入する値を配列に格納する
$params = array(':id' => 'おはようございます', ':name' => 'こんにちは');
 
// 挿入する値が入った変数をexecuteにセットしてSQLを実行
$stmt->execute($params);
 
// 投稿完了のメッセージ
echo '投稿完了しました';



if( !empty($_POST['btn_submit']) ) {
    if( $file_handle = fopen( FILENAME, "a") ) {
        // 書き込み日時を取得
		$now_date = date("Y-m-d H:i:s");
	
		// 書き込むデータを作成
		$data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";
	
		// 書き込み
		fwrite($file_handle, $data);
	
		// ファイルを閉じる
        fclose($file_handle);
        
        $success_message = 'メッセージを書き込みました。';

    }
    	
	var_dump($_POST);
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


//エラーメッセージを表示する
if(empty($_POST['title'])){
    $error_message[] = 'タイトルは必須です。';}
if(empty($_POST['txt'])){
    $error_message[] = '記事は必須です。';}
if(strlen($_POST['title']) > 30){
    $error_message[] = 'タイトルは30字以内で入力してください。';}



?>

<!DOCTYPE html>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>st assignment</title>
</head>

<body>
    <div class="header">
        <p>Laravel News</p>
        <p>さあ　最新ニュースをシェアしましょう</p>
    </div> 

    <?php if( !empty($success_message) ): ?>
    <p class="success_message"><?php echo $success_message; ?></p>
    <?php endif; ?> 
    
    <!--投稿-->


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
    </article>
    <?php endforeach; ?>
    <?php endif; ?>
    </section>


</body>

</html>