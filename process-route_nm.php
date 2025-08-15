<?php
/** 
 * PHP Test - Metro Route Finder
 * 
 * process-route.php
 * 
 *      This is the script that will perform the search for the shortest
 *      route between two known stations. The main idea is to load the 
 *      stations and connections maps from the database and load them into
 *      the OrientDB implementation for the "DIJKSTRA algorithm", used to
 *      find the shortest path.
 * 
 * Author: Daniel Koralek
 * Change history:
 *      
 * Dec/04, 2016  
 *  Created 
 * 
 */
//require 'inc.pdoconnection.php';


require "Doctrine/OrientDB/Graph/GraphInterface.php";
require "Doctrine/OrientDB/Graph/Graph.php";
require "Doctrine/OrientDB/Graph/VertexInterface.php";
require "Doctrine/OrientDB/Graph/Vertex.php";
require "Doctrine/OrientDB/Graph/Algorithm/AlgorithmInterface.php";
require "Doctrine/OrientDB/Graph/Algorithm/Dijkstra.php";

require 'inc.pdoconnection.php';

    $sourceKey = filter_input(INPUT_POST, 'selSource');
    $destinKey = filter_input(INPUT_POST, 'selDestin');
    
    /*
     * Load Vertex List
     */
    
    $stmtConnectionList = $dbh->prepare("
        SELECT base_station, conn_station, vertex_cost
        FROM station_connection
        ORDER BY base_station, conn_station
    ");
    
    $stmtConnectionList->execute();
    $arrConnectionList = $stmtConnectionList->fetchAll();
    
    
    /*
     * Create Vertices instances and assign the connections
     * for each one.
     */

    $arrStation = Array();
    $counter = 0;
    foreach($arrConnectionList as $connection){
        
        $baseStation = trim($connection['base_station']);
        $connStation = trim($connection['conn_station']);
        $vertexCost  = trim($connection['vertex_cost']);
        
        if(!array_key_exists($baseStation, $arrStation)){
            $arrStation[$baseStation] = new Doctrine\OrientDB\Graph\Vertex($baseStation);
        }
        
        if(!array_key_exists($connStation, $arrStation)){
            $arrStation[$connStation] = new Doctrine\OrientDB\Graph\Vertex($connStation);
        }

        $arrStation[$baseStation]->connect($arrStation[$connStation], $vertexCost);
        
    }

    /*
     * Start Graph and Load Vertices Items
     */
    
    $graph = new Doctrine\OrientDB\Graph\Graph();

    foreach($arrStation as $station){
        $graph->add($station);
    }

    /*
     * Load the Dijkstra Algorithm lib and set the 
     * Starting and Ending vertexes (stations)
     */

    $algorithm = new Doctrine\OrientDB\Graph\Algorithm\Dijkstra($graph);
    $algorithm->setStartingVertex($arrStation[$sourceKey]);
    $algorithm->setEndingVertex($arrStation[$destinKey]);
    
    /*
     * SOLVE it! Calls the algorithm method that will finally solve the
     * shortes path between the Starting and the Ending points.
     */
    
    $path = $algorithm->solve();

    /*
     * Build path ouput
     * 
     *      I know, I know this could be better. But right now it is
     *      Sunday 11:59PM and I need to finish this test.
     * 
     *      Consider enhancing this output in the next release... :)
     * 
     */
    
    $icon = ' <i class="fa fa-arrow-circle-right station-arrow" aria-hidden="true"></i> ';
    $pathL = '';
    foreach ($path as $p) {
        if($pathL !== ''){
            $pathL .= $icon;
        }
        $pathL .= '<span class="alert alert-info station-tag">' . $p->getId() .'</span>';
    }

    echo '<h4>Stops: ' . $algorithm->getDistance() . '</h4>';
    echo $pathL;
    
?>