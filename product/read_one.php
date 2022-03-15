<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$product = new Product($db);

// set ID property of the product to read
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the deatails of the product to be edited
$product->readOne();

if($product->name != null){
    // create an array
    $product_arr = array(
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name
    );

    // set http responce - 200 OK
    http_response_code(200);

    // tell the user
    echo json_encode($product_arr);
}
else{
    // set response code - 404 No found
    http_response_code(404);

    // tell the user
    echo json_encode(array("message" => "Product does not exist"));
}
