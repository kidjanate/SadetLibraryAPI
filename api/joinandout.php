<?php

require "_main.php";
date_default_timezone_set("Asia/Bangkok");


$studentid = $_POST["sid"];
if(!isset($studentid)){
    $res = new Response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

$day = date("d");
$month = date("m");
$year = date("Y")+543;
$hours = date("H:i:s");

$date = $day."/".$month."/".$year." ".$hours;

$targetdb = "loginlog.`$month/$year`";

$conn = getDatabase();
//Check table exists
$re = $conn->query("SHOW TABLES LIKE '$targetdb'");
if($re->num_rows==0){
    //not found create a new table
    $oldMonth = $month - 1;
    $targetoldtable = "loginlog.`$oldMonth/$year`";
    $conn->query("CREATE TABLE $targetdb LIKE $targetoldtable");
}

//Check student exists
$re = $conn->query("SELECT * FROM students.students WHERE id = '$studentid'");
if($re->num_rows==0){
    $res = new Response();
    $res->code = 2;
    $res->content = "ไม่พบนักเรียน";
    die(json_encode($res));
}

$row = $re->fetch_assoc();
$studentname = $row["name"];
$studentclass = $row["class"];

$re = $conn->query("SELECT * FROM $targetdb WHERE id = '$studentid' AND outtime = ''");
if($re->num_rows==0){
    //join
    $conn->query("INSERT INTO $targetdb (id, name, class, jointime) VALUES ('$studentid', '$studentname', '$studentclass', '$date')");
    $res = new Response();
    $res->code = 0;
    $res->content = "$studentname เข้าห้องสมุดแล้ว";
    die(json_encode($res));
}else{
    //out
    $conn->query("UPDATE $targetdb SET outtime = '$date' WHERE id = '$studentid' AND outtime = ''");
    $res = new Response();
    $res->code = 0;
    $res->content = "$studentname ออกจากห้องสมุดแล้ว";
    die(json_encode($res));
}




class Response{
    public $code;
    public $content;
}

?>