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

        // $PWD_DISCOUNT = .2;
        // $discount = 0;
        // $pwd_discount_price = $overall_total_price / $guest_count;
        // $pwd_discount_price *= $PWD_DISCOUNT;
        // $overall_total_price -= $pwd_discount_price;
        // $pwd_count = $_SESSION["pwd_discount"];
        // let totalPrice = textTotalAmount.innerHTML;
        // let trimTotalPrice = totalPrice.replace(',', '');
        
        // let guest = guestNumber.value;
        
        // if(seniorDiscount.value !== '') {

        //     let senior_discount = .2;
        //     let discount = 0;
        //     let senior_discount_price = parseInt(trimTotalPrice) / guest;                    
        //     senior_discount_price *= senior_discount;
        //     trimTotalPrice -= senior_discount_price;
        //     textDiscount.innerHTML = senior_discount_price.toFixed(2);
        //     textNetTotal.innerHTML = trimTotalPrice.toFixed(2);

        // }

        // if(pwdDiscount.value !== '') {

        //     let pwd_discount = .2;

        //     let discount = 0;
            
        //     let pwd_discount_price = parseInt(trimTotalPrice) / guest;
            
        //     pwd_discount_price *= pwd_discount;
        //     trimTotalPrice -= pwd_discount_price;

        //     textDiscount.innerText = pwd_discount_price.toFixed(2);
        //     textNetTotal.innerText = trimTotalPrice.toFixed(2);
            
        // }

        let dataDiscount = document.querySelectorAll('[data-discount]');

        for(let i = 0; i < dataDiscount.length; i++) {
            
            let element = dataDiscount[i];
            let discountId = element.getAttribute('data-discount');
            let quantity = element.value;
            let referenceCode = document.getElementById('referenceCode').innerText;

            $.ajax({
                type: 'POST',
                url: '/coralview/functions/admin/add_discount.php',
                data: {reference_no: JSON.stringify(referenceCode), discount_id: JSON.stringify(discountId), quantity: JSON.stringify(quantity) },
                success: function(data) {
                    toastr.success('Discount successfully applied');
                },
                error: function(data) {
                    console.log(data);
                }
            })

        }


    })

}
