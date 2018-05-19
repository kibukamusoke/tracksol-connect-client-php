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


if (isset($_POST['name']) && isset($_POST['description'])) {
    $success = '';
    $entr = '';
    $upsert = $__PICKLIST->upsertCustomForm($_POST);
    if ($upsert['success']) {
        echo $__GB->DisplayError($upsert['message'], 'green');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('green');
    } else {
        echo $__GB->DisplayError($upsert['message'], 'red');
        $success = $__GB->b64encode($upsert['message']);
        $entr = $__GB->b64encode('red');
    }

    header('Location: ' . $site_url . '/ipicklist-form/res/' . $success . '/' . $entr);
}




$editable = array('name' => '', 'description' => '', 'level' => 'document', 'fields' => array());
if (isset($_GET['idx']) && isset($_GET['action']) && $_GET['action'] == 'modify') {
    $idx = $__DB->escape_string($_GET['idx']);
    $query = $__DB->select($_CLASSES['TABLES']['picklistCustomForm'], '*', 'idx = ' . $idx . ' AND status = 0', null, 1);
    $result = $__DB->fetch_object($query);
    if ($result) {
        $editable['name'] = $result->name;
        $editable['description'] = $result->description;
        $editable['level'] = $result->level;
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
                                <li><span class="bread-blod">Custom Form</span>
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
                                <li><span class="bread-blod">Custom Form</span>
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
                            <h1>Create Form</h1>
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

                                                            <?php if (isset($idx)) { ?>
                                                                <div class="form-group-inner">
                                                                    <div class="row">
                                                                        <div class="col-lg-3">
                                                                            <label class="login2 pull-right pull-right-pro">idx
                                                                                = <? echo $idx; ?></label>
                                                                        </div>
                                                                        <div class="col-lg-9">
                                                                            <input type="hidden" class="form-control"
                                                                                   name="idx" value="<? echo $idx; ?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Form
                                                            Name</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="name"
                                                               value="<? echo $editable['name']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Level</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <select class="form-control" name="level">
                                                            <option value="document" <?php echo $editable['level'] === 'document' ? 'selected' : ''; ?> >
                                                                Document
                                                            </option>
                                                            <option value="item" <?php echo $editable['level'] === 'item' ? 'selected' : ''; ?> >
                                                                Item
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label class="login2 pull-right pull-right-pro">Description</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="description"
                                                               value="<? echo $editable['description']; ?>"/>
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
<?php if (strlen($editable['name']) > 2) { ?>
    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Form <span class="table-project-n">Fields</span></h1>
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
                                        <th data-field="key" data-editable="true">Field Key</th>
                                        <th data-field="type" data-editable="true">Type</th>
                                        <th data-field="min" data-editable="true">Min Len</th>
                                        <th data-field="max" data-editable="true">Max Len</th>
                                        <th data-field="action">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $types = array('S' => 'String', 'M' => 'Money', 'N' => 'Number');
                                    $q = $__DB->select($_CLASSES['TABLES']['picklistCustomFormField'], '*', ' status = 0 AND form_id = ' . $idx);
                                    while ($result = $__DB->fetch_assoc($q)) {
                                        echo '<tr><td></td>';
                                        echo '<td>' . $result['idx'] . '</td>';
                                        echo '<td>' . $result['key'] . '</td>';
                                        echo '<td>' . ($result['type'] ? $types[$result['type']] : 'Unknown') . '</td>';
                                        echo '<td>' . $result['minlen'] . '</td>';
                                        echo '<td>' . $result['maxlen'] . '</td>';
                                        echo '<td>' . '<a href="javascript:;" onclick="deleteFormField(\''. $result['idx'] .'\')"><i class="fa indicator-mn fa-trash col-md-6" style="color: red!important;"></i></a>' . '</td>';
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


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header bg-primary text-light">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Form Field</h4>
            </div>
            <div class="modal-body bg-primary">

                <p class="statusMsg"></p>
                <form action="" id="fields" method="post">

                    <div class="form-group text-danger">
                        <label >Form Id</label>
                        <input type="text" class="form-control" name="form_id" id="form_id" value="<?echo $idx;?>" required disabled>
                    </div>
                    <!-- text input -->
                    <div class="form-group text-danger">
                        <label >Field Key</label>
                        <input type="text" class="form-control" placeholder="field key" name="key" id="key" required>
                    </div>
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="form-control" name="type" id="type">
                            <option value="S">String</option>
                            <option value="N">Number</option>
                            <option value="M">Money</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Min Length</label>
                        <input type="number" class="form-control" placeholder="minimum length" name="minlen" id="minlen" required>
                    </div>
                    <div class="form-group">
                        <label>Max Length</label>
                        <input type="number" class="form-control" placeholder="maximum length" name="maxlen" id="maxlen" required>
                    </div>

                </form>

            </div>
            <div class="modal-footer bg-primary">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success submitBtn" data-dismiss="modal" onclick="submitFormField()">Save changes
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


