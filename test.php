<?php

namespace triagens\ArangoDb;


// use this and change it to the path to autoload.php of the arangodb-php client if you're using the client standalone...
// require __DIR__ . '/../vendor/triagens/ArangoDb/autoload.php';

// ...or use this and change it to the path to autoload.php in the vendor directory if you're using Composer/Packagist
require 'libs/arangodb-php/autoload.php';


// This function will provide us with our pre-configured connection options. 
function getConnectionOptions()
{
    $traceFunc = function ($type, $data) {
        print "TRACE FOR " . $type . PHP_EOL;
    };

    return array(
            // server endpoint to connect to
            ConnectionOptions::OPTION_ENDPOINT => 'tcp://192.168.0.24:8529/',
            // authorization type to use (currently supported: 'Basic')
            ConnectionOptions::OPTION_AUTH_TYPE => 'Basic',
            // user for basic authorization
            ConnectionOptions::OPTION_AUTH_USER => 'root',
            // password for basic authorization
            ConnectionOptions::OPTION_AUTH_PASSWD => '',
            ConnectionOptions::OPTION_DATABASE => 'pinterest',
            // connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
            ConnectionOptions::OPTION_CONNECTION => 'Close',
            // connect timeout in seconds
            ConnectionOptions::OPTION_TIMEOUT => 3,
            // whether or not to reconnect when a keep-alive connection has timed out on server
            ConnectionOptions::OPTION_RECONNECT => true,
            // optionally create new collections when inserting documents
            ConnectionOptions::OPTION_CREATE => true,
            // optionally create new collections when inserting documents
            ConnectionOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST,
        );
}


// This function tries to persist the user data into the database upon registration
// it will fail if a user with the same username already exists.
function register($connection, $username, $password, $registrationData)
{
    // This would be where you call the function that encrypts your password like you did for storage earlier
    $hashedPassword = md5($password);

    // assign the collection to a var (or type it directly into the methods parameters)
    $collectionId = 'users';

    //create an example document or an array in order to pass to the following byExample method
    $document = Document::createFromArray(
        array('username' => $username, 'password' => $hashedPassword, 'data' => $registrationData)
    );

    // Get an instance of the collection handler
    $documentHandler = new DocumentHandler($connection);

    try {
        // query the given $collectionId by example using the previously declared $exampleDocument array
        $result = $documentHandler->add($collectionId, $document);

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
            $_SESSION['uid'] = $userDocument->getKey();

            // extract and return the document in form of an array
            return $userDocument->getAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo ('An error occured. Exception: ' . $e . '<br>');
    }
}


// register the connection to ArangoDB
$connection = new Connection(getConnectionOptions());


// register a collection handler to work with the 'users' collection
$collectionHandler = new CollectionHandler($connection);


// create the 'users' collection...
// remark those lines if you want to create the collection by hand.
echo "creating 'users' collection...";
try {
    $collection = new Collection();
    $collection->setName('users');
    $collectionHandler->create($collection);
    echo "created.<br>";
} catch (Exception $e) {
    echo ('Could not create collection. Exception: ' . $e . '<br>');
}


// create unique skip list index in 'users' collection on field ''username'...
// remark those lines if you want to create the index by hand.
echo "creating unique skip list index in 'users' collection on field ''username'... ";
try {
    $collection = new Collection();
    $collection->setName('users');
    $collectionHandler->index('users', 'skiplist', array('username'), true);
    echo "created.<br>";
} catch (Exception $e) {
    echo ('Could not create skip list index. Exception: ' . $e . '<br>');
}


// let's assume those variables hold your username / password
$userNameProvided = 'jane';
$passwordProvided = 'mysecretpassword';

// here we pass some structured registration data
$registrationData = array(
    'name'      => 'Jane',
    'surname'   => 'Doe',
    'addresses' => array(
        'email' => array('jane@doe.com', 'jane2@doe.com'),
        'home'  => array(
            array('street' => 'Brooklyn Ave.', 'number' => 10),
            array('street' => '54th Street', 'number' => 340, 'is_primary' => true)
        )
    )
);

// First register
echo "trying to register user for the first time... ";
$result = register($connection, $userNameProvided, $passwordProvided, $registrationData);
if ($result) {
    echo " " . $userNameProvided . " registered<br>";
} else {
    echo "failed<br>";
}


// Trying to register user with same username a second time
echo "trying to register user with same username a second time... ";
$result = register($connection, $userNameProvided, $passwordProvided, $registrationData);
if ($result) {
    echo "registered<br>";
} else {
    echo "failed<br>";
}


// now authenticate with the correct username/password combination
echo "trying to authenticate with the correct username/password combination... ";
if ($userArray = authenticate($connection, $userNameProvided, $passwordProvided)) {
    echo "login successful. ";
    echo '<br>';
    // do some fancy after-login stuff here...
    echo "<br>Welcome back " . $userArray['username'] . '!<br>';
    if (count($userArray['data']['addresses']['email']) > 0) {
        echo "Your primary mail address is " . $userArray['data']['addresses']['email'][0] . '<br>';
    }
    foreach ($userArray['data']['addresses']['home'] as $key => $value) {
        if (array_key_exists('is_primary', $value)) {
            $homeAddress = $userArray['data']['addresses']['home'][$key];
            echo "Your primary home address is " . $homeAddress['number'] . ', ' . $homeAddress['street'] . '<br>';
            // if found, break out of the loop. There can be only one... primary address!
            break;
        }
    }
} else {
    // re-display login form. +1 the wrong-login counter...
    echo "wrong username or password<br>";
}
echo '<br>';

// now authenticate with the wrong username/password combination
echo "trying to authenticate with the wrong username/password combination... ";
if (authenticate($connection, $userNameProvided, 'I am a wrong password')) {
    // do some fancy after-login stuff here...
    echo "login successful<br>";
} else {
    // re-display login form. +1 the wrong-login counter...
    echo "wrong username or password<br>";
}


// truncate the collection... not needed if dropping, but only here to empty the collection of its tests
// in case you decide to not create and drop the collection through this script, but by hand.
echo "truncating collection...";
try {
    $collectionHandler->truncate('users');
    echo "truncated.<br>";
} catch (Exception $e) {
    die ('Could not truncate collection. Exception: ' . $e . '<br>');
}


// finally drop the collection...
// remark those lines if you want to drop the collection by hand.
echo "dropping collection...";
try {
    $collectionHandler->drop('users');
    echo "dropped.<br>";
} catch (Exception $e) {
    die ('Could not drop collection. Exception: ' . $e . '<br>');
}