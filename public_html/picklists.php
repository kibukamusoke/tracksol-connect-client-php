<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:30 AM
 */

require_once 'init.php';
require_once 'header.php';

if (isset($_GET['success']) && isset($_GET['entr'])) // display error or success
    echo $__GB->DisplayError($__GB->b64decode($_GET['success']), $__GB->b64decode($_GET['entr']));

if (isset($_GET['idx']) && $_GET['action']) { //destroy collection
    if ($_GET['action'] == 'deactivate') {
        $data = array('idx' => (int)$_GET['idx']);
        $data['status'] = 9;
        $upsert = $__PICKLIST->upsertPicklist($data);
        if ($upsert['success']) {
            echo $__GB->DisplayError($upsert['message'], 'green');
            $success = $__GB->b64encode($upsert['message']);
            $entr = $__GB->b64encode('green');
        } else {
            echo $__GB->DisplayError($upsert['message'], 'red');
            $success = $__GB->b64encode($upsert['message']);
            $entr = $__GB->b64encode('red');
        }

        header('Location: ' . $site_url . '/picklists/res/' . $success . '/' . $entr);
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
                                <li><span class="bread-blod">Lists</span>
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
                                <li><a href="#">Lists</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Lists</span>
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
                            <h1>Picklists <span class="table-project-n"></span></h1>
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
                                    <th data-field="code" data-editable="true">Code</th>
                                    <th data-field="type" data-editable="true">Type</th>
                                    <th data-field="description" data-editable="true">Description</th>
                                    <th data-field="location" data-editable="true">Location</th>
                                    <th data-field="form" data-editable="true">Form</th>
                                    <th data-field="collection" data-editable="true">Action</th>
                                    <th data-field="created" data-editable="true">Created</th>
                                    <th data-field="action">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $collections = $__CONNECT->getPicklistCollections();
                                // foreach ($picklists as $picklist) {
                                $query = $__DB->select($_CLASSES['TABLES']['picklist'], '`*`', '`status` = 0', 'updatedAt DESC');
                                while ($result = $__DB->fetch_assoc($query)) {
                                    $location = '';
                                    $form = '';
                                    $type = '';
                                    $collection = '';
                                    if (!empty($result['collection_id'])) {

                                        foreach ($collections as $cx) {
                                            if ($cx->get('fileId') == $result['collection_id'])
                                                $collection = $cx->get('name');
                                        }
                                    }
                                    if (!empty($result['destination_location_id'])) {
                                        $locQuery = $__DB->select($_CLASSES['TABLES']['picklistLocation'], '`*`', '`idx` = ' . $result['destination_location_id'], 'updatedAt DESC', 1);
                                        $res = $__DB->fetch_assoc($locQuery);
                                        $location = $res['name'];
                                    }
                                    if (!empty($result['form_idx'])) {
                                        $frmQuery = $__DB->select($_CLASSES['TABLES']['picklistCustomForm'], '`*`', '`idx` = ' . $result['form_idx'], 'updatedAt DESC', 1);
                                        $res = $__DB->fetch_assoc($frmQuery);
                                        $form = $res['name'];
                                    }
                                    if (!empty($result['type_id'])) {
                                        $typeQuery = $__DB->select($_CLASSES['TABLES']['picklistType'], '`*`', '`type_id` = ' . $result['type_id'], 'updatedAt DESC', 1);
                                        $res = $__DB->fetch_assoc($typeQuery);
                                        $type = $res['name'];
                                    }
                                    echo '<tr><td></td>';
                                    echo '<td>' . $result['idx'] . '</td>';
                                    echo '<td>' . $result['code'] . '</td>';
                                    echo '<td>' . $type . '</td>';
                                    echo '<td>' . $result['description'] . '</td>';
                                    echo '<td>' . $location . '</td>';
                                    echo '<td>' . $form . '</td>';
                                    echo '<td>' . $collection . '</td>';
                                    echo '<td>' . $result['updatedAt'] . '</td>';
                                    echo '<td>' . '<a href="' . $site_url . '/ipicklist/' . $result['idx'] . '/modify"><i class="fa indicator-mn fa-edit col-md-6" style="color: yellow!important;"></i></a><a href="' . $site_url . '/picklists/' . $result['idx'] . '/deactivate"><i class="fa indicator-mn fa-trash col-md-6" style="color: red!important;"></i></a>' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>

                            <a href="/ipicklist">
                                <button class="btn btn-block btn-warning">Add New</button>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="multi-uploaded-area mg-b-15">
    <div class="row">
        <div class="col-lg-12">
            <div class="dropzone-pro shadow-reset nt-mg-b-30">
                <div id="dropzone1">
                    <form action="/upload" class="dropzone dropzone-custom needsclick" id="demo1-upload" enctype="multipart/form-data" method="POST">
                        <div class="dz-message needsclick download-custom">
                            <span class="adminpro-icon adminpro-cloud-computing-down download-icon"></span>
                            <h2>Drop files here or click to upload.</h2>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Multi Upload End-->

<?
require_once 'footer.php';
?>


