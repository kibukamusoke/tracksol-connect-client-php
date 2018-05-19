<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:30 AM
 */

require_once 'init.php';
require_once 'header.php';

if (isset($_POST['cardNo']) && isset($_POST['type']) && isset($_POST['name'])) {
    $success = '';
    $entr = '';
    $upsert = $__CONNECT->upsertCard($_POST);
    if($upsert['success']) {
        echo $__GB->DisplayError($upsert['message'],'green');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('green');
    } else {
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('red');
    }

    header('Location: ' . $site_url . '/cards/res/'.$success.'/'.$entr);
}

$editable = array('cardNo' => '', 'name' => '', 'type' => 1);
if(isset($_GET['cardNo']) && isset($_GET['action']) && $_GET['action'] == 'modify') {
    $cardNo = $__DB->escape_string($_GET['cardNo']);
    // $query = $__DB->select($_CLASSES['TABLES']['card'], '*', 'cardNo = ' . '\''. $cardNo .'\' AND status = 0', null, 1);
    // $result = $__DB->fetch_object($query);
    $cards = $__CONNECT->getStaffCards($cardNo);

    if(sizeof($cards) > 0){
        $card = $cards[0];
        $editable['cardNo'] = $card->get('cardNo');
        $editable['name'] = $card->get('name');
        $editable['type'] = $card->get('type');
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
                                <h2>Cards</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Card</span>
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
                                <h2>Cards</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Card</span>
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
                            <h1>Create Card</h1>
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
                                                            <div class="col-lg-3">
                                                                <label class="login2 pull-right pull-right-pro">Card
                                                                    Type</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value"
                                                                            name="type">
                                                                        <option value="0" <? echo($editable['type'] == 0 ? 'selected' : '')?>>Supervisor</option>
                                                                        <option value="1" <? echo($editable['type'] == 1 ? 'selected' : '')?>>Staff</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Card
                                                            Number</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="cardNo" value="<?echo $editable['cardNo']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Staff
                                                            Name</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="name" value="<?echo $editable['name']; ?>"/>
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

