<?php
function GenerarAnctiCSRF(){
   // $_SESSION['anticsrf'] = md5(random_int(1000, 9999));
   // return $_SESSION['anticsrf'];
   $anticsrf = md5(random_int(1000, 9999));
   $_SESSION['anticsrf'] = $anticsrf;
}
?>