<?php

session_start();

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();


?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card" style="width: 45%; margin: 0 auto;">

                <?php
            
                if(isset($_SESSION['message']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION['alert'] . ' text-center" role="alert">
                            ' . $_SESSION['message']  . '
                        </div>
                    ';
                }
                
                ?>

                <div class="card-header">
                    <h2 class="text-center coralview-blue">Account Log in</h2>
                </div>
                <div class="card-body">
                    <form action="functions/user/log_in.php" method="POST">
                        <div class="form-group">
                            <label for="">Account Email</label>
                            <input type="text" name="account_email" class="form-control" id="">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="account_password" class="form-control" id="">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">LOG-IN</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php

session_destroy();
include('common/footer.php');

?>