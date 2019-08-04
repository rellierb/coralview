<?php
session_start();

include('../../common/header.php');
require('../../functions/assets/connection.php');

$db = connect_to_db();

$edit_state = false;

$edit_state = false;

if(isset($_GET['edit'])) {

    $id = $_GET['edit'];
    $query = "SELECT * FROM users WHERE id=$id";
    $record = mysqli_query($db, $query);
    $record_result = mysqli_fetch_assoc($record);

    $id = $record_result['id'];
    $full_name = $record_result['FullName'];
    $user_name = $record_result['UserName'];
    $password = $record_result['Password'];
    $email = $record_result['Email'];
    $type = $record_result['Type'];
    $phone_number = $record_result['PhoneNumber'];
    $edit_state = true;

}

?>

    <?php include('../../common/admin_sidebar.php') ?>

    <div class="main-panel">
    
        <div class="container-fluid">
            <h1>User Maintenance</h1>

            <?php
    
            if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                echo '
                    <div class="' . $_SESSION['alert'] . '" role="alert">
                        ' . $_SESSION['msg']  . '
                    </div>
                ';
            }
            
            ?>

            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="../../functions/admin/user.php" method="POST">
                                <input type="hidden" name="user_id" value="<?php if(isset($id)) { echo $id; } ?>"  />
                                <div class="form-group">
                                    <label for="">Full Name</label>
                                    <input type="text" name="user_full_name" class="form-control" id="" value="<?php if(isset($full_name)) { echo $full_name;  } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">User Name</label>
                                    <input type="text" name="user_name" class="form-control" id="" value="<?php if(isset($user_name)) { echo $user_name; } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" name="user_password" class="form-control" id="" value="<?php if(isset($password)) { echo $password;  } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" name="user_email" class="form-control" id="" value="<?php if(isset($email)) { echo $email;  } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">User Type</label>
                                    <select name="user_type" class="form-control" value="<?php if(isset($type)) { echo $type;  } ?>">
                                        <option value="Administrator">Administrator</option>
                                        <option value="Receptionist">Receptionist</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Phone Number</label>
                                    <input type="number" name="user_phone_number" class="form-control" id="" value="<?php if(isset($phone_number)) { echo $phone_number;  } ?>">
                                </div>
                                
                                <?php 
                                
                                if($edit_state == true) {
                                    echo '<input type="submit" name="edit_user" class="btn btn-primary btn-block" value="Save">';
                                } else {
                                    echo '<input type="submit" name="enter_user" class="btn btn-primary btn-block" value="Save">';
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
                                        <th class="text-center" scope="col">Full Name</th>
                                        <th class="text-center" scope="col">User Name</th>
                                        <th class="text-center" scope="col">Email</th>
                                        <th class="text-center" scope="col">Type</th>
                                        <th class="text-center" scope="col">Phone Number</th>
                                        <th class="text-center" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM users";
                                $result = mysqli_query($db, $query);
                            
                                if(mysqli_num_rows($result) > 0) {
                                    while($user = mysqli_fetch_assoc($result)) {
                                        echo '
                                            <tr>
                                                <td>' . $user['FullName'] . '</td>
                                                <td>' . $user['UserName'] . '</td>
                                                <td>' . $user['Email'] . '</td>
                                                <td>' . $user['Type'] . '</td>
                                                <td>' . $user['PhoneNumber'] . '</td>
                                                <td class="text-center" style="width: 17%;">
                                                    
                                                    <form action="../../functions/admin/user.php" method="POST">
                                                        <a href="/coralview/admin/maintenance/user.php?edit=' . $user['id'] . '"class="btn btn-info" value="' . $user['id'] . '" >Edit</a>
                                                        <button class="btn btn-danger" value="' . $user['id'] . '" name="delete_user">Delete</button>
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

include('../../common/footer.php');
session_destroy();

?>