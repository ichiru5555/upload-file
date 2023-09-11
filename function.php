<?php
function random(int $length){
        $randombytes = random_bytes($length);
        $randomhex = bin2hex($randombytes);
        return $randomhex;
}
function register_sql(string $host, string $database_name, string $user, string $password, string $dir_name, ?string $dir_password){
    $pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$password);
    $sql = "INSERT INTO upload (id, dir_name, dir_passwd) VALUES (NULL, :dir_name, :dir_passwd);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dir_name', $dir_name);
    $stmt->bindValue(':dir_passwd', $dir_password);
    $stmt->execute();
    unset($pdo);
    unset($stmt);
}
function password_sql(string $host, string $database_name, string $user, string $password, string $dir_name, ?string $dir_password){
    $pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$password);
    $sql = "SELECT * FROM upload WHERE dir_name = :dir_name;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dir_name', $dir_name);
    $stmt->execute();
    $result = $stmt->fetch();
    if(is_null($result['dir_name'])){
        return true;
    }
    $dir_passwd = $result['dir_passwd'];
    unset($pdo);
    unset($result);
    return password_verify($dir_password, $dir_passwd);
}
function delete_sql(string $host, string $database_name, string $user, string $password, string $dir_name, ?string $dir_password){
    $pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$password);
    $sql = "SELECT * FROM upload WHERE dir_name = :dir_name;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dir_name', $dir_name);
    $stmt->execute();
    $result = $stmt->fetch();
    if(is_null($result['dir_name'])){
        return true;
    }
    if(password_verify($dir_password, $result['dir_passwd'])){
        $sql = "DELETE FROM upload WHERE dir_name = :dir_name;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':dir_name', $dir_name);
        $stmt->execute();
        unset($pdo);
        unset($result);
        return true;
    }else{
        unset($pdo);
        unset($result);
        return 'パスワードの認証に失敗したためデーターベースの削除に失敗しました';
    }
}
