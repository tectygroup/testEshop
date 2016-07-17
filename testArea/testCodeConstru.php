<?php
include '../framework.php';
$fram=new framwork();
$fram->plugIn('backend/codeConstructure');
$code=new codeConstru();
$code=$code->construCode();
echo $code;
?>
