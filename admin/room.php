<?php
session_start();

include('../common/admin_header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

$edit_state = false;

if(isset($_GET['edit'])) {

    $id = $_GET['edit'];
    $query = "SELECT * FROM rooms WHERE id=$id";
    $record = mysqli_query($db, $query);
    $record_result = mysqli_fetch_assoc($record);
    $id = $record_result['Id'];
    $type = $record_result['type'];
    $number = $record_result['number'];
    $peak_rate = $record_result['peak_rate'];
    $off_peak_rate = $record_result['off_peak_rate'];
    $description = $record_result['description'];
    $edit_state = true;
}

?>

    <?php include('../common/admin_sidebar.php') ?>

    <div class="main-panel">
    
        <div class="container-fluid">
            <h1>Room Maintenance</h1>

            <?php
    
            if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                echo '
                    <div class="' . $_SESSION['alert'] . '" role="alert">
                        ' . $_SESSION['msg']  . '
                    </div>
                ';
            }
            
            ?>

            <?php
            // if(isset(($_SESSION['fileType']) || $_SESSION['fileExists'] || $_SESSION['fileSize'] || $_SESSION['fileImage'] || $_SESSION['fileError'])) {
                // 
            
            if(isset($_SESSION['alertType'])) {

                echo '<div class="' . $_SESSION['alertType'] . '" role="alert">';

                if(isset($_SESSION['fileType'])) {
                    echo $_SESSION['fileType'];
                }

                if(isset($_SESSION['fileExists'])) {
                    echo $_SESSION['fileExists'];
                }

                if(isset($_SESSION['fileSize'])) {
                    echo $_SESSION['fileSize'];
                }

                if(isset($_SESSION['fileImage'])) {
                    echo $_SESSION['fileImage'];
                }

                if(isset($_SESSION['fileError'])) {
                    echo $_SESSION['fileError'];
                }

                echo '</div>';
                
            }
            
            
            ?>

            <div class="row">
                <div class="col-12">
                    
                    <div class="card">

                        <div class="card-body">
                            <form action=".../functions/admin/rooms/insert_room.php" method="POST" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-4">
                                        <input type="hidden" name="room_id" value="<?php if(isset($id)) { echo $id; } ?>"  />
                                        <div class="form-group">
                                            <label for="">Number</label>
                                            <input type="text" name="room_number" class="form-control" id="" value="<?php if(isset($number)) { echo $number;  } ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Type</label>
                                            <input type="text" name="room_type" class="form-control" id="" value="<?php if(isset($type)) { echo $type; } ?>">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Peak Rate</label>
                                            <input type="number" name="room_peak_rate" class="form-control" id="" value="<?php if(isset($peak_rate)) { echo $peak_rate;  } ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Off-Peak Rate</label>
                                            <input type="number" name="room_off_peak_rate" class="form-control" id="" value="<?php if(isset($off_peak_rate)) { echo $off_peak_rate;  } ?>">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Descrption</label>
                                            <input type="text" name="room_description" class="form-control" id="" value="<?php if(isset($description)) { echo $description;  } ?>">
                                        </div>

                                        <label for="image">Image</label>
                                        <input type="file" name="room_image" class="form-control-file" accept="image/png, image/jpeg">
                                
                                    </div>

                                </div>
                                

                                <div class="row">
                                    <div class="col-12">
                                        <div class="float-right">
                                        <?php 
                                        
                                        if($edit_state == true) {
                                            echo '<input type="submit" name="edit_room" class="btn btn-primary" value="Save">';
                                        } else {
                                            echo '<input type="submit" name="enter_room" class="btn btn-primary" value="Save">';
                                        }
                                        
                                        ?>
                                        </div>
                                        
                                    
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                    
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Image</th>
                                        <th class="text-center" scope="col">Number</th>
                                        <th class="text-center" scope="col">Type</th>
                                        <th class="text-center" scope="col">Peak Rate</th>
                                        <th class="text-center" scope="col">Off-peak Rate</th>
                                        <th class="text-center" scope="col">Description</th>
                                        <th class="text-center" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM rooms";
                                $result = mysqli_query($db, $query);
                            
                                if(mysqli_num_rows($result) > 0) {
                                    while($room = mysqli_fetch_assoc($result)) {
                                        echo '
                                            <tr>
                                                <td><img src="../' . $room['image'] . '"></img></td>
                                                <td>' . $room['number'] . '</td>
                                                <td>' . $room['type'] . '</td>
                                                <td style="width: 8%;">' . $room['peak_rate'] . '</td>
                                                <td style="width: 8%;">' . $room['off_peak_rate'] . '</td>
                                                <td>' . $room['description'] . '</td>
                                                <td class="text-center" style="width: 15%;">
                                                    <a href="../../admin/maintenance/room.php?edit=' . $room['Id'] . '"class="btn btn-info" value="' . $room['Id'] . '" >Edit</a>
                                                    <button class="btn btn-danger" value="' . $room['Id'] . '" name="delete_room">Delete</button>
                                                </td>
                                            </tr>
                                        ';
                                    } 
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                </div>
            </div>
        </div>

    </div>
    


<?php

include('../common/admin_footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);

?>