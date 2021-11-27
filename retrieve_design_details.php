<?Php
@$designId=$_GET['designId'];
//$designId=58;
if(!is_numeric($designId)){
echo "Data Error";
exit;
 }
/// end of checking injection attack ////
require('db_connect.php');

$query="SELECT designId, name, description FROM designs WHERE designId = '$designId'";
$statement=$db->prepare($query);
//$statement = bindParam(':categoryId', $categoryId, PDO::PARAM_INT,5);
$statement->execute();
$result=$statement->fetchAll(PDO::FETCH_ASSOC);
//htmlspecialchars_decode($result);

$main = array('data'=>$result);
echo json_encode($main);
?>