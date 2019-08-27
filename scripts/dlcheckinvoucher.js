let dlCheckInVoucher = document.getElementById('dlCheckInVoucher');
// let remainingBalance = document.getElementById('remainingBalance');
// let dpAmount = document.getElementById('dpAmount');
// let dpDescription = document.getElementById('dpDescription');
let referenceCode = document.getElementById('referenceCode').innerText;

if(document.body.contains(document.getElementById('dlCheckInVoucher'))) {

    dlCheckInVoucher.addEventListener('click', function() {

        $.ajax({
            type: 'POST',
            url: '/coralview/functions/admin/generate_check_in_voucher.php',
            data: {reference_no: JSON.stringify(referenceCode) },
            success: function(data) {
                console.log(data);
                toastr.success('Payment succcessfully entered');
            },
            error: function(data) {
                console.log(data);
            }
        })

    })

}
