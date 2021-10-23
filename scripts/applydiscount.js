let btnApplyDiscount = document.getElementById('btnApplyDiscount');
let textDiscount = document.getElementById('textDiscount');
let textNetTotal = document.getElementById('textNetTotal');
let textRemainingBalance = document.getElementById('textRemainingBalance');
let textTotalAmount = document.getElementById('textTotalAmount');
let seniorDiscount = document.getElementById('seniorDiscount');
let guestNumber = document.getElementById('guestNumber');
let pwdDiscount = document.getElementById('pwdDiscount');

if(document.body.contains(document.getElementById('btnApplyDiscount'))) {

    btnApplyDiscount.addEventListener('click', function() {

        let dataDiscount = document.querySelectorAll('[data-discount]');

        for(let i = 0; i < dataDiscount.length; i++) {
            
            let element = dataDiscount[i];
            let discountId = element.getAttribute('data-discount');
            let quantity = element.value;
            let referenceCode = document.getElementById('referenceCode').innerText;

            if(quantity !== '' && quantity !== '0') {

                $.ajax({
                    type: 'POST',
                    url: '/coralview/functions/admin/add_discount.php',
                    data: {reference_no: JSON.stringify(referenceCode), discount_id: JSON.stringify(discountId), quantity: JSON.stringify(quantity) },
                    success: function(data) {
                        if(data == 'UPDATE SUCCESS' || data == 'INSERT SUCCESS') {
                            toastr.success('Discount successfully applied');
                            location.reload();
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

            }

            

        }


    })

}
