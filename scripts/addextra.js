$(document).ready(function() {
    
    if(document.body.contains(document.getElementById('addExtra'))) {

        let addExtra = document.getElementById('addExtra');
        let extraField = document.querySelectorAll('input[data-id]');

        addExtra.addEventListener('click', function() {
            
            for(let i = 0; i < extraField.length; i++) {
                
                let element = extraField[i];
                let extraQuantity = element.value;
                let extraId = element.getAttribute('data-id');
                let price = element.getAttribute('data-price');
                let amount = extraQuantity * price;
                
                if(document.body.contains(document.getElementById('referenceCode'))) {
                    
                    let referenceCode = document.getElementById('referenceCode').innerText;
                    // console.log(extraQuantity);
                    if(extraQuantity !== 0) {

                        $.ajax({
                            type: 'POST',
                            url: '/coralview/functions/admin/add_extra.php',
                            data: {reference_no: JSON.stringify(referenceCode), extra_id: JSON.stringify(extraId), quantity: JSON.stringify(extraQuantity), amount: JSON.stringify(amount) },
                            success: function(data) {
                                
                                toastr.success('Additional Extras successfully entered');

                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                              
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        })
    
                    }


                }
                
              

            }
            toastr.success('Extras Successfully Added!');
        });
        
    }

    

});

function attachExtras(code) {

    let extraList = document.getElementById('extraList');

    $.ajax({
        type: 'GET',
        url: '/coralview/functions/admin/list_extra.php',
        data: {reference_no: JSON.stringify(code) },
        success: function(data) {
            //htmlExtraAttach(data);
            location.reload();
        },
        error: function(data) {
            console.log(data);
        }
    })

}
