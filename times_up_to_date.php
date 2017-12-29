<?php
    include("./config/config.php");
    
    // The date and time now
    $date_time = date("Y-m-d H:i:s");
    
    $query = $con->query("SELECT id, date_added FROM swirls ORDER BY id DESC");
    $i = 0; 
    
    while($row = $query->fetch_array(MYSQLI_ASSOC)){
        $id = $row['id'];
        $update = $con->query("UPDATE swirls SET date_added='$date_time' WHERE id='$id'");
        
        $comments_query = $con->query("SELECT id, date_added FROM swirl_comments WHERE post_id='$id' ORDER BY id ASC");
        
        //Duplicate date for comments
        $temp_date = $date_time;
        
        while($comment_row = $comments_query->fetch_array(MYSQLI_ASSOC)){
            $comment_id = $comment_row['id'];
            
            $temp_date = date('Y-m-d H:i:s', strtotime($temp_date . "-42 minutes 17 seconds"));
            $update_comment = $con->query("UPDATE swirl_comments SET date_added='$temp_date' WHERE id='$comment_id'");
        }
        
        if($i % 10 == 0){
            $date_time = date('Y-m-d H:i:s', strtotime($date_time . "+1 hours 28 minutes 42 seconds"));
        } elseif (i % 4 == 0) {
            $date_time = date('Y-m-d H:i:s', strtotime($date_time . "+51 minutes 2 seconds"));
        } else {
            $date_time = date('Y-m-d H:i:s', strtotime($date_time . "+24 minutes 17 seconds"));
        }
        
        $i++;
    }
    
?>