<?php
if(isset($_GET['key']) && file_exists('./upload/'.$_GET['key'])){
    $dir = __DIR__.'/upload/'.$_GET['key'];
    $files = glob($dir . '/*.*');
    if($files === false){
        rmdir($dir);
        echo 'ディレクトリ内のファイルは見つかりませんでしたが、ディレクトリの削除に成功しました。';
        exit;
    }
    foreach($files as $file){
        $result = match(unlink($file)){
            true => 'ファイル '.basename($file).'を削除しました。',
            false => throw new Exception('ファイル '.basename($file).'の削除に失敗しました。'),
        };
        echo $result.'<br>';
    }
    if(isset($result)){
        rmdir($dir);
    }else{
        $error_message = 'ファイルの削除に失敗したためディレクトリを削除することはできません。';
    }
}else{
    $error_message = 'エラーが発生しました。';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ファイル削除</title>
</head>
<body>
    <p>アップロードしたファイルを削除します。</p>
    <?php
    if(isset($error_message)){
        echo $error_message;
    }
    ?>
    <form action="" method="get">
        <input type="text" name="key">
        <input type="submit">
    </form>
</body>
</html>
