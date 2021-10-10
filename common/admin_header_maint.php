<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Coralview Beach Resort</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../resources/paperkit/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../resources/paperkit/assets/css/paper-kit.min.css" />
    <link rel="stylesheet" href="../../resources/test.css" />
    <link rel="stylesheet" href="../../resources/smart-wizard/dist/css/smart_wizard.css" type="text/css" />   
    
    <?php
    
    $site_uri = $_SERVER['REQUEST_URI'];
    
    if(strpos($site_uri, 'reports') != true) {

        echo '<link rel="stylesheet" href="../../resources/air-datepicker/dist/css/datepicker.min.css" type="text/css" />';
    }

    ?>
    
    <link rel="stylesheet" href="../../resources/toastr/toastr.css" type="text/css" /> 
    <link rel="stylesheet" href="../../resources/datatables/datatable.css" type="text/css" /> 

    <link rel="stylesheet" href="../../css/animate.css" />
    <link rel="stylesheet" href="../../css/style.css" />
    
    <script src="https://kit.fontawesome.com/7bbfe16282.js"></script>
    

</head>
<body class=""> 
<!-- animated fadeIn delay-1s -->