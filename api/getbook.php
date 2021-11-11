<?php

require "_main.php";



$id = $_POST["bid"];

if (!isset($id)){
    $res = new Response();
    $res->code = 1;
    $res->content = "Bad request.";
    die(json_encode($res));
}

$conn = getDatabase();

$re = $conn->query("SELECT * FROM books.books WHERE id = '$id';");
if($re->num_rows>0){
    $res = new Response();
    $res->code = 0;
    $res->content = "Success";
    
    $row = $re->fetch_assoc();
    $book = new book();
    $book->id = $row["id"];
    $book->name = $row["name"];
    $book->getbooktime = $row["getbooktime"];
    $book->regisNumber = $row["regisNumber"];
    $book->price = $row["price"];

    $res->book = $book;

    die(json_encode($res));
}else{
    
    $res = new Response();
    $res->code = 2;
    $res->content = "Book not found.";
    die(json_encode($res));
    
}








class Response{
    public $code;
    public $content;
    public $book;
}

class book
{
    public $id;
    public $name;
    public $getbooktime;
    public $regisNumber;
    public $price;
}
?>