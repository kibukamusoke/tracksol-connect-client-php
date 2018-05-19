<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:30 AM
 */

require_once 'init.php';
require_once 'header.php';

if (isset($_POST['name']) && isset($_POST['description'])) {
    $success = '';
    $entr = '';
    $upsert = $__PICKLIST->upsertLocation($_POST);
    if($upsert['success']) {
        echo $__GB->DisplayError($upsert['message'],'green');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('green');
    } else {
        echo $__GB->DisplayError($upsert['message'],'red');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('red');
    }

    header('Location: ' . $site_url . '/picklist-locations/res/'.$success.'/'.$entr);
}


$editable = array('name' => '', 'description' => '');
if(isset($_GET['idx']) && isset($_GET['action']) && $_GET['action'] == 'modify') {
    $idx = $__DB->escape_string($_GET['idx']);
    $query = $__DB->select($_CLASSES['TABLES']['picklistLocation'], '*', 'idx = ' . $idx .' AND status = 0', null, 1);
    $result = $__DB->fetch_object($query);
    if($result){
        $editable['name'] = $result->name;
        $editable['description'] = $result->description;
    }
}

?>

<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="breadcome-heading">
                                <h2>Picklist</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Location</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End-->
<!-- Mobile Menu start -->
<?php include_once 'mobile-menu.php' ?>
<!-- Mobile Menu end -->
<!-- Breadcome start-->
<div class="breadcome-area des-none">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="breadcome-heading">
                                <h2>Picklist</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Location</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcome End-->
<div class="basic-form-area mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sparkline12-list shadow-reset mg-t-30">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <h1>Create Location</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="all-form-element-inner">
                                        <form action="" id="loginForm" method="post">
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="form-group-inner">
                                                        <div class="row">

                                                            <?php if(isset($idx)) { ?>
                                                                <div class="form-group-inner">
                                                                    <div class="row">
                                                                        <div class="col-lg-3">
                                                                            <label class="login2 pull-right pull-right-pro">idx  =   <?echo $idx; ?></label>
                                                                        </div>
                                                                        <div class="col-lg-9">
                                                                            <input type="hidden" class="form-control" name="idx" value="<?echo $idx; ?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Location Name</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="name" value="<?echo $editable['name']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Description</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="description" value="<?echo $editable['description']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-3"></div>
                                                        <div class="col-lg-9">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                        type="submit">Save Changes
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?
require_once 'footer.php';
?>

