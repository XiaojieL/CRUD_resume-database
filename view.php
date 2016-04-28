<?php
require_once "pdo.php";
session_start();

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
  }

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sm = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>


<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Xiaojie Liu</title>
</head>
<body>
<div class="container">
<h2>Profile information</h2>
<p>First Name: <?=$fn?></p>
<p>Last Name:<?=$ln?></p>
<p>Email: <?=$email?></p>
<p>Headline:<br/>
<?=$hl?></p>
<p>Summary:<br/>
<?=$sm?>
</p>

<?php
$stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
echo('<p>Position: </p>');
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  echo('<ul><li>');
  echo(htmlentities($row['year']).': '.htmlentities($row['description']));
  echo('</li></ul>');
}
?>

<a href="index.php">Done</a>

</body>
</html>
