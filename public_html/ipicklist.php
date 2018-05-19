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


if (isset($_POST['code'])) {
    $success = '';
    $entr = '';
    $upsert = $__PICKLIST->upsertPicklist($_POST);
    if ($upsert['success']) {
        echo $__GB->DisplayError($upsert['message'], 'green');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('green');
    } else {
        echo $__GB->DisplayError($upsert['message'], 'red');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('red');
    }

    header('Location: ' . $site_url . '/ipicklist/res/' . $success . '/' . $entr);
}


$editable = array('code' => '', 'description' => '', 'type' => 'so', 'destination_location_id' => 1);
if (isset($_GET['idx']) && isset($_GET['action']) && $_GET['action'] == 'modify') {
    $idx = $__DB->escape_string($_GET['idx']);
    $query = $__DB->select($_CLASSES['TABLES']['picklist'], '*', 'idx = ' . $idx . ' AND status = 0', null, 1);
    $result = $__DB->fetch_object($query);
    if ($result) {
        $editable['code'] = $result->code;
        $editable['description'] = $result->description;
        $editable['type_id'] = $result->type_id;
        $editable['destination_location_id'] = $result->destination_location_id;
        $editable['form_idx'] = $result->form_idx;
        $editable['collection_id'] = $result->collection_id;
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
                                <li><span class="bread-blod">Picklist</span>
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
                                <li><span class="bread-blod">Picklist</span>
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
                            <h1>Create Picklist</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="all-form-element-inner">
                                        <form action="" id="loginForm" method="post">
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="form-group-inner">
                                                        <div class="row">

                                                            <?php if (isset($idx)) { ?>
                                                                <div class="form-group-inner">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label class="login2 pull-right pull-right-pro">idx
                                                                                = <? echo $idx; ?></label>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <input type="hidden" class="form-control"
                                                                                   name="idx" value="<? echo $idx; ?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>

                                                        <div class="col-md-3">
                                                            <label class="login2 pull-right pull-right-pro">Action</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="collection_id">

                                                                <?php

                                                                $collections = $__CONNECT->getPicklistCollections();
                                                                foreach ($collections as $collection) {
                                                                    echo '<option value="' . $collection->get('fileId') . '" ' . ($collection->get('fileId') == $editable['collection_id'] ? ' selected ' : ' ') . '>' . $collection->get('name') . '</option>';
                                                                }

                                                                ?>

                                                            </select>
                                                        </div>

                                                    <br><br><br>
                                                    <div class="col-md-3">
                                                        <label class="login2 pull-right pull-right-pro">Picklist
                                                            Code</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="code"
                                                               value="<? echo $editable['code']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="login2 pull-right pull-right-pro">type</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="type_id">

                                                            <?php

                                                            $typesQuery = $__DB->select($_CLASSES['TABLES']['picklistType'], '*', 'status = 0', 'type_id ASC');
                                                            while ($type = $__DB->fetch_assoc($typesQuery)) {
                                                                echo '<option value="' . $type['type_id'] . '" ' . ($type['type_id'] == $editable['type_id'] ? ' selected ' : ' ') . ' >' . $type['name'] . '</option>';
                                                            }

                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="login2 pull-right pull-right-pro">Description</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="description"
                                                               value="<? echo $editable['description']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="login2 pull-right pull-right-pro">Destination
                                                            Location</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="destination_location_id">
                                                            <option value="0">None</option>
                                                            <?php
                                                            $locationsQuery = $__DB->select($_CLASSES['TABLES']['picklistLocation'], '`*`', '`status` = 0', 'updatedAt DESC');
                                                            while ($result = $__DB->fetch_assoc($locationsQuery)) {
                                                                echo '<option value="' . $result['idx'] . '" ' . ($editable['destination_location_id'] == $result['idx'] ? 'selected ' : ' ') . '>' . $result['name'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="login2 pull-right pull-right-pro">Form</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="form_idx">
                                                            <option value="0">None</option>
                                                            <?php
                                                            $frmQuery = $__DB->select($_CLASSES['TABLES']['picklistCustomForm'], '`*`', '`status` = 0 AND `level`=\'document\'', 'updatedAt DESC');
                                                            while ($result = $__DB->fetch_assoc($frmQuery)) {
                                                                echo '<option value="' . $result['idx'] . '" ' . ($editable['form_idx'] == $result['idx'] ? 'selected ' : ' ') . ' >' . $result['name'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-9">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                        type="submit">Save Form
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


<!-- form fields -->
<?php if (strlen($editable['code']) > 2) { ?>
    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Picklist <span class="table-project-n">Items</span></h1>
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
                                       data-show-columns="true" data-show-pagination-switch="true"
                                       data-show-refresh="true"
                                       data-key-events="true" data-show-toggle="true" data-resizable="true"
                                       data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                       data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                    <tr>
                                        <th data-field="state" data-checkbox="true"></th>
                                        <th data-field="id">ID</th>
                                        <th data-field="key" data-editable="true">Item Code</th>
                                        <th data-field="type" data-editable="true">Quantity</th>
                                        <th data-field="min" data-editable="true">Location</th>
                                        <th data-field="max" data-editable="true">Form</th>
                                        <th data-field="action">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $q = $__DB->select($_CLASSES['TABLES']['picklistItem'], '*', 'picklist_id = ' . $idx . ' AND status = 0');
                                    while ($result = $__DB->fetch_assoc($q)) {
                                        $location = '';
                                        $form = '';
                                        if (!empty($result['source_location_id'])) {
                                            $locQuery = $__DB->select($_CLASSES['TABLES']['picklistLocation'], '`*`', '`idx` = ' . $result['source_location_id'], 'updatedAt DESC', 1);
                                            $res = $__DB->fetch_assoc($locQuery);
                                            $location = $res['name'];
                                        }
                                        if (!empty($result['form_idx'])) {
                                            $frmQuery = $__DB->select($_CLASSES['TABLES']['picklistCustomForm'], '`*`', '`idx` = ' . $result['form_idx'], 'updatedAt DESC', 1);
                                            $res = $__DB->fetch_assoc($frmQuery);
                                            $form = $res['name'];
                                        }
                                        echo '<tr><td></td>';
                                        echo '<td>' . $result['idx'] . '</td>';
                                        echo '<td>' . $result['code'] . '</td>';
                                        echo '<td>' . $result['quantity'] . '</td>';
                                        echo '<td>' . $location . '</td>';
                                        echo '<td>' . $form . '</td>';
                                        echo '<td>' . '<a href="javascript:(0);" onclick="deletePicklistItem(' . $result['idx'] . ')"><i class="fa indicator-mn fa-trash col-md-6" style="color: red!important;"></i></a>' . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>


                                <button class="btn btn-block btn-warning" data-toggle="modal"
                                        data-target="#modal-default">Add New
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>




<?
require_once 'footer.php';
?>


<div class="modal fade darklayout" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Picklist Item</h4>
            </div>
            <div class="modal-body bg-primary">

                <p class="statusMsg"></p>
                <form action="" id="fields" method="post">

                    <div class="form-group text-danger">
                        <label>Picklist Id</label>
                        <input type="text" class="form-control" name="picklist_id" id="picklist_id"
                               value="<? echo $idx; ?>" required disabled>
                    </div>
                    <!-- text input -->
                    <div class="form-group text-danger">
                        <label>Item Code</label>
                        <input type="text" class="form-control" placeholder="Item Code" name="code" id="code" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control" placeholder="Item Quantity" name="quantity"
                               id="quantity" required>
                    </div>
                    <div class="form-group-inner">
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="login2 pull-right pull-right-pro">Source Location</label>
                            </div>
                            <div class="col-lg-9">
                                <select class="form-control" name="source_location_id" id="source_location_id">
                                    <option value="0">None</option>
                                    <?php
                                    $locationsQuery = $__DB->select($_CLASSES['TABLES']['picklistLocation'], '`*`', '`status` = 0', 'updatedAt DESC');
                                    while ($result = $__DB->fetch_assoc($locationsQuery)) {
                                        echo '<option value="' . $result['idx'] . '">' . $result['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group-inner">
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="login2 pull-right pull-right-pro">Form</label>
                            </div>
                            <div class="col-lg-9">
                                <select class="form-control" name="form_idx" id="form_idx">
                                    <option value="0">None</option>
                                    <?php
                                    $frmQuery = $__DB->select($_CLASSES['TABLES']['picklistCustomForm'], '`*`', '`status` = 0 AND `level`=\'item\'', 'updatedAt DESC');
                                    while ($result = $__DB->fetch_assoc($frmQuery)) {
                                        echo '<option value="' . $result['idx'] . '">' . $result['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer bg-primary">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success submitBtn" data-dismiss="modal"
                        onclick="submitPicklistItem()">Save changes
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


