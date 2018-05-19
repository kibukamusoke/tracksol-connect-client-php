<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 4:44 PM
 */

?>


<div class="left-sidebar-pro">
    <nav id="sidebar">
        <div class="sidebar-header">
            <a href="#"><img src="<?php echo $site_url;?>/assets/images/logo-small.png" height="150" alt=""/>
            </a>
            <h3>Connect</h3>
            <p><? try { echo($__CONNECT->currentUser->get('fullname')); } catch (\Parse\ParseException $ex) { return $__CONNECT->handleParseError($ex);} ?></p>
            <strong>TC+</strong>
        </div>
        <div class="left-custom-menu-adp-wrap">
            <ul class="nav navbar-nav left-sidebar-menu-pro">
                <li class="nav-item">
                    <a href="index" role="button" aria-expanded="false" class="nav-link"><i
                                class="fa big-icon fa-home"></i> <span class="mini-dn">Dashboard</span> <span
                                class="indicator-right-menu mini-dn"><i
                                    class="fa indicator-mn fa-angle-left"></i></span></a>
                </li>
                <li class="nav-item"><a href="<?php echo $site_url;?>" data-toggle="dropdown" role="button" aria-expanded="false"
                                        class="nav-link dropdown-toggle"><i class="fa big-icon fa-user"
                                                                            aria-hidden="true"></i> <span
                                class="mini-dn">Staff Cards</span> <span class="indicator-right-menu mini-dn"><i
                                    class="fa indicator-mn fa-angle-left"></i></span></a>
                    <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                        <a href="<?php echo $site_url;?>/cards" class="dropdown-item">View All</a>
                        <a href="<?php echo $site_url;?>/icard" class="dropdown-item">Add New</a>
                    </div>
                </li>
                <!--<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                        class="nav-link dropdown-toggle"><i class="fa big-icon fa-users"></i> <span
                                class="mini-dn">Customers</span> <span class="indicator-right-menu mini-dn"><i
                                    class="fa indicator-mn fa-angle-left"></i></span></a>
                    <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                        <a href="<?php echo $site_url;?>/customers" class="dropdown-item">View All</a>
                        <a href="<?php echo $site_url;?>/icustomer" class="dropdown-item">Add New</a>
                    </div>
                </li>
                <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                        class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span
                                class="mini-dn">Meter Readings</span> <span class="indicator-right-menu mini-dn"><i
                                    class="fa indicator-mn fa-angle-left"></i></span></a>
                    <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                        <a href="<?php echo $site_url;?>/meter-readings" class="dropdown-item">View All</a>
                    </div>
                </li> -->

                <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                        class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span
                                class="mini-dn">Picklist</span> <span class="indicator-right-menu mini-dn"><i
                                    class="fa indicator-mn fa-angle-left"></i></span></a>
                    <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                        <a href="<?php echo $site_url;?>/picklist-collections" class="dropdown-item">Actions</a>
                        <a href="<?php echo $site_url;?>/picklist-locations" class="dropdown-item">Locations</a>
                        <a href="<?php echo $site_url;?>/picklist-forms" class="dropdown-item">Forms</a>
                        <a href="<?php echo $site_url;?>/picklists" class="dropdown-item">Picklists</a>
                        <a href="<?php echo $site_url;?>/picklist-log" class="dropdown-item">Pick Log</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>


