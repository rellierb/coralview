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
                    <br>
                    <p class="text-center"><b>REMINDER</b></p>
                    <p>CANCELLATION of the event by the guest for whatever reason made one (1) month will be charge (50%) percent penalty while
                    fifteen (15) days before the reservation date is eighty (80%) percent penalty for the total package, 
                    the deposited to our account shall be forfeited</p>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>