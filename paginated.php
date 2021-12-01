<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
           //  Sanitize user input to escape HTML entities and filter out dangerous characters.
            $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);

            //Get Page
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }

            //Set Limit per page
            $limit = 6;
            $offset = ($page-1) * $limit;

            $total = $db->query("SELECT COUNT(*) FROM designs WHERE name OR description LIKE '%$searchtext%' AND categoryId = '$categoryId'")->fetchColumn();

            $pages = ceil($total / $limit);
            echo $pages;

            $stmt = $db->prepare("SELECT * FROM designs WHERE name OR description LIKE '%$searchtext%' AND categoryId = '$categoryId' LIMIT :limit OFFSET :offset");
            // Bind the query params
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            echo count($results);

            $status = 1;
        }
    }
?>