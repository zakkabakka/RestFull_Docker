<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

/*
* Faire un select de tout les admin et les mettre dans un tab et verifiers si l'utilisateur est parmis les admin ou pas
*
*/

// $sql = "SELECT lastname, password FROM user WHERE role = 'admin'";

// try{
//     $db = new db();
//     $db = $db->connect();
//     $stmt = $db->query($sql);
//     $data = $stmt->fetchAll();
//     // $db = null;
//     // echo json_encode($data);
//     // var_dump($data);
// } 
// catch(PDOException $e){
//     echo '{"error": {"text": '.$e->getMessage().'}';
// }

// foreach ($data as $value) {

    // if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== $value['lastname'] || $_SERVER['PHP_AUTH_PW']  !== $value['password']) {
     
    //     header("WWW-Authenticate: Basic realm=\"Secure Page\"");
    //     header("HTTP\ 1.0 401 Unauthorized");
    //     echo 'No soup for you';
    //     //exit;
    // }
// }


$app->get('/hello/{name}', function (Request $request, Response $response) {

    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

require '../src/routes/customers.php';

$app->run();