

if(document.body.contains(document.getElementById('btnExtendCheckOutDate'))) {

    let extendDepartureDate = $('#extendDepartureDate').datepicker().data('datepicker');
    let checkOutDate = document.getElementById('checkOutDate').value;
    let newCheckOutDate = new Date(checkOutDate)
    let disabledDate = newCheckOutDate.setDate(newCheckOutDate.getDate() + 1)

    extendDepartureDate.update('minDate', newCheckOutDate); 

    let btnExtendCheckOutDate = document.getElementById('btnExtendCheckOutDate');

    btnExtendCheckOutDate.addEventListener('click', function() {

        let code = referenceCode.innerText;
        let dateSelected = extendDepartureDate.lastSelectedDate;
        let date = dateSelected.toISOString().slice(0, 10);

        $.ajax({
            type: 'POST',
            url: '/coralview/functions/admin/extend_checkout.php',
            data: {reference_no: JSON.stringify(code), check_out_date: JSON.stringify(date)  },
            success: function(data) {
                toastr.success('Checkout date successfully updated');

                setTimeout(function() {

                    location.reload();
                    
                }, 3000);

            },
            error: function(data) {
                console.log(data);
            }
        })
        

    })

}
