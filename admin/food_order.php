<?php
session_start();

include('../common/admin_header_maint.php');
require('../functions/assets/connection.php');

$db = connect_to_db();


?>

    <?php include('../../common/admin_sidebar.php') ?>

    <div class="main-panel">
        <div class="container-fluid">
            <h1>Food Order</h1>
            
            <?php
    
            if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                echo '
                    <div class="' . $_SESSION['alert'] . ' text-center" role="alert">
                        ' . $_SESSION['msg']  . '
                    </div>
                ';
            }
            
            ?>
                
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-secondary float-left mr-2" data-toggle="modal" data-target="#orderFood">Order Food</button>
                    <div style="width: 70%; background-color: white; padding: 15px;">
                    
                        <table class="table" id="roomStatusTable">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Reference Number</th> 
                                    <th scope="col">Food Order Subject</th> 
                                    <th scope="col">Food Order Description</th> 
                                </tr>                            
                            </thead>
                            
                            <tbody>
                                <?php

                                $food_query = "SELECT * FROM food";
                                $food_result = mysqli_query($db, $food_query);

                                while($food = mysqli_fetch_assoc($food_result)) {
                                        
                                    echo '
                                        <tr>
                                            
                                            <td>' . $food["reference_no"] . '</td>
                                            <td>' . $food["subject"] . '</td>
                                            <td>' . $food["description"] . '</td>
                                            </td>
                                        </tr> 
                                    ';
                                }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>

                    

                </div>
            </div>

        </div>
    </div>




<?php

include('../common/order_food.php');
include('../../common/admin_footer_maint.php');
unset($_SESSION['alert']);
unset($_SESSION['msg']);

?>