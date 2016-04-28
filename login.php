<?php // Do not put any HTML above this line
require_once "pdo.php";
session_start();

if (isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123


if ( isset($_POST['email']) && isset($_POST['pass'])) {
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users
    WHERE email = :em AND password = :pw');
    $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ( $row !== false ) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        exit();
      }else{
      $_SESSION['error'] = "Incorrect Password";
      header("Location: login.php");
      exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Xiaojie Liu's Login Page</title>
</head>
<body>


<div class="container">
<h1>Please Log In</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>

<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The account is umsi@umich.edu. The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        emailAdd = document.getElementById('nam').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+emailAdd+" pw="+pw);
        if (emailAdd == null || emailAdd == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if (emailAdd.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</div>
</body>
