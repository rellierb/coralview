// let referenceCode = document.getElementById('referenceCode');

function transferRoom(id, room) {
    
    let roomId = id;
    console.log(roomId)
    let code = referenceCode.innerText;
    let roomSelected = document.getElementById('select-room-' + id).value;
    let oldRoom = room

    console.log({reference_no: JSON.stringify(code), new_room_name: JSON.stringify(roomSelected), old_room_name: JSON.stringify(oldRoom)  })

    $.ajax({
        type: 'POST',
        url: '/coralview/functions/admin/transfer_room.php',
        data: {reference_no: JSON.stringify(code), new_room_name: JSON.stringify(roomSelected), old_room_name: JSON.stringify(oldRoom)  },
        success: function(data) {
            toastr.success('Room successfully transfered');
            console.log(data)
            setTimeout(function() {

                location.reload();
                
            }, 3000);

        },
        error: function(data) {
            console.log(data);
        }
    })

}