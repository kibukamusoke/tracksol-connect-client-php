<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 5:25 PM
 */

?>

<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul class="mobile-menu-nav">
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
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
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
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
