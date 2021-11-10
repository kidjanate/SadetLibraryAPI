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
$year = date("Y")+543;
$currentTime = date("d")."/".date("m")."/".$year;

$targetDB = "`log`.`$year`";

//Get book
$re = $conn->query("SELECT * FROM `books`.`books` WHERE id = '$bookid'");
if ($re->num_rows==0){
    $res = new Response();
    $res->code = 4;
    $res->content = "Book not found.";
    die(json_encode($res));
}
$row = $re->fetch_assoc();
$bookname = $row["name"];

//Get student
$re = $conn->query("SELECT * FROM students.students WHERE id = '$studentid'");
if ($re->num_rows==0){
    $res = new Response();
    $res->code = 3;
    $res->content = "Student not found.";
    die(json_encode($res));
}
$row = $re->fetch_assoc();
$studentname = $row["name"];

$re = $conn->query("UPDATE $targetDB SET returntime = '$currentTime' WHERE bookname = '$bookname' AND name = '$studentname';");

if($re == true){
    $res = new Response();
    $res->code = 0;
    $res->content = "Success.";
    die(json_encode($res));
}else{
    $res = new Response();
    $res->code = 2;
    $res->content = "Error. ".$conn->error;
    die(json_encode($res));
    
}


class Response{
    public $code;
    public $content;
}
?>