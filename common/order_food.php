<div class="modal fade" id="orderFood" tabindex="-1" role="dialog" aria-labelledby="orderFood" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="functions/user/add_food.php" method="POST" enctype="multipart/form-data">
                

                <div class="modal-header">
                    <h5 class="modal-title" id="cancelReservation">Order Food</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <!-- <div class="custom-file"> -->
                <?php 
                
                if($_SESSION['account_type'] == "Administrator" || $_SESSION['account_type'] == "Receptionist") { 
                    echo '<input type="text" class="form-control mb-3" id="referenceCode" placeholder="Reference No" name="reference_no" />';

                    
                } else {
                    echo '<input type="hidden" id="referenceCode" name="reference_no" value="' . $_SESSION["reference_no"] .'" />';

                }
                
                ?>
                    <input class="form-control" name="subject" type="text" placeholder="Food Order Request" id="foodOrderSubj">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1"></label>
                        <textarea class="form-control" placeholder="Food Order Request Details" name="description" id="foodOrderDesc" rows="3"></textarea>
                    </div>
                    <!-- <label class="custom-file-label" for="customFile">Choose file</label> -->
                <!-- </div> -->

                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addFoodRequest">Add Food Request</button>
                </div>
            </form>
        </div>
    </div>
</div>