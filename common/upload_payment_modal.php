<div class="modal fade" id="uploadPaymentModal" tabindex="-1" role="dialog" aria-labelledby="uploadPaymentModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="functions/user/upload_payment.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="reference_no" value="<?php echo $_SESSION["reference_no"]; ?>" />

                <div class="modal-header">
                    <h5 class="modal-title" id="cancelReservation">Upload Reservation Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $reference_no?>" name="reference_no">
                <!-- <div class="custom-file"> -->
                    <input type="file" name="photo_payment" id="photoPayment">
                    <!-- <label class="custom-file-label" for="customFile">Choose file</label> -->
                <!-- </div> -->

                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>