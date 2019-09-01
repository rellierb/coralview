let applyAddFees = document.getElementById('applyAddFees');
let addPayment = document.getElementById('addPayment');
let addDescription = document.getElementById('addDescription');

if(document.body.contains(document.getElementById('applyAddFees'))) {

    applyAddFees.addEventListener('click', function() {

        let amount = addPayment.value;
        let description = addDescription.value;
        let code = referenceCode.innerText;


        if(amount === '' || description === '') {
            toastr.error('Amount and Description in Additional Fees is empty');
        } else if (description === '') {
            toastr.error('Description in Additional Fees is empty');
        } else if (amount === '') {
            toastr.error('Amount in Additional Fees is empty');
        } else {

            $.ajax({
                type: 'POST',
                url: '/coralview/functions/admin/apply_additional_fees.php',
                data: {reference_no: JSON.stringify(code), amount: JSON.stringify(amount), description: JSON.stringify(description)  },
                success: function(data) {
                    
                    toastr.success('Additional Fees successfully entered');

                    setTimeout(function() {

                        location.reload();
                        
                    }, 3000);

                },
                error: function(data) {
                    console.log(data);
                }
            })

        }
        
        

    })

}
