<form name="post" action="" method="post">

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Kiboo REST API Authentication', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="kiboo_url"><?=__( 'URL', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[kiboo_url]" id="kiboo_url" type="text" class="regular-text" value="<?=kibooGetOption('kiboo_url'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="kiboo_client_id"><?=__( 'Client ID', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[kiboo_client_id]" id="kiboo_client_id" type="text" class="regular-text" value="<?=kibooGetOption('kiboo_client_id'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="kiboo_client_secret"><?=__( 'Client Secret', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[kiboo_client_secret]" id="kiboo_client_secret" type="password" class="regular-text" value="<?=kibooGetOption('kiboo_client_secret'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="log_hooks"><?=__( 'Log', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[log_hooks]" id="log_hooks" type="radio" value="1" <?=(kibooGetOption('log_hooks') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[log_hooks]" id="log_hooks" type="radio" value="0" <?=(kibooGetOption('log_hooks') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="new_customer_hook"><?=__( 'Informar nuevo cliente', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[new_customer_hook]" id="new_customer_hook" type="radio" value="1" <?=(kibooGetOption('new_customer_hook') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[new_customer_hook]" id="new_customer_hook" type="radio" value="0" <?=(kibooGetOption('new_customer_hook') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="update_customer_hook"><?=__( 'Informar nuevos cambios', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[update_customer_hook]" id="update_customer_hook" type="radio" value="1" <?=(kibooGetOption('update_customer_hook') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[update_customer_hook]" id="update_customer_hook" type="radio" value="0" <?=(kibooGetOption('update_customer_hook') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="new_order_hook"><?=__( 'Informar nueva orden', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[new_order_hook]" id="new_order_hook" type="radio" value="1" <?=(kibooGetOption('new_order_hook') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[new_order_hook]" id="new_order_hook" type="radio" value="0" <?=(kibooGetOption('new_order_hook') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'WooCommerce REST API', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="woo_api_user"><?=__( 'Usuario', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[woo_api_user]" id="woo_api_user" type="text" class="regular-text" value="<?=kibooGetOption('woo_api_user'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="woo_api_pass"><?=__( 'Contraseña', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[woo_api_pass]" id="woo_api_pass" type="password" class="regular-text" value="<?=kibooGetOption('woo_api_pass'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_customers_synch"><?=__( 'Sincronizar clientes', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_customers_synch]" id="enable_customers_synch" type="radio" value="1" <?=(kibooGetOption('enable_customers_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_customers_synch]" id="enable_customers_synch" type="radio" value="0" <?=(kibooGetOption('enable_customers_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <!--<tr>
                    <th scope="row"><label for="enable_current_account_synch"><?=__( 'Current Account synch', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_current_account_synch]" id="enable_current_account_synch" type="radio" value="1" <?=(kibooGetOption('enable_current_account_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_current_account_synch]" id="enable_current_account_synch" type="radio" value="0" <?=(kibooGetOption('enable_current_account_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>-->
                <tr>
                    <th scope="row"><label for="enable_products_synch"><?=__( 'Sincronizar productos', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_products_synch]" id="enable_products_synch" type="radio" value="1" <?=(kibooGetOption('enable_products_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_products_synch]" id="enable_products_synch" type="radio" value="0" <?=(kibooGetOption('enable_products_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_categories_synch"><?=__( 'Sincronizar categorías de productos', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_products_categories_synch]" id="enable_products_categories_synch" type="radio" value="1" <?=(kibooGetOption('enable_products_categories_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_products_categories_synch]" id="enable_products_categories_synch" type="radio" value="0" <?=(kibooGetOption('enable_products_categories_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_categories_synch"><?=__( 'Sincronizar categorias', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_categories_synch]" id="enable_categories_synch" type="radio" value="1" <?=(kibooGetOption('enable_categories_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_categories_synch]" id="enable_categories_synch" type="radio" value="0" <?=(kibooGetOption('enable_categories_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
               <tr>
                    <th scope="row"><label for="enable_attributes_synch"><?=__( 'Sincronizar atributos', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_attributes_synch]" id="enable_attributes_synch" type="radio" value="1" <?=(kibooGetOption('enable_attributes_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_attributes_synch]" id="enable_attributes_synch" type="radio" value="0" <?=(kibooGetOption('enable_attributes_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_brands_synch"><?=__( 'Sincronizar marcas', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_brands_synch]" id="enable_brands_synch" type="radio" value="1" <?=(kibooGetOption('enable_brands_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_brands_synch]" id="enable_brands_synch" type="radio" value="0" <?=(kibooGetOption('enable_brands_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_models_synch"><?=__( 'Sincronizar modelos', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_models_synch]" id="enable_models_synch" type="radio" value="1" <?=(kibooGetOption('enable_models_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_models_synch]" id="enable_models_synch" type="radio" value="0" <?=(kibooGetOption('enable_models_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_images_synch"><?=__( 'Sincronizar imagenes', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_images_synch]" id="enable_images_synch" type="radio" value="1" <?=(kibooGetOption('enable_images_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_images_synch]" id="enable_images_synch" type="radio" value="0" <?=(kibooGetOption('enable_images_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enable_prices_synch"><?=__( 'Sincronizar precios', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[enable_prices_synch]" id="enable_prices_synch" type="radio" value="1" <?=(kibooGetOption('enable_prices_synch') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[enable_prices_synch]" id="enable_prices_synch" type="radio" value="0" <?=(kibooGetOption('enable_prices_synch') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>



    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Customizaciones', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="woo_api_country"><?=__( 'País pretederminado', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[woo_api_country]" id="woo_api_country">
                        <?php
                        global $woocommerce;
                        $countries_obj   = new WC_Countries();
                        $countries   = $countries_obj->__get('countries');
                        foreach($countries as $countryID => $countryName){
                            if(kibooGetOption('woo_api_country') == $countryID){
                                echo '<option value="'.$countryID.'" selected>'.$countryName.'</option>';
                            }else{
                                echo '<option value="'.$countryID.'">'.$countryName.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="default_state"><?=__( 'Provincia predeterminada', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[default_state]" id="default_state">
                        <?php
                        global $wpdb;
                        $states = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_states", OBJECT );
                        foreach($states as $state){
                            if(kibooGetOption('default_state') == $state->ID){
                                echo '<option value="'.$state->ID.'" selected>'.$state->name.'</option>';
                            }else{
                                echo '<option value="'.$state->ID.'">'.$state->name.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="default_city"><?=__( 'Ciudad por defecto', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[default_city]" id="default_city">
                        <?php
                        global $wpdb;
                        $state_id = kibooGetOption('default_state');
                        $cities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_cities WHERE state_id = {$state_id} ORDER BY name", OBJECT );
                        foreach($cities as $city){
                            if(kibooGetOption('default_city') == $city->name){
                                echo '<option value="'.$city->name.'" selected>'.$city->name.'</option>';
                            }else{
                                echo '<option value="'.$city->name.'">'.$city->name.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="default_invoice_type"><?=__( 'Tipo de factura por defecto', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[default_invoice_type]" id="default_invoice_type">
                        <?php
                        $invoice_types = array('A', 'B');
                        foreach($invoice_types as $invoice_type){
                            if(kibooGetOption('default_invoice_type') == $invoice_type){
                                echo '<option value="'.$invoice_type.'" selected>'.$invoice_type.'</option>';
                            }else{
                                echo '<option value="'.$invoice_type.'">'.$invoice_type.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="default_doc_type"><?=__( 'Tipo de documento por defecto', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[default_doc_type]" id="default_doc_type">
                        <?php
                        global $wpdb;
                        $docs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_doc_types", OBJECT );
                        foreach($docs as $doc_type){
                            if(kibooGetOption('default_doc_type') == $doc_type->ID){
                                echo '<option value="'.$doc_type->ID.'" selected>'.$doc_type->name.'</option>';
                            }else{
                                echo '<option value="'.$doc_type->ID.'">'.$doc_type->name.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="default_vat_type"><?=__( 'Categoría de IVA por defecto', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[default_vat_type]" id="default_vat_type">
                        <?php
                        global $wpdb;
                        $vats = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_vat_types", OBJECT );
                        foreach($vats as $vat_type){
                            if(kibooGetOption('default_vat_type') == $vat_type->ID){
                                echo '<option value="'.$vat_type->ID.'" selected>'.$vat_type->name.'</option>';
                            }else{
                                echo '<option value="'.$vat_type->ID.'">'.$vat_type->name.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="registration_add_name"><?=__( 'Agregar nombre y apellido en el registro', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[registration_add_name]" id="registration_add_name" type="radio" value="1" <?=(kibooGetOption('registration_add_name') == 1) ? 'checked' : ''; ?>> <?=__( 'Si', 'kiboo' ); ?>&nbsp;
                        <input name="settings[registration_add_name]" id="registration_add_name" type="radio" value="0" <?=(kibooGetOption('registration_add_name') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wholesaler_vat0"><?=__( 'Mostrar precio neto para mayorista', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[wholesaler_vat0]" id="wholesaler_vat0" type="radio" value="1" <?=(kibooGetOption('wholesaler_vat0') == 1) ? 'checked' : ''; ?>> <?=__( 'Si', 'kiboo' ); ?>&nbsp;
                        <input name="settings[wholesaler_vat0]" id="wholesaler_vat0" type="radio" value="0" <?=(kibooGetOption('wholesaler_vat0') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Carrito', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="show_vat_labels"><?=__( 'Mostrar etiqueta impuesto', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[show_vat_labels]" id="show_vat_labels" type="radio" value="1" <?=(kibooGetOption('show_vat_labels') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[show_vat_labels]" id="show_vat_labels" type="radio" value="0" <?=(kibooGetOption('show_vat_labels') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Pagos ', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="show_vat_labels"><?=__( 'Habilitar cuenta corriente', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[payments]" id="backorders">
                        <?php
                        $payments_options = array(
                            'wholesaler'        => __( 'Solo mayoristas', 'kiboo' ),
                            'allclients'    => __( 'Todos', 'kiboo' ),
                            'no'       => __( 'Ninguno', 'kiboo' ),
                        );

                        foreach($payments_options as $bo_id => $bo_text){
                            if(kibooGetOption('payments') == $bo_id){
                                echo '<option value="'.$bo_id.'" selected>'.$bo_text.'</option>';
                            }else{
                                echo '<option value="'.$bo_id.'">'.$bo_text.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="woo_configurated_orders"><?=__( 'Forzar ingreso como nota de pedido', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[force_bank_transfer]" id="force_bank_transfer" type="radio" value="1" <?=(kibooGetOption('force_bank_transfer') == 1) ? 'checked' : ''; ?>> <?=__( 'Si', 'kiboo' ); ?>&nbsp;
                        <input name="settings[force_bank_transfer]" id="force_bank_transfer" type="radio" value="0" <?=(kibooGetOption('force_bank_transfer') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>                
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Visibilidad de productos sin stock', 'kiboo' ); ?></h3>
        <div class="inside">
            <h3 class="kiboo-block-title"><?=__( 'Mayorista', 'kiboo' ); ?></h3> 
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="display_wholesalers"><?=__( 'Mostrar', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[display_wholesalers]" id="display_wholesalers" type="radio" value="1" <?=(kibooGetOption('display_wholesalers') == 1) ? 'checked' : ''; ?>> <?=__( 'Si', 'kiboo' ); ?>&nbsp;
                        <input name="settings[display_wholesalers]" id="display_wholesalers" type="radio" value="0" <?=(kibooGetOption('display_wholesalers') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <h3 class="kiboo-block-title"><?=__( 'Minorista', 'kiboo' ); ?></h3> 
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="display_wholesalers"><?=__( 'Mostrar', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[display_notwholesalers]" id="display_notwholesalers" type="radio" value="1" <?=(kibooGetOption('display_notwholesalers') == 1) ? 'checked' : ''; ?>> <?=__( 'Si', 'kiboo' ); ?>&nbsp;
                        <input name="settings[display_notwholesalers]" id="display_notwholesalers" type="radio" value="0" <?=(kibooGetOption('display_notwholesalers') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Productos', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="default_price"><?=__( 'Precio por defecto', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[default_price]" id="default_price">
                        <?php
                        $prices = array(
                            'PRICE_A',
                            'PRICE_B',
                            'PRICE_C',
                            'PRICE_D'
                        );
                        foreach($prices as $price){
                            if(kibooGetOption('default_price') == $price){
                                echo '<option value="'.$price.'" selected>'.$price.'</option>';
                            }else{
                                echo '<option value="'.$price.'">'.$price.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <!--
                <tr>
                    <th scope="row"><label for="show_price_with_vat"><?=__( 'Mostrar precios con impuestos', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[show_price_with_vat]" id="show_price_with_vat" type="radio" value="1" <?=(kibooGetOption('show_price_with_vat') == 1) ? 'checked' : ''; ?>> <?=__( 'ON', 'kiboo' ); ?>&nbsp;
                        <input name="settings[show_price_with_vat]" id="show_price_with_vat" type="radio" value="0" <?=(kibooGetOption('show_price_with_vat') == 0) ? 'checked' : ''; ?>> <?=__( 'OFF', 'kiboo' ); ?>
                    </td>
                </tr>
                -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Tarjeta de credito', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="cc_card_plan"><?=__( 'Plan de tarjeta', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[cc_card_plan]" id="cc_card_plan" type="text" class="regular-text" value="<?=kibooGetOption('cc_card_plan'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cc_credit_card"><?=__( 'Tarhet', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[cc_credit_card]" id="cc_credit_card" type="text" class="regular-text" value="<?=kibooGetOption('cc_credit_card'); ?>">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Envío ordenes a Kiboo', 'kiboo' ); ?></h3>
        <div class="inside">
            <h3 class="kiboo-block-title"><?=__( 'Mayoristas', 'kiboo' ); ?></h3> 
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="send_orders_as"><?=__( 'Enviar como', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[send_orders_as_wholesalers]" id="send_orders_as_wholesaler" type="radio" value="1" <?=(kibooGetOption('send_orders_as_wholesalers') == 1) ? 'checked' : ''; ?>> <?=__( 'Nota de crédito', 'kiboo' ); ?>&nbsp;
                        <input name="settings[send_orders_as_wholesalers]" id="send_orders_as_Wholesalers" type="radio" value="0" <?=(kibooGetOption('send_orders_as_wholesalers') == 0) ? 'checked' : ''; ?>> <?=__( 'Factura', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pos_number"><?=__( 'Punto de Venta Nro', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[pos_number_wholesalers]" id="pos_number_wholesalers" type="text" class="regular-text" value="<?=kibooGetOption('pos_number_wholesalers'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_channel"><?=__( 'Servicio de Canal', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[service_channel_wholesalers]" id="service_channel_wholesalers" type="text" class="regular-text" value="<?=kibooGetOption('service_channel_wholesalers'); ?>">
                    </td>
                </tr>
                </tbody>
            </table>

            <h3 class="kiboo-block-title"><?=__( 'Minorista', 'kiboo' ); ?></h3> 
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="send_orders_as"><?=__( 'Enviar como', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[send_orders_as_notwholesalers]" id="send_orders_as_notwholesaler" type="radio" value="1" <?=(kibooGetOption('send_orders_as_notwholesalers') == 1) ? 'checked' : ''; ?>> <?=__( 'Nota de crédito', 'kiboo' ); ?>&nbsp;
                        <input name="settings[send_orders_as_notwholesalers]" id="send_orders_as_notWholesalers" type="radio" value="0" <?=(kibooGetOption('send_orders_as_notwholesalers') == 0) ? 'checked' : ''; ?>> <?=__( 'Factura', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pos_number"><?=__( 'Punto de Venta Nro', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[pos_number_notwholesalers]" id="pos_number_notwholesalers" type="text" class="regular-text" value="<?=kibooGetOption('pos_number_notwholesalers'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_channel"><?=__( 'Servicio de Canal', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[service_channel_notwholesalers]" id="service_channel_notwholesalers" type="text" class="regular-text" value="<?=kibooGetOption('service_channel_notwholesalers'); ?>">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Atributos de productos', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="woo_brand_attribute"><?=__( 'Atributo Marca', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[woo_brand_attribute]" id="woo_brand_attribute">
                        <?php
                        $attr_tax = wc_get_attribute_taxonomies();
                        foreach($attr_tax as $tax){
                            if(kibooGetOption('woo_brand_attribute') == $tax->attribute_name){
                                echo '<option value="'.$tax->attribute_name.'" selected>'.$tax->attribute_label.'</option>';
                            }else{
                                echo '<option value="'.$tax->attribute_name.'">'.$tax->attribute_label.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="woo_model_attribute"><?=__( 'Atributo Modelo ', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[woo_model_attribute]" id="woo_model_attribute">
                        <?php
                        $attr_tax = wc_get_attribute_taxonomies();
                        foreach($attr_tax as $tax){
                            if(kibooGetOption('woo_model_attribute') == $tax->attribute_name){
                                echo '<option value="'.$tax->attribute_name.'" selected>'.$tax->attribute_label.'</option>';
                            }else{
                                echo '<option value="'.$tax->attribute_name.'">'.$tax->attribute_label.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="woo_size_attribute"><?=__( 'Atributo Tamaño ', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[woo_size_attribute]" id="woo_size_attribute">
                        <?php
                        $attr_tax = wc_get_attribute_taxonomies();
                        foreach($attr_tax as $tax){
                            if(kibooGetOption('woo_size_attribute') == $tax->attribute_name){
                                echo '<option value="'.$tax->attribute_name.'" selected>'.$tax->attribute_label.'</option>';
                            }else{
                                echo '<option value="'.$tax->attribute_name.'">'.$tax->attribute_label.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="woo_color_attribute"><?=__( 'Atributo Color', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[woo_color_attribute]" id="woo_color_attribute">
                        <?php
                        $attr_tax = wc_get_attribute_taxonomies();
                        foreach($attr_tax as $tax){
                            if(kibooGetOption('woo_color_attribute') == $tax->attribute_name){
                                echo '<option value="'.$tax->attribute_name.'" selected>'.$tax->attribute_label.'</option>';
                            }else{
                                echo '<option value="'.$tax->attribute_name.'">'.$tax->attribute_label.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>



    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Stock', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="manage_stock"><?=__( 'Administrar Stock', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[manage_stock]" id="manage_stock" type="radio" value="1" <?=(kibooGetOption('manage_stock') == 1) ? 'checked' : ''; ?>> <?=__( 'Si', 'kiboo' ); ?>&nbsp;
                        <input name="settings[manage_stock]" id="manage_stock" type="radio" value="0" <?=(kibooGetOption('manage_stock') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr style="display:none;">
                    <th scope="row" ><label for="backorders"><?=__( 'Allow Backorder', 'kiboo' ); ?></label></th>
                    <td>
                        <select name="settings[backorders]" id="backorders">
                        <?php
                        $backorder_options = array(
                            'no'        => __( 'Disallow', 'kiboo' ),
                            'notify'    => __( 'Allow but Notify', 'kiboo' ),
                            'Si'       => __( 'Allow', 'kiboo' ),
                        );

                        foreach($backorder_options as $bo_id => $bo_text){
                            if(kibooGetOption('backorders') == $bo_id){
                                echo '<option value="'.$bo_id.'" selected>'.$bo_text.'</option>';
                            }else{
                                echo '<option value="'.$bo_id.'">'.$bo_text.'</option>';
                            }
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p class="submit">
        <input name="save" id="save" type="submit" class="button button-primary" value="<?=__( 'Save', 'kiboo' ); ?>">
        <br class="clear">
    </p>
</form>