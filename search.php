<?php

/*$limitpages = 6;
$page_counter = 0;
$next = $page_counter + 1;
$previous = $page_counter - 1;


$totalRecordsPerPage=4;

$searchQuery = '';
$categodySearched = '';
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
	    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
	    $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	    $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);

	    $searchQuery = $searchtext;
			$categodySearched = $categoryId;
	 }
}*/
 //echo $searchQuery;
 //echo $categodySearched;
//Pagination Starts
/*if($_SERVER["REQUEST_METHOD"] == "POST"){
  if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
      //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
		try {

		    // Find out how many items are in the table
		    $total = $db->query("SELECT COUNT(*) FROM designs WHERE name OR description LIKE '%$searchtext%' AND categoryId = '$categoryId'")->fetchColumn();

		    // How many items to list per page
		    $limit = 6;

		    // How many pages will there be
		    $pages = ceil($total / $limit);

		    // What page are we currently on?
		    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
		        'options' => array(
		            'default'   => 1,
		            'min_range' => 1,
		        ),
		    )));

		    if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

		    // Calculate the offset for the query
		    $offset = 6; //($page - 1)  * $limit;
		    echo $offset;
		    echo $limit;

		    // Some information to display to the user
		    $start = $offset + 1;
		    $end = min(($offset + $limit), $total);

		    // The "back" link
		    $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

		    // The "forward" link
		    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

		    // Display the paging information
		    echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';

		    // Prepare the paged query
		    $stmt = $db->prepare("SELECT * FROM designs WHERE name OR description LIKE '%$searchtext%' AND categoryId = '$categoryId' LIMIT :limit OFFSET :offset");

		    // Bind the query params
		    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		    $stmt->execute();
		    $results = $stmt->fetchAll();

		} catch (Exception $e) {
		    echo '<p>', $e->getMessage(), '</p>';
		}
	}
}*/
/*
include ('paginated.php');

if (isset($_GET['page'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
    // Sanitize the id.
    $page = $_GET['page'];
    echo $page;
    //Set Limit per page
    $limit = 6;
    $offset = ($page-1) * $limit;

    //$total = $db->query("SELECT COUNT(*) FROM designs WHERE name OR description LIKE '%$searchQuery%' AND categoryId = '$categodySearched'")->fetchColumn();
    $qry = "SELECT * FROM designs WHERE name OR description LIKE '%$searchQuery%' AND categoryId = '$categodySearched'";
      $statement = $db->prepare($qry); //Catch the statement and wait for values
      $statement->execute();
      $res = $statement->fetchAll();

    $pages = ceil(count($res) / $limit);

    echo $pages;

    $stmt = $db->prepare("SELECT * FROM designs WHERE name OR description LIKE '%$searchQuery%' AND categoryId = '$categodySearched' LIMIT :limit OFFSET :offset");
    // Bind the query params
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll();
    echo count($results);
   $status = 1;
} 
*/
//Pagination ends

if($_SERVER["REQUEST_METHOD"] == "POST"){

  if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
      //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $searchtext = strtolower($searchtext); 
      $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
      

			$query = "SELECT * FROM designs WHERE (LOWER(name) LIKE '%$searchtext%' OR LOWER(description) LIKE '%$searchtext%') AND categoryId = '$categoryId'";
      $statement = $db->prepare($query); //Catch the statement and wait for values
      $statement->execute();
      $results = $statement->fetchAll();
      //header("Location: ?search=$searchtext&category=$categoryId");
  		
      $status = 1;
      
    //exit;
  } 
}
 
?> 