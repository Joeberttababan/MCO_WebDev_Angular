<?php
include 'db.php';
session_start();

$sql = "SELECT posts.*, users.first_name, users.last_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY RAND()";

$result = mysqli_query($conn, $sql);

$posts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
