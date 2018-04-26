<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:30 AM
 */

require_once 'init.php';
require_once 'header.php';

if (isset($_POST['customer_name']) && isset($_POST['customer_number']) && isset($_POST['meter_number'])) {
    $success = '';
    $entr = '';
    var_dump($_POST);
    $upsert = $__NWSC->upsertCustomer($_POST);
    if($upsert['success']) {
        echo $__GB->DisplayError($upsert['message'],'green');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('green');
    } else {
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('red');
    }

    header('Location: ' . $site_url . '/customers/res/'.$success.'/'.$entr);
}

$editable = array('customer_name' => '', 'customer_number' => '', 'meter_number' => '', 'property_number' => '');
if(isset($_GET['idx']) && isset($_GET['action']) && $_GET['action'] == 'modify') {
    $idx = $__DB->escape_string($_GET['idx']);
    $query = $__DB->select($_CLASSES['TABLES']['customer'], '*', 'idx = ' . '\''. $idx .'\' AND status = 0', null, 1);
    $result = $__DB->fetch_object($query);
    if($result){
        $editable['customer_name'] = $result->customer_name;
        $editable['customer_number'] = $result->customer_number;
        $editable['meter_number'] = $result->meter_number;
        $editable['property_number'] = $result->property_number;
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
                                <h2>Customers</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Customers</span>
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
                                <h2>Customers</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Customer</span>
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
                            <h1>Create/Edit Customer</h1>
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
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <label class="login2 pull-right pull-right-pro">Customer Name</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <input type="text" class="form-control" name="customer_name" value="<?echo $editable['customer_name']; ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Customer Phone</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="customer_number" value="<?echo $editable['customer_number']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Property Number</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="property_number" value="<?echo $editable['property_number']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Meter Number</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="meter_number" value="<?echo $editable['meter_number']; ?>"/>
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
                                                                        type="submit">Save Change
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

