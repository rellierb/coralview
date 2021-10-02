<?php

include('common/header.php');
include('common/navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();


if(isset($_REQUEST["room_id"])) {
    $room_id = $_REQUEST["room_id"];
}

?>

<div class="container">

    <div class="row">
        <div class="col">



            


            









            
        </div>
    </div>

</div>


<?php

include('common/footer.php');

?>
