<?php
include "ui/header.php" ;

?>

<?php
echo $_SERVER['HTTP_HOST'];
var_dump (strpos(@get_headers('http://'.$_SERVER['HTTP_HOST'] . ':8000/stream.ogg')[0],'200') === false ? false : true) ;
?>

<?php
include "ui/footer.php" ;
?>