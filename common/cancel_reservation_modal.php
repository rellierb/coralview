<div class="modal fade" id="cancelReservation" tabindex="-1" role="dialog" aria-labelledby="cancelReservation" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="functions/user/cancel_reservation.php" method="POST">
                <input type="hidden" name="reference_no" value="<?php echo $_SESSION["reference_no"]; ?>" />

                <div class="modal-header">
                    <h5 class="modal-title" id="cancelReservation">Cancel Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel your reservation?</p>

                    <p>Please see rules on cancellation<p>
                    <ul>
                        <li>Lorem Ipsum</li>
                        <li>Lorem Ipsum</li>
                        <li>Lorem Ipsum</li>
                        <li>Lorem Ipsum</li>
                    </ul>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>