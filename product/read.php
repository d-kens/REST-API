<?php
// Required headers
header("Access-Control-Allow-Origin: *"); // Who can read this file - Anyone because of the * sign
header("Content-Type: application/json; charseet=UTF-8"); // Which type of content will be returned - JSON format


// Include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';

// Instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// Initialize object
$product = new Product($db);


// Query products
$stmt = $product->read();
$num = $stmt->rowCount();

// Check if more than 0 record found
if($num > 0) {
    // products array
    $products_arr = array();
    $products_arr['records'] = array();

    // Retrieve our table contents, fetch is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to $name
        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        array_push($products_arr['records'], $product_item);
    }

    //set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($products_arr);
}
else {
    // set response code - 404 Not Found
    http_response_code(404);

    //tell the user no products found
    echo json_encode(
        array("message" => "No produtcs found.")
    );
}