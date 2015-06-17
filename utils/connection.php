<?php
/**
 * Created by PhpStorm.
 * User: Rodrigo
 * Date: 15/06/2015
 * Time: 10:11 PM
 */

namespace triagens\ArangoDb;

require 'libs/arangodb-php/autoload.php';

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