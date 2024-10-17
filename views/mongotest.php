<?php
require_once "../vendor/autoload.php";
require_once "../services/dbConnect.php";
require_once "../services/importFromNoSqlToSql.php";

use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;
$uri = 'mongodb://localhost:27017/';
// Specify Stable API version 1
$apiVersion = new ServerApi(ServerApi::V1);
// Create a new client and connect to the server
$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

?>
    <div>
        <h1><a href="../index.php">Le Fabuleux Site De La Moviethèque</a></h1>
        <h2>Le test mongoDB</h2>
    </div>
<?php
try {
    // Send a ping to confirm a successful connection
    $client->selectDatabase('admin')->command(['ping' => 1]);
    echo "Pinged your deployment. You successfully connected to MongoDB!\n";
} catch (Exception $e) {
    printf($e->getMessage());
}

// We check if there is at least 100 movies in the sql database, if not we populate it
$sql = "SELECT COUNT(*) FROM movie";

// Request
//var_dump($dbh->query($sql)->fetchColumn());
//exit();
$conn = dbConnect::sqlGet($sql);
$data = $conn->fetchColumn();
if ($data >= 1000){
    ?>
        <br/>
        <h4>La BDD SQL est déjà bien peuplée! Aucune opération n'a été effectuée.</h4>
    <?php
} else {
    try {
        importFromNoSqlToSql();
    } catch(Exception $e){
        printf($e->getMessage());
    }
}