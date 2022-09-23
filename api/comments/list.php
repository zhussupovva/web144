<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(!isset($_GET["id"])){
        exit();
    }

    $id=$_GET["id"];
    $query_comments=mysqli_query($con,
    "SELECT c.*, u.full_name FROM comments c
    LEFT OUTER JOIN users u ON
    u.id=c.author_id
    WHERE c.blog_id=$id");

    // php=>js json.encode()
    // js=>php json.decode()


    $comments=array();
    if(mysqli_num_rows($query_comments)==0){
        echo json_encode($comments);
        exit();
    }

    while($com=mysqli_fetch_assoc($query_comments)){
        $comments[]=$com;
    }

    echo json_encode($comments);

    
?>