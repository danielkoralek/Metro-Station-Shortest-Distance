<?php

include 'inc.pdoconnection.php';

$stmtList = $dbh->prepare("
    SELECT DISTINCT base_station
    FROM station_connection
    ORDER BY base_station
");

$stmtList->execute();
$arrList = $stmtList->fetchAll();

$selectSource = '<select class="sel-source form-control" name="selSource" id="selSource">' .
                    '<option selected="selected" value="0">Please select...</option>';

$selectDestin = '<select class="sel-destin form-control" name="selDestin" id="selDestin">' .
                    '<option selected="selected" value="0">Please select...</option>';

foreach($arrList as $station){
    $selectSource .= '<option value="' . $station['base_station'] . '">' . $station['base_station'] . '</option>';
    $selectDestin .= '<option value="' . $station['base_station'] . '">' . $station['base_station'] . '</option>';
}

$selectSource .= '</select>';
$selectDestin .= '</select>';

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
            </div>
            
            <div class="row">

                    <div class="col-lg-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Route Search                                
                            </div>
                            <div class="panel-body">

                                <form class="route-search-form">

                                    <div class="form-group">
                                        <label>Source:</label>
                                        <?php echo $selectSource; ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Destination:</label>
                                        <?php echo $selectDestin; ?>
                                    </div>

                                    <div class="form-group">
                                        <input id="btnSearch" type="button" value="Search" class="btn" /> or <a href="upload-map.php">Upload a map file</a>
                                    </div>

                                </form>
                                
                            </div>
                        </div>
                    </div>
          
                    <div class="col-lg-9">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Route Found
                            </div>
                            <div class="panel-body">

                                <div id="route">&nbsp;</div>

                            </div>
                        </div>
                            
                    </div>
                
            </div>
            
        </div>
        
        
        <script type="text/javascript">
            
            $(document).ready(function(){
                
                $("#btnSearch").on('click', function(){ 
                    
                    $("#route").html("Calculating the shortest path from '" + $("select.sel-source").val() + "' to '" + $("select.sel-destin").val() + "'..." );
                    
                    findRoute();
                });
                
                
                $("select.sel-source, select.sel-destin").on('change', function(){
                    $selSource = $("select.sel-source");
                    $selDestin = $("select.sel-destin");
                    if( ($selSource.val() !== '0') && ($selDestin.val() !== '0') ){
                        $("#btnSearch").attr("disabled", false);
                    } else {
                        $("#btnSearch").attr("disabled", true);
                    }
                });
                
                 $("select.sel-source").trigger('change');
                
            });
            
            function findRoute(){                
                doAjax(
                    'process-route_nm.php',
                    $("form.route-search-form"),
                    $("#route")
                );
            }
            
            function doAjax( aUrl, oForm, divRetorno ){
                var request = $.ajax({
                        type: "POST",
                        url: aUrl,
                        data: oForm.serialize()
                });
                request.done(function(retorno){ divRetorno.html(retorno); disableForm(false); });
                request.done(function(){ divRetorno.html(jqXHR.responseText); disableForm(false); });
            }            
            
            function disableForm(value){
                $("form.route-search-form input, form.route-search-form select").attr("disabled", value);
            }
            
        </script>
        
    </body>
</html>

