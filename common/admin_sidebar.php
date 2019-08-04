

<div class="sidebar" data-color="white" data-active-color="danger">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
            
            </div>
        </a>
        <a href="http://www.creative-tim.com" class="simple-text logo-normal">
            Coral View
            <!-- <div class="logo-image-big">
            <img src="../assets/img/logo-big.png">
            </div> -->
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li>
                <a href="/coralview/admin/check_in.php">
                    <!-- <i class="nc-icon nc-single-02"></i> -->
                    <p>Check-in</p>
                </a>
            </li>
            <li>
                <a href="/coralview/admin/maintenance/room_number.php">
                    <!-- <i class="nc-icon nc-bell-55"></i> -->
                    <p>Room Availability</p>
                </a>
            </li>
            <li>
                <a href="/coralview/admin/list_reservation.php">
                    <!-- <i class="nc-icon nc-pin-3"></i> -->
                    <p>List of Reservations</p>
                </a>
            </li>

            <?php
            
            if(isset($_SESSION['account_type'])) {

                if($_SESSION['account_type'] == "Administrator") {

                    echo '
                        
                        <li>
                            <a href="/coralview/admin/maintenance/room.php">
                                <p>Room Maintenance</p>
                            </a>
                        </li>
                        <li>
                            <a href="/coralview/admin/user.php">
                                <p>User Maintenance</p>
                            </a>
                        </li>
                    
                    ';

                }

            }
            
            
            ?>

            
        </ul>
    </div>
</div>
