<?php
function GenerarAnctiCSRF(){
   $anticsrf = random_int(1000, 9999);
   $_SESSION['anticsrf'] = $anticsrf;
}
?>