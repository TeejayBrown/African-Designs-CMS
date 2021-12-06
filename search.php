<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){

  if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
      //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $searchtext = strtolower($searchtext); 
      $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
      
      if (isset($categoryId)) {
      	$query = "SELECT * FROM designs WHERE (LOWER(name) LIKE '%$searchtext%' OR LOWER(description) LIKE '%$searchtext%') AND categoryId = '$categoryId'";
	      $statement = $db->prepare($query); //Catch the statement and wait for values
	      $statement->execute();
	      $results = $statement->fetchAll();
      } else {
      	$query = "SELECT * FROM designs WHERE (LOWER(name) LIKE '%$searchtext%' OR LOWER(description) LIKE '%$searchtext%')";
	      $statement = $db->prepare($query); //Catch the statement and wait for values
	      $statement->execute();
	      $results = $statement->fetchAll();
      }
			
      //header("Location: ?search=$searchtext&category=$categoryId");
  		
      $status = 1;
      
    //exit;
  } 
}


//$per_page_html = '';
/*if($_SERVER["REQUEST_METHOD"] == "POST"){

  if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
      //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $searchtext = strtolower($searchtext); 
      $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);     
      $keyword = $_POST['searchtext'];
      $qry = explode(" ", $keyword);
      $perpageview=6;    
      if (isset($_GET['page'])) {
          $page = $_GET['page'];
      } else {
          $page = 1;
      }
     $frompage = $page*$perpageview-$perpageview;
      $query = "SELECT * FROM designs WHERE (LOWER(name) LIKE '%$searchtext%' OR LOWER(description) LIKE '%$searchtext%') AND categoryId = '$categoryId' LIMIT $frompage , $perpageview";
      $query_count = $db->query("SELECT COUNT(*) FROM designs WHERE name OR description LIKE '%$searchtext%' AND categoryId = '$categoryId'")->fetchColumn();
      $stmt = $db->prepare($query);
    	$stmt->execute();
    	$results = $stmt->fetchAll();
      //$pagecount = ceil($query_count/$perpageview);
      
      if(!empty($row_count)){
				$per_page_html .= "<div style='text-align:center;margin:20px 0px;'>";
				$page_count=ceil($query_count/$perpageview);
				if($page_count>1) {
					for($i=1;$i<=$page_count;$i++){
						if($i==$page){
							$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
						} else {
							$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page" />';
					}
				}
			}
			$per_page_html .= "</div>";
		}
      $status = 1;
      //header("Location: index.php?search=$searchtext&page=$page");
      //exit;
    }
}*/

 
?> 