$(document).ready(function(){

    $('#roomStatusTable').DataTable();
    $('#reservationCheckIn').DataTable();

    let windowUrl = window.location.href;

    if(windowUrl.indexOf('reserve.ph') > 0 || windowUrl.indexOf('walk_in_reservation.ph') > 0) {
        
        $('#smartwizard').smartWizard();

        // Modify Calendar Style

        // Width
        var datePickerWidth = document.querySelectorAll('div.datepicker');
        for(var i = 0; i < datePickerWidth.length; i++) {
            datePickerWidth[i].style.width = '100%';
        }

        let roomList = document.getElementById('roomList');

        let spanArrivalDate = document.getElementById('spanArrivalDate');
        let spanDepartureDate = document.getElementById('spanDepartureDate');
        let spanDaysOfStay = document.getElementById('spanDaysOfStay');

        let arrivalDateData = $('#arrivalDate').datepicker().data('datepicker');
        let departureDateData = $('#departureDate').datepicker().data('datepicker');

        let inputArrivalDate = document.getElementById('inputArrivalDate');
        let inputDepartureDate = document.getElementById('inputDepartureDate');

        let inputNoOfDays = document.getElementById('inputNoOfDays');
        
        let arrivalDateMinDate = arrivalDateData.currentDate;

        // if(windowUrl.indexOf('reserve.ph') > 0) {
        //     arrivalDateMinDate = datePlusTwoThree(arrivalDateData.currentDate);
        // } else {
        //     arrivalDateMinDate = arrivalDateData.currentDate;
        // }
        

        $('#arrivalDate').datepicker({
            startDate: new Date(),
            minDate: arrivalDateMinDate,      
            onSelect: function() {
                spanArrivalDate.innerText = arrivalDateData._prevOnSelectValue;  
                let departureDate = arrivalDateData.selectedDates[0];
                let newDate = new Date(departureDate);
                let minDepartureDate = newDate.setDate(departureDate.getDate() + 1);
                let anotherDate = new Date(minDepartureDate);                        
                departureDateData.update('minDate', anotherDate);            
                toastr.success('Arrival Date successfully selected!');
            }
        });

        $('#departureDate').datepicker({
            minDate: datePlusOneDate(arrivalDateMinDate),
            onSelect: function() {
                let selectedDaysLength = arrivalDateData.selectedDates.length;
                if(selectedDaysLength === 0) {
                    toastr["error"]('You must select your arrival date first.');
                } else {
                    spanDepartureDate.innerText = departureDateData._prevOnSelectValue;

                    let arr = arrivalDateData.selectedDates[0];
                    let dep = departureDateData.selectedDates[0];
                    let res = Math.abs(arr - dep)/1000;
                    let days = Math.floor(res/ 86400);
                    
                    spanDaysOfStay.innerHTML = days;

                    inputArrivalDate.value = arrivalDateData._prevOnSelectValue;
                    inputDepartureDate.value = departureDateData._prevOnSelectValue;                       
                    inputNoOfDays.value = days;

                    if(document.body.contains(document.getElementById('smartwizard'))) { 

                        document.querySelector('.sw-btn-next').disabled = false;

                    }

                    toastr.success('Departure Date successfully selected!');
                }
            }

        });

        let numOfAduField = document.getElementById('numOfAduField');
        let numOfKidsField = document.getElementById('numOfKidsField');
        let spanNoOfGuests = document.getElementById('spanNoOfGuests');

        let inputKidCount = document.getElementById('inputKidCount');
        let inputAdultCount = document.getElementById('inputAdultCount');

        numOfAduField.addEventListener('focusout', ()=> {
            let adultCount = numOfAduField.value;          
            localStorage.setItem('adultCount', adultCount);

            if(adultCount == 0 || adultCount === '') {

                toastr.error('Adult count is empty');

            } else if(adultCount < 0) {

                toastr.error('Invalid input on Adult Count');
                
            } else {

                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/user/store_adult_count.php',
                    data: {adult_count: JSON.stringify(adultCount) },
                    success: function(data) {
                        spanNoOfGuests.innerHTML = adultCount.toString();
                        inputAdultCount.value = adultCount;
                        disableNextButton();
                        toastr.success('Adult count successfully added');
                        // showRoomsBasedOnCapacity(adultCount);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

            }


        })

        numOfKidsField.addEventListener('focusout', ()=> {
            let childCount = numOfKidsField.value;
            let adultCount = localStorage.getItem('adultCount');
            let totalCount = 0;

            if(adultCount !== null && childCount > 0) {
                totalCount = parseInt(childCount) + parseInt(adultCount);

                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/user/store_kids_count.php',
                    data: {kids_count: JSON.stringify(childCount) },
                    success: function(data) {
                        inputKidCount.value = childCount;
                        spanNoOfGuests.innerHTML = totalCount;
                        // disableNextButton();    
                        toastr.success('Kids count successfully added');
                        // showRoomsBasedOnCapacity(totalCount);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })


            } else if(childCount < 0) {

                toastr.error('Invalid input on Kids Count');


            } else if(childCount == 0 || childCount === '') {

                toastr.info('Kids count is empty');
                spanNoOfGuests.innerHTML = adultCount;

            }else {
                totalCount = childCount;

                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/user/store_kids_count.php',
                    data: {kids_count: JSON.stringify(childCount) },
                    success: function(data) {
                        inputKidCount.value = childCount;
                        spanNoOfGuests.innerHTML = totalCount;
                        // disableNextButton();    
                        toastr.success('Kids count successfully added');         
                        // showRoomsBasedOnCapacity(totalCount);        
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

            }

            

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
                    
                    if(roomAndCountStorage !== null) {
                        
                        var parsedStorage = JSON.parse(roomAndCountStorage);
                         
                        for(var x = 0; x < parsedStorage.length; x++) {
                            
                            if(parsedStorage[x].roomId === objToPush.roomId) {
                                parsedStorage.splice(x, 1); 
                            }   
                        }
                        parsedStorage.push(objToPush);      

                        localStorage.setItem('roomsAndCountReserved', JSON.stringify(parsedStorage));
                        inputRoomsReserved.value = JSON.stringify(JSON.stringify(toStore));
                        forCheckingRoomCapacity = toStore;
                        
                        console.log({room_reserved: JSON.stringify(parsedStorage) })

                        $.ajax({
                            type: 'POST',
                            url: '/coralview/functions/user/store_room.php',
                            data: {room_reserved: JSON.stringify(parsedStorage) },
                            success: function(data) {
                                console.log(data)
                                toastr.success('Room successfully selected!');            
                                disableNextButton();
                                attachRemoveButton(roomId);
                                checkCapacity(forCheckingRoomCapacity);
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        })

                        
                        
                    } else {
                        toastr.success('Room successfully selected!');            
                        attachRemoveButton(roomId);
                        localStorage.setItem('roomsAndCountReserved', JSON.stringify(toStore));
                        disableNextButton();
                        inputRoomsReserved.value = JSON.stringify(toStore);
                        checkCapacity(toStore);
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

        if(document.body.contains(document.getElementById('smartwizard'))) {

            document.querySelector('.sw-btn-prev').style.display = 'none';
            document.querySelector('.sw-btn-next').disabled = true;

        }

        $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) { 
            
            if(stepNumber === 1) {
                document.querySelector('.sw-btn-next').disabled = true;
            } else if(stepNumber === 2) {
                // document.querySelector('.sw-btn-next').disabled = false;
                document.querySelector('.sw-btn-next').style.display = 'none';
                document.querySelector('.sw-btn-prev').style.display = 'block';
            } else if(stepNumber === 0) {
                
                // document.querySelector('.sw-btn-next').disabled = true;

                if(document.body.contains(document.querySelector('.sw-btn-prev'))) {
                    document.querySelector('.sw-btn-prev').style.display = 'none';
                    document.querySelector('.sw-btn-next').style.display = 'block';
                    document.querySelector('.sw-btn-next').disabled = true;
                } else {
                    document.querySelector('.sw-btn-next').disabled = true;
                }

            }

        });

    }

    
    /*
     * 
     * Form Validation 
     *  
     */

    let submitReservationBtn = document.getElementById('submitReservationBtn');
    let isSubmitReservationBtn = document.body.contains(submitReservationBtn);

    let fieldFirstName = document.getElementById('fieldFirstName');
    let isFieldFirstName = document.body.contains(fieldFirstName);

    let fieldLastName = document.getElementById('fieldLastName');
    let isFieldLastName = document.body.contains(fieldLastName);

    let fieldContactNumber = document.getElementById('fieldContactNumber');
    let isFieldContactNumber = document.body.contains(fieldContactNumber);

    let fieldEmail = document.getElementById('fieldEmail');
    let isFieldEmail = document.body.contains(fieldEmail);

    let fieldAddress = document.getElementById('fieldAddress');
    let isFieldAddress = document.body.contains(fieldAddress);

    // form-group-div
    let fnFormGroup = document.getElementById('fnFormGroup');
    let isFnFormGroup = document.body.contains(fnFormGroup);

    let lnFormGroup = document.getElementById('lnFormGroup');
    let isLnFormGroup = document.body.contains(lnFormGroup);

    let contactFormGroup = document.getElementById('contactFormGroup');
    let isContactFormGroup = document.body.contains(contactFormGroup);

    let emailFormGroup = document.getElementById('emailFormGroup');
    let isEmailFormGroup = document.body.contains(emailFormGroup);

    let addressFormGroup = document.getElementById('addressFormGroup');
    let isAddressFormGroup = document.body.contains(addressFormGroup);

    let completeValidated = false;

    if(isSubmitReservationBtn) {
        submitReservationBtn.disabled = true;
    }

    if(isFieldFirstName) {
        fieldFirstName.addEventListener('focusout', function() {

            var firstName = this.value;

            if(/[^a-zA-Z\-\/]/.test(firstName)) {
                toastr.error('First name contains special Characters or Numbers');
                localStorage.setItem('firstNameValidation', true)
            } else if (firstName === '') {
                toastr.error('First name field is empty');
                localStorage.setItem('firstNameValidation', true)
            } else {
                completeValidated = true;
                localStorage.setItem('firstNameValidation', false)
            }
            disableSubmitButton();
        });
    }

    if(isFieldLastName) {
        fieldLastName.addEventListener('focusout', function() {

            var lastName = this.value;

            if(/[^a-zA-Z\-\/]/.test(lastName)) {
                toastr.error('Last name contains special Characters or Numbers');
                localStorage.setItem('lastNameValidation', true);
            } else if (lastName === '') {
                toastr.error('Last name field is empty');
                localStorage.setItem('lastNameValidation', true);
            } else {
                localStorage.setItem('lastNameValidation', false);
            }
            disableSubmitButton();
        });

    }

    if(isFieldContactNumber) {
        fieldContactNumber.addEventListener('focusout', function() {

            var contactNumber = this.value;
            var contactNumbereLength = contactNumber.length;
            
            if(!/^(09|\+639)\d{9}$/.test(contactNumber) || (contactNumbereLength >= 8 && contactNumbereLength <= 10) || (contactNumbereLength > 11)) {
                toastr.error('Contact number format is invalid');
                localStorage.setItem('contactNumberValidation', true)
            } else if (contactNumber === '') {
                toastr.error('Contact number field is empty');
                localStorage.setItem('contactNumberValidation', true)
            } else {
                localStorage.setItem('contactNumberValidation', false)
            }
            disableSubmitButton();
        });
    }

    if(isFieldEmail) {
        fieldEmail.addEventListener('focusout', function() {

            var emailAddress = this.value;

            var emailFieldValidator = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if(emailAddress === '') {
                toastr.error('Email address field is empty');
                localStorage.setItem('emailValidation', true)
            } else if (!emailFieldValidator.test(emailAddress)) {
                toastr.error('Email address format is invalid');
                localStorage.setItem('emailValidation', true)
            } else {
                localStorage.setItem('emailValidation', false)
            }
            disableSubmitButton();
        });
    }

    if(isFieldAddress) {
        fieldAddress.addEventListener('focusout', function() {

            var address = this.value;

            if(address === '') {
                toastr.error('Address field is empty');
                localStorage.setItem('addressValidation', true)
            } else {
                localStorage.setItem('addressValidation', false)
            }
            disableSubmitButton();

        });
    }

    
});

function disableNextButton() {
    
    var numOfAduField = document.getElementById('numOfAduField').value;
    // var numOfKidsField = document.getElementById('numOfKidsField').value;
    var roomLocalStorage = localStorage.getItem('roomsAndCountReserved'); 

    if((numOfAduField !== '') && (roomLocalStorage !== null)) {

        if(document.body.contains(document.getElementById('smartwizard'))) { 
            document.querySelector('.sw-btn-next').disabled = false;
        }
        
    }

}

function enableNextButton() {

    if(document.body.contains(document.getElementById('smartwizard'))) { 
        document.querySelector('.sw-btn-next').disabled = true;
    }

}

function disableSubmitButton() {

    let isSubmitReservationBtn = document.body.contains(submitReservationBtn);
    let returnValue = true;

    if(fieldFirstName.value === '' || (/[^a-zA-Z\-\/]/.test(fieldFirstName.value))) {
        returnValue = false;
    }

    if(fieldLastName.value === '' || (/[^a-zA-Z\-\/]/.test(fieldLastName.value))) {
        returnValue = false;
    }

    if(fieldContactNumber.value === '') {
        returnValue = false;
    }

    if(fieldEmail.value === '') {
        returnValue = false;
    }

    if(fieldAddress.value === '') {
        returnValue = false;
    }

    if(returnValue) {
        var firstNameValidation = localStorage.getItem('firstNameValidation') === 'true'
        var lastNameValidation = localStorage.getItem('lastNameValidation') === 'true'
        var emailNameValidation = localStorage.getItem('contactNumberValidation') === 'true'
        var contactNumberValidation = localStorage.getItem('emailValidation') === 'true'
        var addressValidation = localStorage.getItem('addressValidation') === 'true'

        if(firstNameValidation || lastNameValidation || emailNameValidation || contactNumberValidation || addressValidation) {
            submitReservationBtn.disabled = true;
        } else {
            submitReservationBtn.disabled = false;
        }

    } else {
        submitReservationBtn.disabled = true;
    }

}


function datePlusOneDate(date) {
    
    let tempDate = date;
    let newDate = new Date(tempDate);
    let minDepartureDate = newDate.setDate(tempDate.getDate() +1);
    let returnDate = new Date(minDepartureDate);
    
    return returnDate;
}

function datePlusTwoDate(date) {
    
    let tempDate = date;
    let newDate = new Date(tempDate);
    let minDepartureDate = newDate.setDate(tempDate.getDate() + 2);
    let returnDate = new Date(minDepartureDate);
    
    return returnDate;
}

function datePlusTwoThree(date) {
    
    let tempDate = date;
    let newDate = new Date(tempDate);
    let minDepartureDate = newDate.setDate(tempDate.getDate() + 3);
    let returnDate = new Date(minDepartureDate);
    
    return returnDate;
}

function showRoomsBasedOnCapacity(capacity) {

    $.ajax({
        type: 'GET',
        url: '/coralview/functions/user/list_of_rooms.php',
        data: { capacity: JSON.stringify(capacity) },
        success: function(data) {
            attachRoomList(data);
        },
        error: function(data) {
            console.log(data);
        }
    })

}

function attachRoomList(list) {
    
    let parseList = JSON.parse(list);
    let html = '';
    let isOffPeakClass = '';
    let isPeakRateClass = '';
    let rateType = '';

    if(document.body.contains(document.getElementById('rateType'))) {
        rateType = document.getElementById('rateType').value;
    }
    
    if(rateType === "OFF-PEAK") {
        isOffPeakClass = "coralview-blue font-weight-bolder";
        isPeakRateClass = "";
    }

    if(rateType === "PEAK") {
        isOffPeakClass = "";
        isPeakRateClass = "coralview-blue font-weight-bolder";
    } 

    for(let i = 0; i < parseList.length; i++) {

        let offPeakRate = parseList[i]["off_peak_rate"];
        let peakRate = parseList[i]["peak_rate"];
      
        html += `
        <div class="card card-nav-tabs">
            <h4 class="card-header card-header-info">${parseList[i]["type"]}</h4>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <img src="${parseList[i]["image"]}" style="border-radius: 0; width: 100%;">
                    </div>
                    <div class="col-5">
                        <p>Room capacity: ${parseList[i]["capacity"]}<p>
                        ${parseList[i]["inclusions"]}
                    </div>
                    <div class="col-4">
                        <p class="${isOffPeakClass}">OFF-PEAK RATE: <span class="float-right">PHP ${parseFloat(Math.round(offPeakRate * 100) / 100).toFixed(2)}</span></p>
                        <p class="${isPeakRateClass}">PEAK RATE: <span class="float-right font-weight-bolder">PHP ${parseFloat(Math.round(peakRate * 100) / 100).toFixed(2)}</span></p>
                        <select class="form-control mt-3" data-room-id="${parseList[i]["room_id"]}">  
        `;
       
        let roomCountLength = parseList[i]["room_count"]
        for(let i = 0; i <= roomCountLength; i++) {

           html += `<option value="${i}">${i}</option>`;
        }

        html += `
                        </select>
                    <a href="#" data-room-select="${parseList[i]["room_id"]}" class="btn btn-primary btn-block mt-3">Select</a>
                    <div data-room-remove="${parseList[i]["room_id"]}">


                    
                    </div>
                    </div>
                </div>
            </div>
        </div>
        `; 

    }
    roomList.innerHTML = html;

    // console.log(list);
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
                    
                    if(roomAndCountStorage !== null) {
                        
                        var parsedStorage = JSON.parse(roomAndCountStorage);
                        
                        for(var x = 0; x < parsedStorage.length; x++) {
                            
                            if(parsedStorage[x].roomId === objToPush.roomId) {
                                parsedStorage.splice(x, 1); 
                            }   
                        }
                        parsedStorage.push(objToPush);      

                        localStorage.setItem('roomsAndCountReserved', JSON.stringify(parsedStorage));
                        inputRoomsReserved.value = JSON.stringify(JSON.stringify(toStore));
                        forCheckingRoomCapacity = toStore;

                        $.ajax({
                            type: 'POST',
                            url: '/coralview/functions/user/store_room.php',
                            data: {room_reserved: JSON.stringify(toStore) },
                            success: function(data) {
                                toastr.success('Room successfully selected!');            
                                disableNextButton();
                                attachRemoveButton(roomId);
                                checkCapacity(toStore);
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        })

                    
                        
                        
                    } else {
                        toastr.success('Room successfully selected!');            
                        attachRemoveButton(roomId);        
                        localStorage.setItem('roomsAndCountReserved', JSON.stringify(toStore));
                        disableNextButton();
                        inputRoomsReserved.value = JSON.stringify(toStore);
                        checkCapacity(toStore);
                    }                     
                    
                    console.log(roomsAndCountReserved)
                    
                }
            });
        }

}


function attachRemoveButton(roomId) {

    let id = roomId;

    let divToAttach = document.querySelector('div[data-room-remove*="' + id + '"]');

    let buttonToAttach = '<button type="button" class="btn btn-danger btn-block" data-remove-button="' + roomId +'">Remove</button>';

    divToAttach.innerHTML = buttonToAttach;

    let removeBtn = document.querySelectorAll('button[data-remove-button*="' + roomId + '"]');

    for(let i = 0; i < removeBtn.length; i++) {

        let element = removeBtn[i];

        element.addEventListener('click', function() {

            let el = this;

            let roomsReserved = localStorage.getItem('roomsAndCountReserved');

            let parsedRoomsReserved = JSON.parse(roomsReserved);

            for(let j = 0; j < parsedRoomsReserved.length; j++) {
                
                let roomReserved = parsedRoomsReserved[j];
                
                let roomToRemoved = roomReserved.roomId; 

                if(roomToRemoved === roomId) {
                    parsedRoomsReserved.splice(j, 1);
                }

            }

            if(parsedRoomsReserved.length !== 0) {
                localStorage.setItem('roomsAndCountReserved', JSON.stringify(parsedRoomsReserved));
                inputRoomsReserved.value = JSON.stringify(JSON.stringify(parsedRoomsReserved));

                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/user/store_room.php',
                    data: {room_reserved: JSON.stringify(parsedRoomsReserved) },
                    success: function(data) {
                            
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

                
            } else {
                localStorage.removeItem('roomsAndCountReserved');
            }
            
            divToAttach.innerHTML = '';
            toastr.info('Room successfully removed');


        })

    }    



}

function checkCapacity(roomsReserved) {

    let capacity = document.getElementById('spanNoOfGuests').innerText;

    $.ajax({
        type: 'POST',
        url: '/coralview/functions/user/check_capacity.php',
        data: {room_reserved: JSON.stringify(roomsReserved), capacity: JSON.stringify(capacity) },
        success: function(data) {

            if(data == 'greater') {
                setTimeout(function() {
                    toastr.options.timeOut = 5000;
                    toastr.options.extendedTimeOut = 500;
                    toastr.info('Guest Capacity is greater than the room you reserved. Please add another room to accomodate guest count.');
                    enableNextButton();
                }, 2000);
            }
        },
        error: function(data) {
            console.log(data);
        }
    })


    

}