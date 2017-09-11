<?php

// Pass Authentication Level to the view
$authLevel=fAuthorization::getUserAuthLevel();
$smarty->assign('authLevel',$authLevel);

?>
