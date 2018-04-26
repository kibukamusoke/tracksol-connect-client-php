<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:30 AM
 */

require_once 'init.php';
require_once 'header.php';

if(isset($_GET['success']) && isset($_GET['entr'])) // display error or success
    echo $__GB->DisplayError($__GB->b64decode($_GET['success']),$__GB->b64decode($_GET['entr']));

if(isset($_GET['cardNo']) && $_GET['action']) { //destroy card
    if($_GET['action'] == 'deactivate'){
        $upsert = $__CONNECT->upsertCard(array('cardNo' => $_GET['cardNo'], 'status' => 9));
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
                                <li><span class="bread-blod">Cards</span>
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
                                <li><span class="bread-blod">Dashboard</span>
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
<div class="data-table-area mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="sparkline13-list shadow-reset">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Staff <span class="table-project-n">Cards</span></h1>
                            <div class="sparkline13-outline-icon">
                                <span class="sparkline13-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span><i class="fa fa-wrench"></i></span>
                                <span class="sparkline13-collapse-close"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <div id="toolbar">
                                <select class="form-control">
                                    <option value="">Export Basic</option>
                                    <option value="all">Export All</option>
                                    <option value="selected">Export Selected</option>
                                </select>
                            </div>
                            <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                   data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                   data-key-events="true" data-show-toggle="true" data-resizable="true"
                                   data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                   data-click-to-select="true" data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="id">ID</th>
                                    <th data-field="cardno" data-editable="true">Card No</th>
                                    <th data-field="cardtype" data-editable="true">Card Type</th>
                                    <th data-field="name" data-editable="true">Name</th>
                                    <th data-field="created" data-editable="true">Created</th>
                                    <th data-field="action">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = $__DB->select($_CLASSES['TABLES']['card'], '`*`', '`status` = 0', 'createdAt DESC');
                                while ($result = $__DB->fetch_assoc($query)) {
                                    echo '<tr><td></td>';
                                    echo '<td>' . $result['idx'] . '</td>';
                                    echo '<td>' . $result['cardNo'] . '</td>';
                                    echo '<td>' . ($result['type'] == 0 ? 'Supervisor' : 'Staff') . '</td>';
                                    echo '<td>' . $result['name'] . '</td>';
                                    echo '<td>' . $result['createdAt'] . '</td>';
                                    echo '<td>' . '<a href="' . $site_url .'/icard/' . $result['cardNo'] . '/modify"><i class="fa indicator-mn fa-edit col-md-6" style="color: yellow!important;"></i></a><a href="' . $site_url .'/cards/' . $result['cardNo'] . '/deactivate"><i class="fa indicator-mn fa-trash col-md-6" style="color: red!important;"></i></a>' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>

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

