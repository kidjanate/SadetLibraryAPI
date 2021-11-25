<?php

$studentid = $_POST["sid"];
$studentname = $_POST["sname"];
$studentclass = $_POST["sclass"];
$studentnumber = $_POST["snumber"];

if(!isset($studentid) ||
!isset($studentname) ||
!isset($studentclass)){
    $res = new Response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

require "_main.php";

$conn = getDatabase();


//Check students exists
$re = $conn->query("SELECT * FROM students.students WHERE id = '$studentid'");
if($re->num_rows>0){
    $res = new Response();
    $res->code = 2;
    $res->content = "มีรายชื่อนักเรียนนี้อยู่แล้วโปรดใช้รหัสนักเรียนอื่น";
    die(json_encode($res));
}


$re = $conn->query("INSERT INTO students.students (id, name, class, number) VALUES ('$studentid', '$studentname', '$studentclass', '$studentnumber');");
if(!$re){
    $res = new Response();
    $res->code = 3;
    $res->content = "อัพเดทรายชื่อนักเรียนล้มเหลว";
    die(json_encode($res));
}

$res = new Response();
$res->code = 0;
$res->content = "อัพเดทรายชื่อนักเรียนสำเร็จ กรุณาทำการลงชื่อเข้าใช้อีกครั้ง";
die(json_encode($res));



class Response{
    public $code;
    public $content;
}

?>