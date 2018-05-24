 <?php
$servername = "localhost";
$username = "admin";
$password = "admin123";

/*try {
    $pdo = new PDO("mysql:host=$servername;dbname=online_test", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }*/
	 $pdo = new PDO("mysql:host=$servername;dbname=online_test", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
