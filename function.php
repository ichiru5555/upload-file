<?php
function random(int $length){
        $randombytes = random_bytes($length);
        $randomhex = bin2hex($randombytes);
        return $randomhex;
}
