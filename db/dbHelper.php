<?php
/**
 * Created by PhpStorm.
 * User: Rodrigo
 * Date: 15/06/2015
 * Time: 9:56 PM
 */

namespace triagens\ArangoDb;

include_once realpath($_SERVER["DOCUMENT_ROOT"]).'/utils/connection.php';

// register the connection to ArangoDB
$connection = new Connection(getConnectionOptions());

// register a collection handler to work with the 'users' collection
$collectionHandler = new CollectionHandler($connection);

function register($connection, $registrationData)
{
    // This would be where you call the function that encrypts your password like you did for storage earlier
    $hashedPassword = md5($registrationData['password']);

    // assign the collection to a var (or type it directly into the methods parameters)
    $collectionId = 'users';

    //create an example document or an array in order to pass to the following byExample method
    $document = Document::createFromArray(
        array('username' => $registrationData['username'],
            'password' => $hashedPassword,
            'name' => $registrationData['name'],
            'lastname' => $registrationData['lastname'],
            'email' => $registrationData['email'],)
    );

    // Get an instance of the collection handler
    $documentHandler = new DocumentHandler($connection);

    try {
        // query the given $collectionId by example using the previously declared $exampleDocument array
        $result = $documentHandler->save($collectionId, $document);

        // return the result;
        return $result;
    } catch (Exception $e) {

        if ($e->getCode()) {
            echo ('User already exists... ');
        } else {
            // any other error
            echo ('An error occured. Exception: ' . $e);
        }
    }
}


// This function tries to authenticate the user and will return an array with its data
function authenticate($connection, $username, $password)
{
    // This would be where you call the function that encrypts your password like you did for storage earlier
    $hashedPassword = md5($password);

    // assign the collection to a var (or type it directly into the methods parameters)
    $collectionId = 'users';

    //create an example document or an array in order to pass to the following byExample method
    $exampleDocumentArray = array('username' => $username, 'password' => $hashedPassword);

    // Get an instance of the collection handler
    $documentHandler = new CollectionHandler($connection);

    try {
        // query the given $collectionId by example using the previously declared $exampleDocument array
        $cursor = $documentHandler->byExample($collectionId, $exampleDocumentArray);
        // check if the count of the cursor is one or not.
        if ($cursor->getCount() == 1) {
            // do some fancy login stuff here...

            // get the current document from the cursor
            $userDocument = $cursor->current();

            // set session uid to the document key that was set automatically by ArangoDB,
            // since we didn't provide our own on registration
            session_start();
            $_SESSION['uid'] = $userDocument->getKey();
            $_SESSION['username'] = $userDocument->get('username');
            $_SESSION['name'] = $userDocument->get('name').' '.$userDocument->get('lastname');
            $_SESSION['loggedin'] = true;

            // extract and return the document in form of an array
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo ('An error occured. Exception: ' . $e . '<br>');
    }
}

function getUserInfo($connection, $uid){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'for user in users filter user._key == "'.$uid.'" return {
                                name : user.name,
                                lastname : user.lastname,
                                username : user.username,
                                user_id : user._key
                            }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        //print $statement . "\n\n";
        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function createBoard($connection, $data){

    $collectionId = 'boards';


    $document = Document::createFromArray(
        array('id_user' => $data['uid'],
            'name' => $data['name'],
            'description' => $data['description'],
            'date' => date("Y-m-d H:i:s"),
            'is_private' => $data['private'],
            'id_category' => $data['category']
            )
    );

    // Get an instance of the collection handler
    $documentHandler = new DocumentHandler($connection);

    try {
        // query the given $collectionId by example using the previously declared $exampleDocument array
        $result = $documentHandler->save($collectionId, $document);

        // return the result;
        return $result;
    } catch (Exception $e) {

        if ($e->getCode()) {
            echo ('User already exists... ');
        } else {
            // any other error
            echo ('An error occured. Exception: ' . $e);
        }
    }
}

function getBoardInfo($connection, $board){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'for board in boards filter board._key == "'.$board.'" return {
                                name : board.name,
                                description : board.description,
                                board_id : board._key
                            }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        //print $statement . "\n\n";
        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function getBoardsbyUser($connection, $uid){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'for board in boards filter board.id_user == "'.$uid.'" return {
                                name : board.name,
                                board_id : board._key
                            }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        //print $statement . "\n\n";
        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function getImagesByBoard($connection, $board){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'for image in images filter image.id_board == "'.$board.'"
                            return {
                                description : image.description,
                                ruta : image.ruta,
                                id_image : image._key
                            }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function getAllImages($connection){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'FOR image IN images
                            FOR user IN users
                              FILTER image.id_user == user._key
                              RETURN {
                                ruta : image.ruta,
                                username : user.username,
                                id_user : user._key
                              }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function getCategories($connection){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'for category in categories return {
                                name : category.name,
                                category_id : category._key
                            }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        //print $statement . "\n\n";
        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function createPin($connection, $data){

    $collectionId = 'images';


    $document = Document::createFromArray(
        array('type' => 'remote',
            'ruta' => $data['img_web'],
            'description' => $data['description'],
            'date' => date("Y-m-d H:i:s"),
            'id_user' => $data['uid'],
            'id_board' => $data['id_board']
        )
    );

    // Get an instance of the collection handler
    $documentHandler = new DocumentHandler($connection);

    try {
        // query the given $collectionId by example using the previously declared $exampleDocument array
        $result = $documentHandler->save($collectionId, $document);

        // return the result;
        return $result;
    } catch (Exception $e) {

        if ($e->getCode()) {
            echo ('User already exists... ');
        } else {
            // any other error
            echo ('An error occured. Exception: ' . $e);
        }
    }
}

function insertFollow($connection, $uid, $user, $board, $image){
    $collectionId = 'follows';


    $document = Document::createFromArray(
        array('id_user' => $uid,
            'id_user_follow' => $user,
            'id_board_follow' => $board,
            'id_image_follow' => $image,
        )
    );

    // Get an instance of the collection handler
    $documentHandler = new DocumentHandler($connection);

    try {
        // query the given $collectionId by example using the previously declared $exampleDocument array
        $result = $documentHandler->save($collectionId, $document);

        // return the result;
        return $result;
    } catch (Exception $e) {

        if ($e->getCode()) {
            echo ('User already follow... ');
        } else {
            // any other error
            echo ('An error occured. Exception: ' . $e);
        }
    }
}

function getUserFollows($connection, $uid){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'FOR f IN follows
                          FOR u IN users
                            FILTER f.id_user == "'.$uid.'"
                                && f.id_user_follow == u._key
                            RETURN {
                             "id_user_follow" : u.id_user,
                             "user" : u.name,
                             "lastname" : u.lastname
                          }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        //print $statement . "\n\n";
        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}

function getBoardsFollows($connection, $uid){
    try {

        $statement = new Statement($connection, array(
            "query"     => 'FOR f IN follows
                          FOR u IN users
                           FOR b IN boards
                            FILTER f.id_user == "'.$uid.'"
                                && b.id_user == u._key
                                    && f.id_board_follow == b._key
                            RETURN {
                             "id_board" : b._key,
                             "name" : b.name,
                             "user_id" : b.id_user
                          }',
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
        ));

        //print $statement . "\n\n";
        $cursor = $statement->execute();

        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }

        return $resultingDocuments;

    } catch (ConnectException $e) {
        print $e . PHP_EOL;
    } catch (ServerException $e) {
        print $e . PHP_EOL;
    } catch (ClientException $e) {
        print $e . PHP_EOL;
    }
}