<?php
session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();
$date_today = date("Y-m-d");

$off_peak_date_start_1 = strtotime("Y-m-d", strtotime("01/02/2019"));
$off_peak_date_end_1 = strtotime("Y-m-d", strtotime("03/11/2019"));

$off_peak_date_start_2 = strtotime("Y-m-d", strtotime("07/18/2019"));
$off_peak_date_end_2 = strtotime("Y-m-d", strtotime("11/19/2019"));

$peak_date_start_1 = strtotime("Y-m-d", strtotime("03/12/2019"));
$peak_date_end_1 = strtotime("Y-m-d", strtotime("07/17/2019"));

$peak_date_start_2 = strtotime("Y-m-d", strtotime("11/20/2019"));
$peak_date_end_2 = strtotime("Y-m-d", strtotime("01/01/2020"));

$type_of_rate = "";

if((($date_today >= $off_peak_date_start_1) && ($date_today >= $off_peak_date_start_2)) || (($date_today >= $off_peak_date_start_2) && ($date_today >= $off_peak_date_start_2))) {
    $type_of_rate = "OFF-PEAK";
} else if((($date_today >= $peak_date_start_1) && ($date_today >= $peak_date_start_2)) || (($date_today >= $peak_date_start_2) && ($date_today >= $peak_date_start_2))) {
    $type_of_rate = "PEAK"; 
}

?>

    <?php include('../common/admin_sidebar.php') ?>

    <form action="../functions/admin/walk_in_reservation.php" method="POST">
        <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>" >
        <div class="main-panel">
            <div class="container-fluid">
                <h1>Walk-in Reservation</h1>
                
                <?php
        
                if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION['alert'] . '" text-center" role="alert">
                            ' . $_SESSION['msg']  . '
                        </div>
                    ';
                }
                
                if(isset($_SESSION['empty_description']) || isset($_SESSION['empty_payment'])) {
                    echo '
                        <div class="alert alert-danger" text-center" role="alert">
                            ' . $_SESSION['empty_description']  . ' <br>
                            ' . $_SESSION['empty_payment']  . '
                        </div>
                    ';
                }

                ?>

                <div class="row">
                    <div class="col">
                    
                        <div class="card">
                            <div class="card-body">
                                
                                <div>
                                    <h4 class="text-info text-center">RESERVATION DETAILS</h4>

                                    <hr>

                                    <div class="row">
                                        <div class="col">
                                            <p class="coralview-blue">Arrival Date <span style="color: black;" id="spanArrivalDate" class="float-right"></span></p>
                                            <p class="coralview-blue">Departure Date <span style="color: black;" id="spanDepartureDate" class="float-right"></span></p>
                                        </div>
                                        <div class="col">
                                            <p class="coralview-blue">Night/s of Stay <span style="color: black;" id="spanDaysOfStay" class="float-right"></span></p>
                                            <p class="coralview-blue">Number of Guests <span style="color: black;" id="spanNoOfGuests" class="float-right"></span></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                        <h4 class="text-center coralview-orange">ARRIVAL DATE</h4>
                                            <div class="datepicker-here" data-language='en' id="arrivalDate"></div>    
                                        </div>
                                        <div class="col">
                                        <h4 class="text-center coralview-orange">DEPARTURE DATE</h4>
                                            <div class="datepicker-here" data-language='en' id="departureDate"></div>    
                                        </div>
                                    </div>
                                    
                                    <!-- Selected Dates Var -->
                                    <input type="hidden" name="arrival_date" id="inputArrivalDate" />
                                    <input type="hidden" name="departure_date" id="inputDepartureDate" />

                                    <input type="hidden" name="adult_count" id="inputAdultCount" />
                                    <input type="hidden" name="kids_count" id="inputKidCount" />
                                    <input type="hidden" name="no_of_days" id="inputNoOfDays" />
                                
                                </div>
                                
                                <br>    
                                <div>
                                    <h4 class="text-info text-center">GUEST COUNT</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-2">
                                            <h6 class="mt-2 text-center">Guest Count</h6>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="numOfAduField" placeholder="Number of Adults" min="0">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">    
                                                <input type="number" class="form-control" id="numOfKidsField" placeholder="Number of Kids" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <br>
                                <div>
                                    <h4 class="text-info text-center">ROOM SELECTION</h4>
                                    <hr>
                                    <input type="hidden" name="rooms_reserved" id="inputRoomsReserved" value="">
                                    <input type="hidden" name="rate_type" id="rateType" value="<?php echo $type_of_rate; ?>">
                                    
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <?php
                                            
                                            if($type_of_rate == "OFF-PEAK") {
                                                echo '<h5 class="text-center text-info">Room prices is OFF-PEAK rate.</h5>';
                                                $off_peak_rate_class = "coralview-blue font-weight-bolder";
                                                $peak_rate_class = "";
                                            } else if($type_of_rate == "PEAK") {
                                                echo '<h5 class="text-center text-info">Room prices is PEAK rate.</h5>';
                                                $peak_rate_class = "coralview-blue font-weight-bolder";
                                                $off_peak_rate_class = "";
                                            }
                                            
                                            ?>

                                            <div style="height: 55vh; overflow: scroll;" id="roomList">
                                            
                                            <?php

                                            $rooms_query = "SELECT rooms.type, rooms.inclusions, rooms.image, rooms.peak_rate, rooms.off_peak_rate, room_id, count('room_id') as room_count FROM `rooms_status` INNER JOIN rooms ON rooms.Id = rooms_status.room_id  WHERE rooms_status.status = 'AVAILABLE' GROUP BY `room_id` ASC";
                                            $rooms_result = mysqli_query($db, $rooms_query);
            
                                            if(mysqli_num_rows($rooms_result) > 0) {
                                                while($room = mysqli_fetch_assoc($rooms_result)) {
                                                    echo '
                                                    <div class="card card-nav-tabs">
                                                        <h4 class="card-header card-header-info">' . $room['type'] . '</h4>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <img src="' . $room['image'] . '" style="border-radius: 0; width: 100%;">
                                                                </div>
                                                                <div class="col-5">
                                                                    ' . $room['inclusions'] . '
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="' . $off_peak_rate_class . '">OFF-PEAK RATE: <span class="float-right">PHP ' . number_format($room['off_peak_rate'], 2) . '</span></p>
                                                                    <p class="' . $peak_rate_class . '">PEAK RATE: <span class="float-right font-weight-bolder">PHP ' . number_format($room['peak_rate'], 2) . '</span></p>
                                                                    <select class="form-control mt-3" data-room-id="' . $room['room_id'] . '">            
                                                    ';
                                                    
                                                    for($i = 0; $i <= $room['room_count']; $i++) {
                                                        echo '<option value="' . $i. '">' . $i. '</option>';
                                                    }

                                                    echo '
                                                                </select>
                                                                <a href="#" data-room-select="' . $room['room_id'] . '" class="btn btn-primary btn-block mt-3">Select</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    ';

                                                }
                                            }


                                            ?>
                                        </div>

                                    </div>
                                </div>
                                
                                
                                <br>
                                <div>
                                    <h4 class="text-info text-center">GUEST DETAILS</h4>
                                    <hr>

                                    <div class="row">
                                        <div class="col-4">
                                            
                                            <div class="form-group row" id="fnFormGroup">
                                                <label for="fieldFirstName" class="col-sm-3 col-form-label text-right">First Name:</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="fieldFirstName" name="first_name" required>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="lnFormGroup">
                                                <label for="fieldLastName" class="col-sm-3 col-form-label text-right">Last Name:</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="fieldLastName" name="last_name" required>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-4">

                                            <div class="form-group row" id="contactFormGroup">
                                                <label for="fieldContactNumber" class="col-sm-3 col-form-label text-right">Contact Number:</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="fieldContactNumber" name="contact_number" required>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="emailFormGroup">
                                                <label for="fieldEmail" class="col-sm-3 col-form-label text-right">Email:</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="fieldEmail" name="email" required>
                                                </div>
                                            </div>
                                        
                                        </div>
                                        <div class="col-4">

                                            <div class="form-group row" id="addressFormGroup">
                                                <label for="fieldAddress" class="col-sm-3 col-form-label text-right">Address:</label>
                                                <div class="col-sm-7">
                                                    <textarea type="text" class="form-control" id="fieldAddress" name="address" required></textarea>
                                                </div>
                                            </div>      
                                        
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="row">
                                    <div class="col">
                                        <button id="submitReservationBtn" type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                
                                </div>


                            </div>
                        </div>

                    </div>
                </div>


                

                
            </div>
        </div>
    
    </form>

    <script>
    
       
    
    </script>

<?php

include('../common/footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);
unset($_SESSION["empty_description"]);
unset($_SESSION["empty_payment"]);

?>