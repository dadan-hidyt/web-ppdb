<?php
/**
 * @author dadan hidayat
 * */
include __DIR__.DS."AjaxFunctions.php";
$ajaxType = input_get("type");
switch ($ajaxType) {
	case 'new_submit_form':
	submitPendaftaran();
	break;
	default:
		// code...
	break;
}
?>