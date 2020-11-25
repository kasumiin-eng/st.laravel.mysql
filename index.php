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



  $dsn = 'mysql:host=localhost;dbname=stnews_database;charset=utf8';
  //データベースのユーザー名
  $user = 'root';
  //データベースのパスワード
  $password = 'root';
  //tryにPDOの処理を記述
  try{
      //PDOインスタンスを生成
      $dbh = new PDO($dsn, $user, $password);
      //エラーが発生したときの処理を記述
    } catch (PDOException $e) {
        //エラーメッセージを表示させる
        echo 'データベースにアクセスできません！' . $e->getMessage();
        //強制終了
        exit;
    }

  
  

  
//メッセージを保存するファイルのパス設定
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
$error_message = array();
// null:特殊な値で変数が値を持たない




if( !empty($_POST['btn_submit']) ) {

    //表示名の入力チェック
    if(empty($_POST['view_name']) ) {
        $error_message[] = '表示名を入力してください。';
    }

    // メッセージの入力チェック
	if( empty($_POST['message']) ) {
		$error_message[] = 'ひと言メッセージを入力してください。';
	}

	if( empty($error_message) ) {

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




//エラーメッセージを表示する　できてない
if(empty($_POST['title'])){
    $error_message[] = 'タイトルは必須です。';}
if(empty($_POST['txt'])){
    $error_message[] = '記事は必須です。';}        
if(strlen($_POST['title']) > 30){
    $error_message[] = 'タイトルは30字以内で入力してください。';}

    var_dump($_POST);
    


    //if ($_SERVER["REQUEST_METHOD"] === "POST"){

        //文字数制限
        //if(mb_strlen($_POST["title"])>30){
          //$ERROR[]="タイトルは30文字以内で入力してください";
        }
        //タイトル未入力
       // else if(empty($_POST["title"])){
         //$ERROR[]="タイトルを入力してください";}
        //記事未入力
        //else if(empty($_POST["text"])){
         //$ERROR[]="記事を入力してください";}
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
    
    <!--投稿-->


    <form name="post">
        <div>
           
            <label for="view_name">タイトル：<label>
		    <input id="view_name" type="text" name="view_name" value="">
        
        </div>

	    
	    <div>
		
            <label for="name">記事：</label>
		    <textarea name="message" rows="10" cols="60"></textarea>
    
	    </div>
        <div>
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
        <p>
            <a href="http://localhost/comment.php">記事全文・コメントを見る</a>
        <p>
    </article>
    <?php endforeach; ?>
    <?php endif; ?>
    </section>


</body>
</html>
