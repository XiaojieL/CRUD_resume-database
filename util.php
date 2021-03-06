<?php

function validateProfile() {
    if ( strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 ||
         strlen($_POST['email']) == 0 || strlen($_POST['headline']) == 0 ||
         strlen($_POST['summary']) == 0 ) {
        return "All fields are required";
    }

    if ( strpos($_POST['email'], '@') === false ) {
        return "Email address must contain @";
    }
    return true;
}

// Look through the POST data and return true or error msg
function validatePos() {
   for($i=1; $i<=9; $i++) {
       if ( ! isset($_POST['year'.$i]) ) continue;   //missing data's not a problem
       if ( ! isset($_POST['desc'.$i]) ) continue;   //i.e. skip the ones that aren't there
       $year = $_POST['year'.$i];
       $desc = $_POST['desc'.$i];
       if ( strlen($year) == 0 || strlen($desc) == 0 ) {  //if they are there, do the validation
           return "All fields are required";
       }

       if ( ! is_numeric($year) ) {
           return "Position year must be numeric";
       }
   }
   return true;
}

function loadPos($pdo, $profile_id) {
   $stmt = $pdo->prepare('SELECT * FROM Position
       WHERE profile_id = :prof ORDER BY rank');
   $stmt->execute(array( ':prof' => $profile_id)) ;
   $positions = array();
   while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
       $positions[] = $row;
   }
   return $positions;
}
