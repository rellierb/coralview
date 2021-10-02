

<div class="sidebar coralview-blue-bg" data-color="white" data-active-color="danger" id="admin_sidebar">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
            
            </div>
        </a>
        <a href="http://www.creative-tim.com" class="simple-text logo-normal coralview-blue">
            CoralView
            <!-- <div class="logo-image-big">
            <img src="../assets/img/logo-big.png">
            </div> -->
        </a>

    </div>

    

    <div class="sidebar-wrapper">

        <?php 
        
        if(isset($_SESSION['full_name'])) {

            echo '
                
                <div style="margin-right: 15px; margin-left: 15px; margin-top: 15px;" >
                    <p class="text-center">WELCOME, ' . $_SESSION['full_name'] . ' </p>
                    <hr style="border: 1px solid #66615B; opacity: .3;">
                </div>
                    
            ';

        } 
        
        ?>



        <ul class="nav">
            <li>
                <a href="/coralview/admin/walk_in_reservation.php">
                    <!-- <i class="nc-icon nc-single-02"></i> -->
                    <p class="coralview-blue">Walk-in Reservation</p>
                </a>
            </li>

            <li>
                <a href="/coralview/admin/check_in.php">
                    <!-- <i class="nc-icon nc-single-02"></i> -->
                    <p class="coralview-blue">Check-in</p>
                </a>
            </li>
            <li>
                <a href="/coralview/admin/check_out.php">
                    <!-- <i class="nc-icon nc-single-02"></i> -->
                    <p class="coralview-blue">Check-out</p>
                </a>
            </li>
            <li>
                <a href="/coralview/admin/maintenance/room_number.php">
                    <!-- <i class="nc-icon nc-bell-55"></i> -->
                    <p class="coralview-blue">Room Availability</p>
                </a>
            </li>
            <li>
                <a href="/coralview/admin/list_reservation.php">
                    <!-- <i class="nc-icon nc-pin-3"></i> -->
                    <p class="coralview-blue">List of Reservations</p>
                </a>
            </li>
            <li>
                <a href="/coralview/admin/reports.php">
                    <!-- <i class="nc-icon nc-pin-3"></i> -->
                    <p class="coralview-blue">Reports Generation</p>
                </a>
            </li>



            <?php
            
            if(isset($_SESSION['account_type'])) {

                if($_SESSION['account_type'] == "Administrator") {

                    echo '
                        
                        <li>
                            <a href="/coralview/admin/maintenance/room.php">
                                <p class="coralview-blue">Room Maintenance</p>
                            </a>
                        </li>
                        <li>
                            <a href="/coralview/admin/maintenance/user.php">
                                <p class="coralview-blue">User Maintenance</p>
                            </a>
                        </li>
                        <li>
                            <a href="/coralview/admin/maintenance/extras.php">
                                <p class="coralview-blue">Extra Maintenance</p>
                            </a>
                        </li>
                    
                    ';

                }

            }
            
            
            ?>

            
        </ul>

        <div style="margin-right: 15px; margin-left: 15px; position: relative; top: 40%;">
            <hr>
            <a href="" class="btn btn-primary btn-link btn-block">Log out</a>
            <hr>
        </div>

        

    </div>
</div>
