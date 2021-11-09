<?php

function getDatabase(){
    $conn = new mysqli("localhost", "root", null);
    return $conn;
}

?>