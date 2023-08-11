<ul class="nav flex-column">

    <?php if (PASS) : ?>
      
        <?php if (is_superuser()) { ?>
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/categories"> <i class="fa-solid fa-list"></i> Product Categories </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link text-white" href="/<?php // echo home; ?>/admin/service-categories"> <i class="fa-solid fa-list"></i> Service Categories </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/products"> <i class="fa-solid fa-list"></i> Products </a>
            </li>
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/bookings"> <i class="fa-solid fa-list"></i> Bookings </a>
            </li>
         
           
         
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/promotions"> <i class="fa-solid fa-rectangle-list"></i> Promotions </a>
            </li>
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/coupons"> <i class="fa-solid fa-rectangle-list"></i> Coupons </a>
            </li>
            <?php } ?>
            <?php if (USER['user_group']=="salesman" || USER['user_group']=="admin" || USER['role']=="superuser") { ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/orders"> <i class="fa-solid fa-rectangle-list"></i> Orders </a>
            </li>
            <?php } ?>
            
            <hr style="color:white;">
            <?php if (is_superuser()) { ?>
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/sliders"> <i class="fa-solid fa-rectangle-list"></i> Sliders </a>
            </li>
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/sliders"> <i class="fa-solid fa-rectangle-list"></i> Sliders </a>
            </li>
          
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/enquiries"> <i class="fa-solid fa-rectangle-list"></i> Enquires </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/users/list/?user_group=user"> <i class="fa-solid fa-users"></i> Users </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/users/list/?user_group=driver"> <i class="fa-solid fa-users"></i> Driver </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/wallets/list/?wallet_group=driver"> <i class="fa-solid fa-rectangle-list"></i> Wallets (Drivers) </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/wallets/list/?wallet_group=user"> <i class="fa-solid fa-rectangle-list"></i> Wallets (Users) </a>
            </li>
            
            <hr style="color:white;">
            <?php if (is_superuser()) { ?>
            <li class="nav-item hide">
                <a class="nav-link text-white" href="/<?php echo home; ?>/admin/pages"> <i class="fa-solid fa-rectangle-list"></i> Pages </a>
            </li>
            <?php } ?>
        <?php } ?>




    <?php endif; ?>

</ul>