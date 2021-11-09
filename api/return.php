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

$re = $conn->query("SELECT 1 FROM `studentsborrow`.`$studentid`;");
if($re->num_rows==0){
    $res = new Response();
    $res->code = 3;
    $res->content = "Error. You never borrow the books.";
    die(json_encode($res));
    
}

$re = $conn->query("SELECT bookid, borrowtime FROM `studentsborrow`.`$studentid` WHERE bookid = '$bookid';");
if($re->num_rows==0){
    $res = new Response();
    $res->code = 2;
    $res->content = "Error. You never borrow this books.";
    die(json_encode($res));
    
}

$row = $re->fetch_assoc();
$borrowtime = $row["borrowtime"];

$time = time();
$re = $conn->query("DELETE FROM `studentsborrow`.`$studentid` WHERE bookid = '$bookid';");

$conn->query("INSERT INTO studentsborrow.log (id, bookid, borrowtime, returntime) VALUES ('$studentid', '$bookid', '$borrowtime', '$time')");


$res = new Response();
$res->code = 0;
$res->content = "Success";
die(json_encode($res));





class Response{
    public $code;
    public $content;
}
?>