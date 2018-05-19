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

if(isset($_GET['idx']) && $_GET['action']) { //destroy collection
    if($_GET['action'] == 'deactivate'){
        $data = array('idx' => (int)$_GET['idx']);
        $data['status'] = 9;
        $upsert = $__PICKLIST->upsertCollection($data);
        if($upsert['success']) {
            echo $__GB->DisplayError($upsert['message'],'green');
            $success = $__GB->b64encode($upsert['message']);
            $entr = $__GB->b64encode('green');
        } else {
            echo $__GB->DisplayError($upsert['message'],'red');
            $success = $__GB->b64encode($upsert['message']);
            $entr = $__GB->b64encode('red');
        }

        header('Location: ' . $site_url . '/picklist-collections/res/'.$success.'/'.$entr);
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
                                <li><span class="bread-blod">Collections</span>
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
                                <li><span class="bread-blod">Collections</span>
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
                            <h1>Picklist <span class="table-project-n">Collections</span></h1>
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
                                    <th data-field="collectionname" data-editable="true">Collection Name</th>
                                    <th data-field="description" data-editable="true">Description</th>
                                    <!-- <th data-field="created" data-editable="true">Updated</th> -->
                                    <!-- <th data-field="action">Action</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $collections = $__CONNECT->getPicklistCollections();
                                foreach ($collections as $collection) {
                                    echo '<tr><td></td>';
                                    echo '<td>' . $collection->get('fileId') . '</td>';
                                    echo '<td>' . $collection->get('name') . '</td>';
                                    echo '<td>' . $collection->get('description') . '</td>';
                                    // echo '<td>' . $picklist->get('updatedAt') . '</td>';
                                     // echo '<td>' . '<a href="' . $site_url .'/ipicklist-collection/' . $result['idx'] . '/modify"><i class="fa indicator-mn fa-edit col-md-6" style="color: yellow!important;"></i></a><a href="' . $site_url .'/picklist-collections/' . $result['idx'] . '/deactivate"><i class="fa indicator-mn fa-trash col-md-6" style="color: red!important;"></i></a>' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>

                            <!-- <a href="/ipicklist-collection"><button class="btn btn-block btn-warning">Add New</button></a> -->

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


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Pick List Collection</h4>
            </div>
            <div class="modal-body">

                <form role="form" action="" id="pick" method="post">
                    <div class="form-group">
                        <label>Idx</label>
                        <input type="number" class="form-control" placeholder="" name="idx" required disabled>
                    </div>
                    <!-- text input -->
                    <div class="form-group">
                        <label>Collection Name</label>
                        <input type="text" class="form-control" placeholder="" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Short Description</label>
                        <input type="text" class="form-control" placeholder="" name="description" required>
                    </div>

                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" data-dismiss="modal">Save changes
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

