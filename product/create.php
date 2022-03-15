<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get databse connection
include_once '../Config/database.php';

// instantiate product object
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// get posted data
// json_decode() is used to convert JSON object to PHP Object
$data = json_decode(file_get_contents("php://input"));

// Make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
){
    // Set product property values
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');

    // Create the product
    if($product->create()){
        // Set responce code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("message"=>"Product was created "));
    }
    // if uable to create product
    else{
        // set responce code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message" => "Unable to create product"));
    }
}
// tell the user data is incomplete
else {
    // set responce code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("message" => "Unable to create product"));
}