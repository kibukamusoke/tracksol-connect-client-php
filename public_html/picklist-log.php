<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:30 AM
 */

require_once 'init.php';
require_once 'header.php';


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
                                <li><a href="#">Picklist</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Logs</span>
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
                                <li><span class="bread-blod">Logs</span>
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
                                <h1>Picklist <span class="table-project-n">Logs</span></h1>
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
                                        <th data-field="action" data-editable="true">Action</th>
                                        <th data-field="picklist" data-editable="true">Picklist</th>
                                        <th data-field="item" data-editable="true">Item</th>
                                        <th data-field="source" data-editable="true">Source</th>
                                        <th data-field="destination" data-editable="true">Destination</th>
                                        <th data-field="quantity" data-editable="true">Qty</th>
                                        <th data-field="balance" data-editable="true">Bal</th>
                                        <th data-field="doc_form" data-editable="true">Doc Form</th>
                                        <th data-field="item_form" data-editable="true">Item Form</th>
                                        <th data-field="time" data-editable="true">Date/Time</th>
                                        <th data-field="action">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $collections = $__CONNECT->getPicklistCollections();
                                    // foreach ($picklists as $picklist) {
                                    $query = $__DB->select($_CLASSES['TABLES']['picklistLog'], '`*`', null, 'created_dt DESC');
                                    while ($result = $__DB->fetch_assoc($query)) {
                                        $location = '';
                                        $form = '';
                                        $type = '';
                                        $collection = '';
                                        if (!empty($result['collection_id'])) {

                                            foreach ($collections as $cx) {
                                                if($cx->get('fileId') == $result['collection_id'])
                                                    $collection = $cx->get('name');
                                            }
                                        }
                                        echo '<tr><td></td>';
                                        echo '<td>' . $result['idx'] . '</td>';
                                        echo '<td>' . $collection . '</td>';
                                        echo '<td>' . $result['picklist_code'] . '</td>';
                                        echo '<td>' . $result['item_code'] . '</td>';
                                        echo '<td>' . $result['source_location'] . '</td>';
                                        echo '<td>' . $result['destination_location'] . '</td>';
                                        echo '<td>' . $result['quantity'] . '</td>';
                                        echo '<td>' . $result['balance'] . '</td>';
                                        echo '<td>' . str_replace( '\\n', '<br>', $result['document_form']) . '</td>';
                                        echo '<td>' . str_replace( '\\n', '<br>', $result['item_form']) . '</td>';
                                        echo '<td>' . $result['pick_time'] . '</td>';
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