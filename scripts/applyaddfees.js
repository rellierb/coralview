let applyAddFees = document.getElementById('applyAddFees');
let addPayment = document.getElementById('addPayment');
let addDescription = document.getElementById('addDescription');

if(document.body.contains(document.getElementById('applyAddFees'))) {

    applyAddFees.addEventListener('click', function() {

        let amount = addPayment.value;
        let description = addDescription.value;

        $.ajax({
            type: 'POST',
            url: '/coralview/functions/admin/apply_additional_fees.php',
            data: {reference_no: JSON.stringify(referenceCode), amount: JSON.stringify(amount), description: JSON.stringify(description)  },
            success: function(data) {
                console.log(data);
                toastr.success('Additional Fees successfully entered');
            },
            error: function(data) {
                console.log(data);
            }
        })

    })

}
