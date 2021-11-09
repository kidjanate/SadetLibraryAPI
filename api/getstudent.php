<?php

require "_main.php";


$studentid = $_GET["sid"];

if (!isset($studentid)){
    $res = new Response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

$conn = getDatabase();


$time = time();
$re = $conn->query("SELECT * FROM students.students WHERE id = '$studentid';");

if($re->num_rows>0){
    $row = $re->fetch_assoc();
    $res = new Response();
    $res->code = 0;
    $res->content = $row["name"];
    die(json_encode($res));
}else{
    $res = new Response();
    $res->code = 1;
    $res->content = "Error. ".$conn->error;
    die(json_encode($res));
    
}








class Response{
    public $code;
    public $content;
}
?>