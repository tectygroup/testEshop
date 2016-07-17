<?php
include 'framework.php';
$fram=new framwork();
$fram->plugIn('backend/engine');
$fram->setFramwork();
$fram->page->setPresentation();
echo $fram->page->getPage();