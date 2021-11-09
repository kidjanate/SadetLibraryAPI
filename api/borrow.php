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
    $conn->query("CREATE TABLE `studentsborrow`.`$studentid` (
        `bookid` VARCHAR(45) NOT NULL,
        `borrowtime` VARCHAR(45) NULL,
        `returntime` VARCHAR(45) NULL,
        `status` VARCHAR(45) NULL,
        PRIMARY KEY (`bookid`));");
    
}

$time = time();
$re = $conn->query("INSERT INTO `studentsborrow`.`$studentid` (bookid, borrowtime, status) VALUES ('$bookid', '$time', 'Borrowing')");

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