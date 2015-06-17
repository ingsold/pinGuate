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