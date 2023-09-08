<?php
$file_name = null;
if(isset($_GET['key'], $_GET['file_name'])){
    if(preg_match("/[一-龠]+|[ぁ-ん]+|[ァ-ヴー]+|[一-龠]+|[ａ-ｚＡ-Ｚ０-９]/u", $_GET['file_name'])){
        $file_name = __DIR__.'/upload/'.$_GET['key'].'/'.$_GET['file_name'];
        $file_name = mb_convert_encoding($file_name, 'sjis', 'auto');
    }else{
        $file_name = __DIR__.'/upload/'.$_GET['key'].'/'.$_GET['file_name'];
    }
}
if(isset($_GET['key']) and empty($_GET['file_name']) and file_exists(__DIR__.'/upload/'.$_GET['key'])){
    $dir = __DIR__.'/upload/'.$_GET['key'];
    $dir_array = glob($dir.'/*');
}elseif(isset($_GET['key'], $_GET['file_name']) and is_file($file_name)){
    $file = __DIR__.'/upload/'.$_GET['key'].'/'.$_GET['file_name'];
    $filetype = pathinfo($file, PATHINFO_EXTENSION);
    header("Content-type: application/$filetype");
    header('Content-Disposition: attachment; filename="'.$_GET['file_name'].'"');
    header('Content-Length: '.filesize($file));
    readfile($file);
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
        echo '<p>ファイル名: <a href="./download.php?key='.$_GET['key'].'&file_name='.basename($file).'"</a>'.basename($file).'</p>';
    }
    ?>
    <?php
    }elseif(isset($error)){
        echo $error;
    }
    ?>
</body>
</html>
