<?php
$qry = "SELECT * FROM categories WHERE categoryId = $i";
$stmt = $db->prepare($qry);
$stmt->execute();
$categories = $stmt->fetch();

$query = "SELECT * FROM designs WHERE categoryId = $i ORDER BY RAND()";
try{
    $statement = $db->prepare($query);
     // Execution on the DB server is delayed until we execute().
     $statement->execute(); 
     $results = $statement->fetchAll();
} 
catch(Exception $ex) {
 echo ($ex -> getMessage());
}

?>