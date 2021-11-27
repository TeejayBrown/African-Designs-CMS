<?Php
@$designId=$_GET['designId'];
//$designId=1;
if(!is_numeric($designId)){
echo "Data Error";
exit;
 }
/// end of checking injection attack ////
require('db_connect.php');

$query="SELECT commentId, comment_date, description, username FROM comments WHERE designId= '$designId' ORDER BY comment_date DESC";
$statement=$db->prepare($query);
//$statement = bindParam(':categoryId', $categoryId, PDO::PARAM_INT,5);
$statement->execute();
$result=$statement->fetchAll(PDO::FETCH_ASSOC);

$main = array('data'=>$result);
echo json_encode($main);
?>