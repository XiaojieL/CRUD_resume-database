<?php
require_once "pdo.php";
require_once 'util.php';

session_start();

if (!isset($_SESSION['name']) ) {
    die("ACCESS DENIED");
}

if(isset($_POST['cancel'])){
  header( 'Location: index.php' ) ;
  return;
}


// Handle the incoming data
if ( isset($_POST['first_name']) && isset($_POST['last_name']) &&
     isset($_POST['email']) && isset($_POST['headline']) &&
     isset($_POST['summary']) ) {

    $msg = validateProfile();
    if ( is_string($msg) ) {
        $_SESSION['error'] = $msg;
        header('Location: add.php');
        return;
    }

    // Validate position entries if present
    $msg = validatePos();
    if ( is_string($msg) ) {
        $_SESSION['error'] = $msg;
        header('Location: add.php');
        return;
    }


    $stmt = $pdo->prepare('INSERT INTO Profile
          (user_id, first_name, last_name, email, headline, summary)
          VALUES ( :id, :firstname, :lastname, :email, :headline, :summary)');
      $stmt->execute(array(
          ':id' => $_SESSION['user_id'],
          ':firstname' => $_POST['first_name'],
          ':lastname' => $_POST['last_name'],
          ':email' => $_POST['email'],
          ':headline' => $_POST['headline'],
          ':summary' => $_POST['summary'])
      );

    $profile_id = $pdo->lastInsertId();

    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
    $_SESSION['success'] = "Profile added";
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Xiaojie Liu</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h2>Adding Profile for <?php echo($_SESSION['name'])?></h2>
<?php
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>Position: <input type="submit" onclick="addPosition(); return false;" value="+">
<div id="position_fields">
</div>
</p>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>

<script>
  countPos = 0;

  function addPosition() {
      window.console && console.log("Adding position");
      if ( countPos >= 9 ) {
          alert("Maximum of nine position entries exceeded");
          return;
      }
      countPos++;

      var div = document.createElement('div');
      div.className = 'position';
      div.innerHTML =
  '<p>Year: <input type="text" name="year'+countPos+'" value="" /> \
  <input type="button" value="-" onclick="removePosition(this)"></p>\
  <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>';

       document.getElementById('position_fields').appendChild(div);
  }

  function removePosition(input) {
      document.getElementById('position_fields').removeChild( input.parentNode.parentNode );
  }

</script>
</form>
</div>
</body>
</html>
