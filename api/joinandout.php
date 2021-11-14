<?php

$studentid = $_POST["sid"];
if(!isset($studentid)){
    $res = new response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

require "_main.php";

$conn = getDatabase();
$re = $conn->query("SELECT * FROM students.students WHERE id = '$studentid';");
if($re->num_rowsm<=0){
    $res = new response();
    $res->code = 2;
    $res->content = "Student not found.";
    die(json_encode($res));
}

class response
{
    public $code;
    public $content;
}

?>