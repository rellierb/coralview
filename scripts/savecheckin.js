let saveCheckIn = document.getElementById('saveCheckIn');


saveCheckIn.addEventListener('click', function() {


    let rooms = document.querySelectorAll('input[data-room-id]');
    let arrayToStore = [];

    for(let i = 0; i < rooms.length; i++) {

        let room = rooms[i];

        if(room.checked) {

            let roomValue = room.value;
            arrayToStore.push(roomValue);

            $.ajax({
                type: 'POST',
                url: '/coralview/functions/admin/save_checkin.php',
                data: {room_to_save: JSON.stringify(roomValue) },
                success: function(data) {
                    
                    console.log(data);
    
                    toastr.success('Room Successfully Saved');
    
                    // setTimeout(function() {
    
                    //     location.reload();
                        
                    // }, 3000);
    
                },
                error: function(data) {
                    console.log(data);
                }
            })



        }

    }

    


})