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
                
                <h2 class="text-center coralview-blue" style="margin-top: 5%;">See Reservation</h2>

                <?php

                if(isset($_SESSION['message']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION["alert"] . ' mb-3" style="width: 40vw; margin: 0 auto;" role="alert">
                            <p class="text-center">' . $_SESSION["message"]  . '</p>
                        </div>
                    ';
                }
                
                ?>

                <div class="card" style="width: 40vw; margin: 0 auto;">

                    <div class="card-body p-5" >

                        <div style="margin: 0 auto; display: block;">

                            <form action="functions/user/see_reservation.php" method="POST">
                                <div class="form-group" style="margin-left: 20%;">
                                    <label>Enter Reference Number</label>
                                    <input class="form-control" name="reference_num" type="text" style="width: 80%;" />
                                    <button type="submit" class="btn btn-primary">Enter</button>
                                </div>
                            </form>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>


<?php


include('common/footer.php');
session_destroy();

?>

