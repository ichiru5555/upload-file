<?php
$file_name = null;
if(isset($_GET['key'])){
    if(!preg_match('/^[a-zA-Z0-9_-]+$/', $_GET['key'])){
        echo '入ってはいけない文字が入っています.';
        exit;
    }
    $get_key = htmlspecialchars($_GET['key'], ENT_QUOTES);
}
if(isset($get_key, $_GET['file_name'])){
    if(preg_match("/[一-龠]+|[ぁ-ん]+|[ァ-ヴー]+|[一-龠]+|[ａ-ｚＡ-Ｚ０-９]/u", $_GET['file_name'])){
        $file_name = __DIR__.'/upload/'.$get_key.'/'.$_GET['file_name'];
        $file_name = mb_convert_encoding($file_name, 'sjis', 'auto');
    }else{
        $file_name = __DIR__.'/upload/'.$get_key.'/'.$_GET['file_name'];
    }
}
if(isset($_GET['key']) && empty($_GET['file_name']) && file_exists(__DIR__.'/upload/'.$get_key) && empty($_GET['zip'])){
    $dir = __DIR__.'/upload/'.$get_key;
    $dir_array = glob($dir.'/*');
}elseif(isset($_GET['key'], $_GET['file_name']) and is_file($file_name) && empty($_GET['zip'])){
    $file = __DIR__.'/upload/'.$get_key.'/'.$_GET['file_name'];
    $filetype = pathinfo($file, PATHINFO_EXTENSION);
    header("Content-type: application/$filetype");
    header('Content-Disposition: attachment; filename="'.$_GET['file_name'].'"');
    header('Content-Length: '.filesize($file));
    readfile($file);
    exit;
}elseif(isset($_GET['key'], $_GET['zip'])){
    $zip_file = __DIR__.'/upload/'.$get_key.'/all.zip';
    if(is_file(__DIR__.'/upload/'.$get_key.'/all.zip')){
        header("Content-type: application/zip");
        header('Content-Disposition: attachment; filename="all.zip"');
        header('Content-Length: '.filesize($zip_file));
        readfile($zip_file);
        exit;
    }
    $zip = new ZipArchive();
    $result = $zip->open($zip_file, ZipArchive::CREATE);
    if(!$result){
        echo '処理に失敗しました。';
        exit;
    }
    $files = glob(__DIR__.'/upload/'.$get_key.'/*.*');
    foreach($files as $file){
        $zip->addFile($file, basename($file));
    }
    $zip->close();
    header("Content-type: application/zip");
    header('Content-Disposition: attachment; filename="all.zip"');
    header('Content-Length: '.filesize($zip_file));
    readfile($zip_file);
    exit;
}else{
    $error = 'エラーが発生しました.';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダウンロード</title>
</head>
<body>
    <?php
    if(empty($_GET['key'])){
    ?>
    <p>共有されたコードを記入して送信してください</p>
    <form action="" method="get">
        <input type="text" name="key">
        <input type="submit">
    </form>
    <?php
    }elseif(isset($_GET['key'], $dir_array)){
    ?>
    <p>ファイル一覧</p>
    <?php
    foreach($dir_array as $file){
        echo '<p>ファイル名: <a href="./download.php?key='.$_GET['key'].'&file_name='.basename($file).'">'.basename($file).'</a></p>';
    }
    echo '<p>ファイル名: <a href="./download.php?key='.$_GET['key'].'&zip=true">all.zip</a></p>';
    }elseif(isset($error)){
        echo $error;
    }
    ?>
</body>
</html>
