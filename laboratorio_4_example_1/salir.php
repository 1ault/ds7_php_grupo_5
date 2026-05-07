<?php
        setcookie("nombre_cookie", "", time() - (60 * 5), "/");
        header("Location: index.php");
 exit;
?>