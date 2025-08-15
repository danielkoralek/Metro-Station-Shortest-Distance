<?php
/* ===
 * Connect to the Database
 * 
 *      This is a simple PDO connection that will be used
 *      to store the data into the database.
 * 
 *      Setting SET NAMES utf8;
 *      Quick rule: Existing data will be truncated before load.
 * 
 */

    try {

        $dbh = new PDO(
            'mysql:host=localhost;dbname=metrodan', 
            'xxx', 
            'xxx',
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") 
        );

    } catch (Exception $ex) {

        die("Error connecting database : " . $ex->getMessage());

    }
    
?>
