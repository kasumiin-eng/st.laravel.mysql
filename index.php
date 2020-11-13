<?php 

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



if( !empty($_POST['btn_submit']) ) {
    if( $file_handle = fopen( FILENAME, "a") ) {
        // 書き込み日時を取得
		$now_date = date("Y-m-d H:i:s");
	
		// 書き込むデータを作成
		$data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";
	
		// 書き込み
		fwrite( $file_handle, $data);
	
		// ファイルを閉じる
		fclose( $file_handle);
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