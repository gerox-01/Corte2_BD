<?php
function GenerarAnctiCSRF(){
   $anticsrf = md5(random_int(1000, 9999));
   $_SESSION['anticsrf'] = $anticsrf;
}
?>