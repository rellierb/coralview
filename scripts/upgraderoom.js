

let upgradeButton = document.querySelectorAll('button[data-upgrade-button]');

for(let i = 0; i < upgradeButton.length; i++) {

    let button = upgradeButton[i];
    
    button.addEventListener('click', function(el) {

        let element = this;
        let previousRoomId = element.getAttribute('data-previous-room-id');
        let reservationId = element.getAttribute('data-reservation-id');
        let newRoomId = document.querySelector('select[data-room-id-' + previousRoomId +']').value;

        if(newRoomId !== '') {

            $.ajax({
                type: 'POST',
                url: '/coralview/functions/admin/upgrade_room.php',
                data: {previous_room_id: JSON.stringify(previousRoomId), reservation_id: JSON.stringify(reservationId), new_room_id: JSON.stringify(newRoomId)  },
                success: function(data) {
                    
                    toastr.success('Room Successfully Upgraded');
    
                    setTimeout(function() {
    
                        location.reload();
                        
                    }, 3000);

                },
                error: function(data) {
                    console.log(data);
                }
            })

        } else {

            toastr.error('Room Field is empty.');

        }

       


    });




}