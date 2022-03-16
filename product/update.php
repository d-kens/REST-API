<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-MAx-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization,X-Requested-With");

// include database and object files
include_once '../Config/database.php';
include_once '../objects/product.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// get the id of the product to be edited
$data = json_decode(file_get_contents("php://input"));

// set the ID property of the product to be edited
// Validation should be included to check if the id exists in the databse
$product->id = $data->id;

// set product property values
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// update the prooduct
if($product->update()){
    // set response code - 200 ok
    http_response_code(200);

    //tell the user
    echo json_encode(array("message" => "Product was updated"));

}// if unable to update product
else{
    // set response code - 503 service unaavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update product"));
}