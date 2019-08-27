let btnApplyPayment = document.getElementById('btnApplyPayment');
let remainingBalance = document.getElementById('remainingBalance');
let dpAmount = document.getElementById('dpAmount');
let dpDescription = document.getElementById('dpDescription');

if(document.body.contains(document.getElementById('referenceCode'))) {
    let referenceCode = document.getElementById('referenceCode').innerText;
}

if(document.body.contains(document.getElementById('btnApplyPayment'))) {

    btnApplyPayment.addEventListener('click', function() {

        let balance = remainingBalance.innerText;
        let trimTotalBalance = parseInt(balance.replace(',', ''));
        let inputAmount = dpAmount.value;
        let description = dpDescription.value;
        
        if(inputAmount > trimTotalBalance) {
            toastr.error('Invalid Amount');
        } else if (inputAmount < trimTotalBalance) {
            toastr.error('Invalid Amount');
        } else {

            $.ajax({
                type: 'POST',
                url: '/coralview/functions/admin/apply_payment.php',
                data: {reference_no: JSON.stringify(referenceCode), amount_paid: JSON.stringify(inputAmount), description: JSON.stringify(description) },
                success: function(data) {
                    console.log(data);
                    toastr.success('Payment succcessfully entered');
                },
                error: function(data) {
                    console.log(data);
                }
            })

        }

    })

}
