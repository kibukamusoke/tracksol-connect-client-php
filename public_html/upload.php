

<?php
require_once 'init.php';
$__GB->slack('uploading csv data.');

if(!empty($_FILES['file']))
{
    $path = "uploads/";
    $upload = $__PICKLIST->uploadCSV($_FILES['file']['tmp_name']);
    $__GB->slack('uploading csv data.');
    $__GB->slack(implode(" ",$upload));
    //$path = $path . basename( $_FILES['file']['name']);
    /*if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
        echo "The file ".  basename( $_FILES['file']['name']).
            " has been uploaded";
    } else{
        echo "There was an error uploading the file, please try again!";
    }*/

    if ($upload['success']) {
        echo $__GB->DisplayError($upload['message'], 'green');
        $success = $__GB->b64encode($upload['message']);
        $entr = $__GB->b64encode('green');
    } else {
        echo $__GB->DisplayError($upload['message'], 'red');
        $success = $__GB->b64encode($upload['message']);
        $entr = $__GB->b64encode('red');
    }

    header('Location: ' . $site_url . '/picklists/res/' . $success . '/' . $entr);
}
?>