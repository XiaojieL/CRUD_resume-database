<?php
require_once "pdo.php";
session_start();

$stmt = $pdo->query("SELECT user_id, profile_id, first_name, last_name, headline FROM Profile");

?>

<!DOCTYPE html>
<html>
<head>
<title>Xiaojie Liu</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h2>Xiaojie's Resume Registry</h2>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}

if(!isset($_SESSION['name'])){
echo('<p><a href="login.php">Please log in</a></p>');
}else {
  echo('<p><a href="logout.php">Logout</a></p>');
}
?>
<table border="1">
<tr><th>Name</th><th>Headline</th>
<?php
  if(isset($_SESSION['name'])){
    echo("<th>Action</th>");
  }
  echo("</tr>");

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">');
    echo(htmlentities($row['first_name']));
    echo("&nbsp");
    echo(htmlentities($row['last_name']));
    echo('</a>');
    echo("</td><td>");
    echo(htmlentities($row['headline']));
  if(isset($_SESSION['name']) && $row['user_id']==$_SESSION['user_id']){
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
  }
    echo("</td></tr>\n");
}

?>
</table>

<?php
  if(isset($_SESSION['name'])){
    echo('<a href="add.php">Add New Entry</a>');
    }
?>
</div>
</body>
</html>
