<?php

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();


?>

    <form action="confirm.php" method="POST"> 

        <div class="container">
            <div class="row">

                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="coralview-blue">Reservation Details</h4>
                        </div>

                        <div class="card-body">
                            <p class="coralview-blue">Arrival Date <span style="color: black;" id="spanArrivalDate" class="float-right"></span></p>
                            <p class="coralview-blue">Departure Date <span style="color: black;" id="spanDepartureDate" class="float-right"></span></p>
                            <p class="coralview-blue">Day/s of Stay <span style="color: black;" id="spanDaysOfStay" class="float-right"></span></p>
                            <p class="coralview-blue">Number of Guests <span style="color: black;" id="spanNoOfGuests" class="float-right"></span></p>                       
                        </div>
                    </div>
                
                </div>
                <div class="col-9">

                    <div id="smartwizard">
                        <ul style="width: 100%:">
                            <li style="width: 24.5%;" class="text-center coralview-blue"><a href="#step-1">Select Date<br /></a></li>
                            <li style="width: 24.5%;" class="text-center coralview-blue"><a href="#step-2">Select Room<br /></a></li>
                            <li style="width: 24.5%;" class="text-center coralview-blue"><a href="#step-3">Modes of Payment<br /></a></li>
                            <li style="width: 24.5%;" class="text-center coralview-blue"><a href="#step-4">Guest Details<br /></a></li>
                        </ul>
                    
                        <div>
                            <div id="step-1" class="" style="height: 76vh;">

                                <h2 class="text-center coralview-blue">Please select arrival and departure date to proceed</h2>
                                
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
                            <div id="step-2" class="">

                                <br />

                                <div class="row">
                                    <div class="col-2">
                                        <h6 class="mt-2 text-center">Guest Count</h6>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="numOfAduField" placeholder="Number of Adults">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">    
                                            <input type="number" class="form-control" id="numOfKidsField" placeholder="Number of Kids">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="rooms_reserved" id="inputRoomsReserved" value="">

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div style="height: 55vh; overflow: scroll;">

                                        <?php

                                        $rooms_query = "SELECT rooms.type, rooms.inclusions, rooms.peak_rate, rooms.off_peak_rate, room_id, count('room_id') as room_count FROM `rooms_status` INNER JOIN rooms ON rooms.Id = rooms_status.room_id  WHERE rooms_status.status = 'AVAILABLE' GROUP BY `room_id` ASC";
                                        $rooms_result = mysqli_query($db, $rooms_query);
        
                                        // SELECT room_id, count('room_id') as room_count FROM `rooms_status` GROUP BY `room_id` ASC
                                        // 

                                        if(mysqli_num_rows($rooms_result) > 0) {
                                            while($room = mysqli_fetch_assoc($rooms_result)) {
                                                echo '
                                                <div class="card card-nav-tabs">
                                                    <h4 class="card-header card-header-info">' . $room['type'] . '</h4>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-8">
                                                                ' . $room['inclusions'] . '
                                                            </div>
                                                            <div class="col-4">
                                                                <p class="coralview-blue font-weight-bolder">OFF-PEAK RATE: <span class="float-right">PHP ' . number_format($room['off_peak_rate'], 2) . '</span></p>
                                                                <p class="coralview-blue font-weight-bolder">PEAK RATE: <span class="float-right font-weight-bolder">PHP ' . number_format($room['peak_rate'], 2) . '</span></p>
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

                                <br />

                                
                            </div>
                            <div id="step-3" class="" style="height: 50vh;">
                                                         
                                <div class="container">                                    
                                    <div class="row">
                                        
                                        <div class="col-6">
                                            
                                            <div class="mt-5">
                                                <label>
                                                    <input type="radio" id="cash" value="BANK DEPOSIT" name="mode_of_payment" class="card-input-element" />

                                                    <div class="panel panel-default card-input">
                                                        <div class="panel-heading">
                                                            <h4 class="text-center">
                                                                BANK DEPOSIT
                                                            </h4>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div style="font-size: 18px;" class="text-center">
                                                                <i class="fas fa-money-check fa-10x"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </label>
                                            </div>
                                            
                                        </div>

                                        <div class="col-6">
                                            
                                            <div class="mt-5">
                                                <label>
                                                <input type="radio" id="bankdeposit" value="CASH UPON WALK-IN" name="mode_of_payment" class="card-input-element" />

                                                    <div class="panel panel-default card-input">
                                                        <div class="panel-heading">
                                                            <h4 class="text-center">
                                                                CASH UPON WALK-IN
                                                            </h4>    
                                                        </div>
                                                        <div class="panel-body">
                                                            <div style="font-size: 18px;" class="text-center">
                                                                <i class="fas fa-money-bill-wave fa-10x"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                        </div>
                                    
                                    </div>
                                </div> 

                            </div>
                            <div id="step-4" class="">
                                
                                <br />
                                <br />

                                <div class="form-group row" id="fnFormGroup">
                                    <label for="fieldFirstName" class="col-sm-3 col-form-label text-right">First Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fieldFirstName" name="first_name" required>
                                    </div>
                                </div>

                                <div class="form-group row" id="lnFormGroup">
                                    <label for="fieldLastName" class="col-sm-3 col-form-label text-right">Last Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fieldLastName" name="last_name" required>
                                    </div>
                                </div>

                                <div class="form-group row" id="contactFormGroup">
                                    <label for="fieldContactNumber" class="col-sm-3 col-form-label text-right">Contact Number:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fieldContactNumber" name="contact_number" required>
                                    </div>
                                </div>

                                <div class="form-group row" id="emailFormGroup">
                                    <label for="fieldEmail" class="col-sm-3 col-form-label text-right">Email:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fieldEmail" name="email" required>
                                    </div>
                                </div>

                                <div class="form-group row" id="addressFormGroup">
                                    <label for="fieldAddress" class="col-sm-3 col-form-label text-right">Address:</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" id="fieldAddress" name="address" required></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3">
                                    
                                    </div>
                                    <div class="col-sm-9">
                                        <br />
                                        <button id="submitReservationBtn" type="submit" class="btn btn-primary btn-block">Submit</button>    
                                    </div>
                                </div>

                                <br />
                                <br />
                              
                            </div>
                                                        
                            </div>
                        </div>
                    </div>

                
                </div>
            </div>
        </div>

    </form>

<?php

include('common/footer.php');

?>

