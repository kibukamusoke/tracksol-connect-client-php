<?php

include_once 'init.php';


switch ($_POST['func']) {

    case 'formfield':
        unset($_POST['func']);
        $upsert = $__PICKLIST->upsertCustomFormField($_POST);
        echo $upsert['message'];
        break;
    case 'picklistitem':
        $upsert = $__PICKLIST->upsertPicklistItem($_POST);
        echo $upsert['message'];
        break;

    case 'delete_picklistitem':
        $delete = $__PICKLIST->upsertPicklistItem(array('idx' => $_POST['idx'], 'status' => 9));
        echo $delete['message'];
        break;

    case 'delete_form_field':
        $delete = $__PICKLIST->upsertCustomFormField(array('idx' => $_POST['idx'], 'status' => 9));
        echo $delete['message'];
        break;

    default:
        echo 'Unknown function';
        break;

}
?>