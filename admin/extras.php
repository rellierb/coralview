<?php
session_start();

include('../common/admin_header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

$edit_state = false;

if(isset($_GET['edit'])) {

    $id = $_GET['edit'];
    $query = "SELECT * FROM extras WHERE id=$id";
    $record = mysqli_query($db, $query);
    $record_result = mysqli_fetch_assoc($record);

    $id = $record_result['Id'];
    $description = $record_result['description'];
    $price = $record_result['price'];

    $edit_state = true;
    
}

?>

    <?php include('../common/admin_sidebar.php') ?>

    <div class="main-panel">
    
        <div class="container-fluid">
            <h1>Extras Maintenance</h1>

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
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="../../functions/admin/extras.php" method="POST">
                                <input type="hidden" name="extra_id" value="<?php if(isset($id)) { echo $id; } ?>"  />

                                <div class="form-group">
                                    <label for="">Description</label>
                                    <input type="text" name="extra_description" class="form-control" id="" value="<?php if(isset($description)) { echo $description;  } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="text" name="extra_price" class="form-control" id="" value="<?php if(isset($price)) { echo $price; } ?>">
                                </div>
                                
                                <?php 
                                
                                if($edit_state == true) {
                                    echo '<input type="submit" name="edit_extra" class="btn btn-primary btn-block" value="Save">';
                                } else {
                                    echo '<input type="submit" name="enter_extra" class="btn btn-primary btn-block" value="Save">';
                                }
                                
                                ?>
                                
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Description</th>
                                        <th class="text-center" scope="col">Price</th>
                                        <th class="text-center" scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                $query = "SELECT * FROM extras";
                                $result = mysqli_query($db, $query);
                            
                                if(mysqli_num_rows($result) > 0) {
                                    while($extras = mysqli_fetch_assoc($result)) {
                                        echo '
                                            <tr>
                                                <td class="text-center">' . $extras['description'] . '</td>
                                                <td class="text-center">' . number_format($extras['price'], 2)  . '</td>
                                                <td class="text-center" style="width: 17%;">
                                                    
                                                    <form action="../../functions/admin/extras.php" method="POST">
                                                        <a href="admin/maintenance/extras.php?edit=' . $extras['Id'] . '"class="btn btn-info" value="' . $extras['Id'] . '" >Edit</a>
                                                        <button class="btn btn-danger" value="' . $extras['Id'] . '" name="delete_extra">Delete</button>
                                                    </form>
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
    


<?php

include('../common/admin_footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);

?>