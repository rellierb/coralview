
    <!-- <script src="/coralview/resources/jquery/jquery.min.js"> </script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/coralview/resources/bootstrap/bootstrap.min.js"></script>
    <script src="/coralview/resources/paperkit/assets/js/paper-kit.min.js"></script>
    <script src="/coralview/resources/smart-wizard/dist/js/jquery.smartWizard.min.js"></script>

    <script src="/coralview/resources/air-datepicker/dist/js/datepicker.min.js"></script>
    <script src="/coralview/resources/air-datepicker/dist/js/i18n/datepicker.en.js"></script>
    <script src="/coralview/resources/toastr/toastr.js"></script>
    
    <script type="text/javascript">

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }



        $(document).ready(function(){
            $('#smartwizard').smartWizard();

            // Modify Calendar Style

            // Width
            var datePickerWidth = document.querySelectorAll('div.datepicker');
            for(var i = 0; i < datePickerWidth.length; i++) {
                datePickerWidth[i].style.width = '100%';
            }

            let spanArrivalDate = document.getElementById('spanArrivalDate');
            let spanDepartureDate = document.getElementById('spanDepartureDate');
            let spanDaysOfStay = document.getElementById('spanDaysOfStay');

            let arrivalDateData = $('#arrivalDate').datepicker().data('datepicker');
            let departureDateData = $('#departureDate').datepicker().data('datepicker');

            let inputArrivalDate = document.getElementById('inputArrivalDate');
            let inputDepartureDate = document.getElementById('inputDepartureDate');

            let inputNoOfDays = document.getElementById('inputNoOfDays');
            
            $('#arrivalDate').datepicker({
                startDate: new Date(),
                minDate: arrivalDateData.currentDate,      
                onSelect: function() {
                    spanArrivalDate.innerText = arrivalDateData._prevOnSelectValue;                   
                    departureDateData.update('minDate', arrivalDateData.selectedDates[0]);            
                    toastr.success('Arrival Date successfully selected!');
       
                }
            });

            
            $('#departureDate').datepicker({
                minDate: arrivalDateData.currentDate,
                onSelect: function() {
                    let selectedDaysLength = arrivalDateData.selectedDates.length;
                    if(selectedDaysLength === 0) {
                        toastr["error"]('You must select your arrival date first.');
                    } else {
                        spanDepartureDate.innerText = departureDateData._prevOnSelectValue;

                        let arr = arrivalDateData.selectedDates[0];
                        let dep = departureDateData.selectedDates[0];
                        let res = Math.abs(arr - dep)/1000;
                        let days = Math.floor(res/ 86400) + 1;

                        spanDaysOfStay.innerHTML = days;

                        inputArrivalDate.value = arrivalDateData._prevOnSelectValue;
                        inputDepartureDate.value = departureDateData._prevOnSelectValue;                       
                        inputNoOfDays.value = days;
                        document.querySelector('.sw-btn-next').disabled = false;
                        toastr.success('Departure Date successfully selected!');
                    }
                }

            });

            let numOfAduField = document.getElementById('numOfAduField');
            let numOfKidsField = document.getElementById('numOfKidsField');
            let spanNoOfGuests = document.getElementById('spanNoOfGuests');

            let inputKidCount = document.getElementById('inputKidCount');
            let inputAdultCount = document.getElementById('inputAdultCount');

            numOfAduField.addEventListener('input', ()=> {
                let adultCount = numOfAduField.value;          
                localStorage.setItem('adultCount', adultCount);

                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/user/store_adult_count.php',
                    data: {adult_count: JSON.stringify(adultCount) },
                    success: function(data) {
                        spanNoOfGuests.innerHTML = adultCount.toString();
                        inputAdultCount.value = adultCount;
                        disableNextButton();
                        toastr.success('Adult count successfully added');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

            })

            numOfKidsField.addEventListener('input', ()=> {
                let childCount = numOfKidsField.value;
                let adultCount = localStorage.getItem('adultCount');
                let totalCount = 0;

                if(adultCount !== null) {
                    totalCount = parseInt(childCount) + parseInt(adultCount);
                } else {
                    totalCount = childCount;
                }
                
                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/user/store_kids_count.php',
                    data: {kids_count: JSON.stringify(childCount) },
                    success: function(data) {
                        inputKidCount.value = childCount;
                        spanNoOfGuests.innerHTML = totalCount;
                        disableNextButton();    
                        toastr.success('Kids count successfully added');                  
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })



            })

            let roomSelectTag = document.querySelectorAll('a[data-room-select]');
            let inputRoomsReserved = document.getElementById('inputRoomsReserved');
            let toStore = [];

            for(let i = 0; i < roomSelectTag.length; i++) {
                roomSelectTag[i].addEventListener('click', (el)=> {
                    let element = roomSelectTag[i];
                    
                    let roomId = element.getAttribute('data-room-select');
                    
                    let roomIdQuery = 'select[data-room-id*="' + roomId + '"]'
                    let selectTag = document.querySelector(roomIdQuery);
                
                    let numberOfRooms = selectTag.value;

                    if (numberOfRooms == '0') {
                        toastr.error('Please select a room');
                    } else {

                        let objToPush = {};
                        objToPush.roomId = roomId;
                        objToPush.roomNumber = numberOfRooms;
                        toStore.push(objToPush);

                        var roomAndCountStorage = localStorage.getItem('roomsAndCountReserved');
                        

                        if( roomAndCountStorage !== null) {

                            var parsedStorage = JSON.parse(roomAndCountStorage);
                            parsedStorage.push(objToPush);                          
                            localStorage.setItem('roomsAndCountReserved', JSON.stringify(toStore));
                            
                            inputRoomsReserved.value = JSON.stringify(JSON.stringify(toStore));
                            console.log(toStore);
                            $.ajax({
                                type: 'POST',
                                url: '/coralview/functions/user/store_room.php',
                                data: {room_reserved: JSON.stringify(toStore) },
                                success: function(data) {
                                    toastr.success('Room successfully selected!');            
                                    disableNextButton();
                                },
                                error: function(data) {
                                    console.log(data);
                                }
                            })
                            
                        } else {

                            localStorage.setItem('roomsAndCountReserved', JSON.stringify(toStore));
                            inputRoomsReserved.value = JSON.stringify(toStore);
                        }                     

                        
                    }
                });
            }

            var modeOfPayment = document.querySelectorAll('input[name*="modeOfPayment"]');

            for(var x = 0; x < modeOfPayment.length; x++) {
                modeOfPayment[x].addEventListener('click', function() {
                    
                    var isChecked = this.checked;
                    if(isChecked) {
                        document.querySelector('.sw-btn-next').disabled = false;
                        toastr.success('Mode of Payment Successfully Selected');
                    }


                });
            }


            document.querySelector('.sw-btn-prev').style.display = 'none';
            document.querySelector('.sw-btn-next').disabled = true;

            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) { 
                
                if(stepNumber === 1) {
                    document.querySelector('.sw-btn-next').disabled = true;
                } else if(stepNumber === 2) {
                    document.querySelector('.sw-btn-next').disabled = true;
                } else if(stepNumber === 3) {
                    document.querySelector('.sw-btn-next').style.display = 'none';
                    document.querySelector('.sw-btn-prev').style.display = 'block';
                } else if(stepNumber === 0) {
                    
                    document.querySelector('.sw-btn-next').disabled = true;
                }

            });



        
        });
        
        function disableNextButton() {
            
            var numOfAduField = document.getElementById('numOfAduField').value;
            var numOfKidsField = document.getElementById('numOfKidsField').value;
            var roomLocalStorage = localStorage.getItem('roomsAndCountReserved'); 

            if((numOfAduField !== '') && (numOfKidsField !== '') && (roomLocalStorage !== null)) {
                document.querySelector('.sw-btn-next').disabled = false;
            }

        }




    </script>

</body>
</html>