<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

/* Route n°1 : POST /users
 * Cette route permet d'ajouter un nouvel utilisateur.
 * 201 : Created User
 * 400 : ErrorResponse
 * 401 : Must be connected
 * 403 : Must be admin
 */
$app->post('/post/user', function(Request $request, Response $response){

    $lastname = $request->getParam('lastname');
    $firstname = $request->getParam('firstname');
    $email = $request->getParam('email');
    $password = $request->getParam('password');
    $role = $request->getParam('role');


    $sql = "INSERT INTO user (lastname,firstname,email,password,role) VALUES
    (:lastname,:firstname,:email,:password,:role)";

    try{
        // Get DB Object
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':lastname',  $lastname);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':password',   $password);
        $stmt->bindParam(':role',       $role);
        $stmt->execute();


        echo '{"notice": {"text": "Created User"}';
        //$app->response->setStatus(201);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

/* Route n°2 : PUT /user/{uid}
 * Cette route permet de modifier un utilisateur.
 * 202 : No datas ExistingUser
 * 401 : Must be connected
 * 403 : Must be admin
 * 404 : Not Found ErrorResponse
 */
$app->put('/put/user/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');
    $lastname = $request->getParam('lastname');
    $firstname = $request->getParam('firstname');
    $email = $request->getParam('email');
    $password = $request->getParam('password');
    $role = $request->getParam('role');

    $sql = "UPDATE user SET 
                        lastname  = :lastname,
                        firstname = :firstname,
                        email     = :email,
                        password  = :password,
                        role      = :role
            WHERE id = $id";
    try{
        // Get DB Object
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':lastname',   $lastname);
        $stmt->bindParam(':firstname',  $firstname);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':password',   $password);
        $stmt->bindParam(':role',       $role);
        $stmt->execute();


        echo '{"notice": {"text": "User Updated"}';
        //$app->response->setStatus(200);
    } catch(PDOException $e){
        $app->response->setStatus(404);
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

/* Route n°3 : DELETE /user/{uid}
 * Cette route permet de supprimer un utilisateur.
 * 204 : No datas
 * 401 : Must be connected
 * 403 : Must be admin
 * 404 : Not Found ErrorResponse
 */
$app->delete('/delete/user/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $sql = "DELETE FROM user WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $db = null;
        echo '{"notice": {"text": "User Deleted"}';
        //$app->response->setStatus(200);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

/* Route n°4 : GET /user/{uid}
 * Cette route permet de récupérer des informations sur un utilisateur.
 * 200 : User definition Object
 * 401 : Must be connected
 * 404 : Not Found ErrorResponse
 */
$app->get('/get/user/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM user WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
        //$app->response->setStatus(200);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

/* Route n°5 : GET /users
 * Cette route permet de récupérer les informations de tous les utilisateurs de la base de données.
 * 200 : An array of users
 * 401 : Must be connected
 */
$app->get('/get/users', function(Request $request, Response $response){
    $sql = "SELECT * FROM user ORDER BY id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

/* Route n°6 : /users/search
 * Cette route permet de chercher des utilisateurs soit par leur nom soit par leur email.
 * 200 : OK inline_response_200
 * 401 : Must be connected
 */
$app->get('/users/search/[{query}]', function ($request, $response, $args) {
    
    $sql = "SELECT firstname, email FROM user WHERE firstname LIKE :query OR email LIKE :query ORDER BY firstname";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $query = "%".$args['query']."%";
        $stmt->bindParam("query", $query);

        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
        //$app->response->setStatus(200);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
   
