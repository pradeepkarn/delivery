<nav class="navbar navbar-expand-lg navbar-dark bg-light mt-2">
    <div class="">
        <button class="navbar-toggler bg-success mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if (is_superuser()) : ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item px-1">
                        <div class="d-grid">

                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin"><i class="fa-solid fa-house-user"></i> Home</a>


                        </div>
                    </li>
                    <li class="nav-item px-1">
                        <div class="d-grid">
                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin/edit-account"><i class="fa-solid fa-user-shield"></i> Edit My Account</a>
                        </div>
                    </li>

                    <!-- <li class="nav-item px-1">
                            <div class="d-grid">
                            <a class="btn btn-sm btn-success" href="/<?php // echo home; 
                                                                        ?>/admin/purchase-orders"><i class="fa-solid fa-receipt"></i> My Purchases</a>
                            </div>
                        </li> -->
                    <!-- <li class="nav-item px-1">
                            <div class="d-grid">
                            <a class="btn btn-sm btn-success" href="/<?php // echo home; 
                                                                        ?>/admin/address-book">Address Book</a>
                            </div>
                        </li> -->
                    <li class="nav-item px-1">
                        <div class="d-grid">
                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin/change-password"><i class="fa-solid fa-key"></i> Change My Password</a>
                        </div>
                    </li>

                     <li class="nav-item px-1">
                        <div class="d-grid">
                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin/users/add-new-item/?user_group=user"><i class="fa-solid fa-user-plus"></i> Add User</a>
                        </div>
                    </li>
                     <li class="nav-item px-1">
                        <div class="d-grid">
                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin/users/add-new-item/?user_group=driver"><i class="fa-solid fa-user-plus"></i> Add Driver</a>
                        </div>
                    </li>
                     
                    <!--
                    <li class="nav-item px-1">
                        <div class="d-grid">
                            <a class="btn btn-primary" href="/<?php // echo home; ?>/admin/add-user/?user_group=whmanager"><i class="fa-solid fa-user-plus"></i> Add Warehouse Manager</a>
                        </div>
                    </li> -->


                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>