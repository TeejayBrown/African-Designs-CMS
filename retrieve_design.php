<?Php
@$categoryId=$_GET['categoryId'];
//$categoryId=1;
if(!is_numeric($categoryId)){
echo "Data Error";
exit;
 }
/// end of checking injection attack ////
require('db_connect.php');

$query="SELECT designId, name FROM designs WHERE categoryId = '$categoryId'";
$statement=$db->prepare($query);
//$statement = bindParam(':categoryId', $categoryId, PDO::PARAM_INT,5);
$statement->execute();
$result=$statement->fetchAll(PDO::FETCH_ASSOC);

$main = array('data'=>$result);
echo json_encode($main);
?>