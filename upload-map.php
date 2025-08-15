<?php
/** 
 * PHP Test - Metro Route Finder
 * 
 * upload-map.php
 * 
 *      This is the script that will process the map text file uploaded,
 *      and load the data into the custom format, to the custom database.
 * 
 *      At this point, for the test purpose, I decided not to use a 
 *      normalized database structure. For a production implementation
 *      I would recommend to use the normalized id -> name format.
 * 
 * Author: Daniel Koralek
 * Change history:
 *      
 * Dec/04, 2016  
 *  Created 
 * 
 */

include 'inc.pdoconnection.php';

    /**
     * 
     */
    if(isset($_POST['act']) && trim($_POST['act'])=='upload'){
        
        
        if ( 0 < $_FILES['userfile']['error'] ) {
            
            $output = 'Error: ' . $_FILES['userfile']['error'];
            
        } elseif ( $_FILES['userfile']['type'] !== 'text/plain' ) {
            
            $output = "Sorry - The uploaded file '{$_FILES['userfile']['name']}' is not in the expected format {$_FILES['userfile']['type']}";
            
        } else {
            
            /* ===
             * Process the datafile
             * 
             *      This block will open the posted temp file and will
             *      load the content into an 2dim Array, which will be
             *      processed in a later step.
             * 
             *      File format is fine so far. Will stop processing if
             *      there is no content to load into the database.
             * 
             */
            
            
            $arrStations = Array();
            
            $tempfilename = $_FILES['userfile']['tmp_name'];
            $fhandler = fopen($tempfilename, 'r');
            while(!feof($fhandler)){
                $row = trim(fgets($fhandler));
                if($row!==''){
                    $arr = explode(':',$row);
                    $arrStations[trim($arr[0])] = trim($arr[1]); //explode(',', $arr[1]);
                }
            }
            
            /*
             * Display count information
             */
            
            $output = '<h4>' .count($arrStations) . ' stations read from source...</h4>';
            if(count($arrStations)==0){
                die('<strong>Nothing to do.</strong>');
            }
            
            /* ===
             * Learn Station connections (into an indexed style)
             * 
             *      Still for normalization purposes, this will re-map the 
             *      stations connections into a numbered key map, which will
             *      be loaded into the database.
             */
            
            $stationMap = Array();
            
            foreach($arrStations as $baseStation => $connections){
                
                $arrConnections = explode(",", $connections);
                foreach($arrConnections as $connStation){
                    $stationMap[] = array(
                        'base' => $baseStation,
                        'conn' => $connStation,
                        'cost' => 1
                    );
                }
                
            }
            
            /* ===
             * Clear the map table in the database.
             */
            
            $dbh->exec("TRUNCATE TABLE station_connection");
            
            /* ===
             * Load Connections Map
             * 
             */
            
            $stmInsertMap = $dbh->prepare("
                INSERT INTO station_connection (base_station, conn_station, vertex_cost)
                VALUES ( :baseStation, :connStation, :vertexCost)
            ");
            
            /*
             * Walk through the stations array and insert data
             * into the database
             */
            
            $insertSuccess = 0;
            foreach($stationMap as $map){
                
                try {
                    $stmInsertMap->execute(array(
                       ':baseStation' => $map['base'],
                       ':connStation' => $map['conn'],
                       ':vertexCost'  => $map['cost']
                    ));
                    $insertSuccess++;
                } catch (Exception $ex) {
                    die("Error inserting data for Connection " . $ex->getMessage());
                }

            }
            
            $output .= "<p><strong>$insertSuccess</strong> connections made.</p>";
            $output .= '<p>Please proceed to the <a href="index.php">Route Search</a> page.</p>';
            
        }
        
    } else {
        
        $output = "Please select the Map File to upload...";
        
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP Test - Metro Route Finder</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="theme/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="theme/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="theme/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="custom/custom.css" rel="stylesheet" type="text/css"/>
        
        <script src="theme/vendor/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="theme/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    
    </head>
    <body>
        <div class="wrapper container">
            
            <div class="jumbotron">
                <h3>PHP Test - Metro Route Finder</h3>
                <p>Map Loader</p>
            </div>

            <div class="row">

                <div class="col-lg-5">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Upload Map Data
                        </div>
                        <div class="panel-body">

                            <form id="frmUpload" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="form-group">
                                    <label class="btn btn-primary" for="my-file-selector">
                                        <input id="my-file-selector" name="userfile" type="file" style="display:none;" onchange="$('#upload-file-info').html( $(this).val() +  '&nbsp;|&nbsp;' + Math.ceil(this.files[0].size/1024) + ' kb');">
                                        Browse File
                                    </label>
                                    <span class='label label-info' id="upload-file-info"></span>                                    
                                </div>
                                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                                <input type="hidden" name="act" value="upload" />
                                <button id="btnUpload" class="btn btn-default" type="submit">Upload</button>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Upload Status
                        </div>
                        <div class="panel-body">  
                            <?php echo $output; ?>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>

    </body>
</html>

