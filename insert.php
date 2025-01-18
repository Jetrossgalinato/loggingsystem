 <?php
 session_start();
 $server = 'localhost';
 $username = 'root';
 $password = '';
 $database = 'qrcodedb';

 $conn = new mysqli($server, $username, $password, $database);

 if ($conn->connect_error) {
     die('Connection failed: ' . $conn->connect_error);
 }

 if (isset($_POST['text'])) {
     $text = $_POST['text'];
     $sql = "INSERT INTO table_attendance (student_id,time_in) VALUES ('$text',now())";
     if ($conn->query($sql) === true) {
         $_SESSION['success'] = 'New record created successfully';
     } else {
         $_SESSION['error'] = $conn->error;
     }
     header('Location: index.php');
 }
 $conn->close();


?>
