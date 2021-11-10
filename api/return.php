<?php

require "_main.php";


$studentid = $_GET["sid"];
$bookid = $_GET["bid"];

if (!isset($studentid) || !isset($bookid)){
    $res = new Response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

$conn = getDatabase();




class Response{
    public $code;
    public $content;
}
?>