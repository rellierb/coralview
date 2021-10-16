let foodOrderSubj = document.getElementById('foodOrderSubj');
let foodOrderDesc = document.getElementById('foodOrderDesc');
let addFoodRequest = document.getElementById('addFoodRequest');
let referenceCode = document.getElementById('referenceCode');

if(document.body.contains(document.getElementById('addFoodRequest'))) {

    addFoodRequest.addEventListener('click', function() {

        let subject = foodOrderSubj.value;
        let description = foodOrderDesc.value;
        let code = referenceCode.value;
        
        if(subject === '' || description === '') {
            toastr.error('Subject and Description in Additional Fees is empty');
        } else if (description === '') {
            toastr.error('Description in Additional Fees is empty');
        } else if (subject === '') {
            toastr.error('Subject in Additional Fees is empty');
        } else {

            $.ajax({
                type: 'POST',
                url: '/coralview/functions/user/add_food.php',
                data: {reference_no: JSON.stringify(code), subject: JSON.stringify(subject), description: JSON.stringify(description)  },
                success: function(data) {
                    toastr.success('Additional request successfully entered');

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
