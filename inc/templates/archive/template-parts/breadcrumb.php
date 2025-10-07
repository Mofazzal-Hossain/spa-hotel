 <?php
    // Don't load directly
    defined('ABSPATH') || exit;
    ?>
 <!-- Breadcrumb -->
 <div class="sht-breadcrumb sht-sec-space">
     <div class="tf-container">
         <ul>
             <li>
                 <a href="<?php echo esc_url(home_url('/')); ?>">
                     <?php echo esc_html__('Home', 'spa-hotel-toolkit'); ?>
                     <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                         <path d="M8 15.5L13 10.5L8 5.5" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                     </svg>
                 </a>
             </li>
             <li>
                 <?php echo esc_html__('Hotels', 'spa-hotel-toolkit'); ?>
             </li>
         </ul>
     </div>
 </div>