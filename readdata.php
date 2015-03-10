<?php
$readfile=file($uploadPath.$name);
	for($k=0; $k<=count($readfile); $k++)
    {
      $fields=explode("\r",$readfile[$k]);
	  for($i=0; $i<=count($fields); $i++) 
	  {
	  $values=explode("\t",$fields[$i]);
		$columns = "'examid', 'question', 'type', 'flag_active'";
      $query=("INSERT into questions ($columns) VALUES           ('$values[0]','$values[1]','$values[2]','$values[3]')");
		echo $query;
        mysql_query($query) ;
		echo mysql_errno($link) . ": " . mysql_error($link) . "\n";
	  }
	}


//sort out line endings
ini_set('auto_detect_line_endings',true);

//connect to your database
$dbUser = "username";
$dbPass = "password";
$conn = new PDO('mysql:host=localhost;dbname=yourDB', $dbUser, $dbPass);

//read the file into an array
$input = file('yourFile.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//prepare the SQL statement
$query = $conn->prepare("INSERT INTO questions (examid, question, type, flag_active) VALUES (?,?,?,?)");

//loop through the array and insert the database records
$filedata=array();
foreach ($input as $line) {
    $filedata = explode("\t",$line);
    $query->execute($filedata);
}
?>