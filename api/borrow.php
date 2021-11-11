<?php

require "_main.php";

$studentid = $_POST["sid"];
$bookid = $_POST["bid"];

if (!isset($studentid) || !isset($bookid)){
    $res = new Response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

$year = date("Y")+543;
$currentTime = date("d")."/".date("m")."/".$year;
$deadline = date("d/m/Y", strtotime($currentTime. ' + 6 Days + 1 Month'));



$targetDB = "`log`.`$year`";
$oldyear = $year - 1;

$conn = getDatabase();

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
$bookregisnum = $row["regisnum"];
$bookcategory = $row["category"];

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
$studentclass = $row["class"];


if($conn->query("SHOW TABLE LIKE $targetDB")){
    $conn->query("CREATE TABLE `log`.`$year` LIKE `log`.`$oldyear`");
}


$re = $conn->query("INSERT INTO $targetDB (borrowtime, name, class, bookname, category, regisnum, deadline) VALUES ('$currentTime', '$studentname', '$studentclass', '$bookname', '$bookcategory', '$bookregisnum' , '$deadline')");

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