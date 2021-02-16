<?php
/**
 * Hide VAT labels on cart and checkout
 */

if(kibooGetOption('show_vat_labels') == 0){
    add_action('wp_head', 'kibooVATLabels', 100);
}


function kibooVATLabels(){
 /*echo "<style>
.woocommerce-cart .tax_label, .woocommerce-checkout .tax_label {
    display: none;
}

.woocommerce-cart .includes_tax, .woocommerce-checkout .includes_tax {
    display: none;
}
 </style>";*/
 
}