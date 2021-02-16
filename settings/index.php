<?php
/**
 * Create Settings Menu & Page
 * Page is divided in sections
 */
add_action('admin_menu', 'kibooSettingsMenu');

function kibooSettingsMenu(){
        add_menu_page( 'Kiboo Woo', 'Kiboo Woo', 'manage_options', 'kiboo-settings', 'kibooSettingsPage', 'dashicons-admin-generic', 2 );
}

function kibooSettingsPage(){
    require('styles.php');
    echo '<div class="wrap kiboo-container">';
    echo '<img src="'.plugin_dir_url( __FILE__ ).'../img/kiboo.png" width="200px">';
    echo '<h1>'.__( 'Kiboo WooCommerce Settings', 'kiboo' ).'</h1>';

    /**
     * Save Setting if form is submitted
     */
    if(isset($_POST['save'])){
        kibooSaveSettings($_POST['settings']);
    }

    /**
     * List of Sections
     * First is the default one
     */
    /*
    $sections = array(
        array(
            'id'        => 'home',
            'label'     => __( 'Home', 'kiboo' )
        ),
        array(
            'id'        => 'general',
            'label'     => __( 'General', 'kiboo' )
        ),
        array(
            'id'        => 'integration',
            'label'     => __( 'Integration', 'kiboo' )
        ),
        array(
            'id'        => 'support',
            'label'     => __( 'Support', 'kiboo' )
        ),
        array(
            'id'        => 'tools',
            'label'     => __( 'Tools', 'kiboo' )
        )
    );*/

    $sections = array(
        array(
            'id'        => 'integration',
            'label'     => __( 'Configuración', 'kiboo' )
        ),
        array(
            'id'        => 'orphans',
            'label'     => __( 'Huérfanos', 'kiboo' )
        ),
        array(
            'id'        => 'export',
            'label'     => __( 'Exportar info', 'kiboo' )
        ),
        array(
            'id'        => 'log',
            'label'     => __( 'Log', 'kiboo' )
        ),
    );

    /**
     * Get Active Section
     */
    $activeSection = $sections[0];

    if(isset($_GET['view'])){
        foreach($sections as $section){
            if($_GET['view'] == $section['id']){
                $activeSection = $section;
            }
        }
    }



    /**
     * Sections Menu
     */
    $sectionsLinks = array();
    foreach($sections as $section){
        if($activeSection['id'] == $section['id']){
            $sectionsLinks[] = '<b>'.$section['label'].'</b>';
        }else{
            $sectionsLinks[] = '<a class="row-title" href="'.admin_url( 'admin.php?page=kiboo-settings&view='.$section['id'] ).'">'.$section['label'].'</a>';
        }
    }

    echo '<div class="tablenav"><div class="alignleft actions">';
    echo implode('&nbsp;|&nbsp;', $sectionsLinks);
    echo '</div></div>';

    /**
     * Load Section
     */

    echo '<h2>'.$activeSection['label'].'</h2>';
    $fileName = dirname(__FILE__).'/'.$activeSection['id'].'.php';
    //echo $fileName;
    require($fileName);



    echo "</div>";

}

/**
 * Settings are saved in an arrray in wordpress' options
 */
function kibooUpdateOption($key, $value){
    $options = kibooGetOption();
    $options[$key] = $value;
    $serialized = serialize($options);
    update_option('kiboo_options', $serialized);

}

function kibooGetOption($key = null){
    $serialized = get_option('kiboo_options');
    $options = unserialize($serialized);
    if($key != null){
        return $options[$key];
    }else{
        return $options;
    }
}

function kibooSaveSettings($data){
    foreach($data as $key => $value){
        kibooUpdateOption($key, $value);
    }
    kibooMessageAlert(__( 'Kiboo Settings Saved!', 'kiboo' ));
}


/**
 * Prints a message alert
 */
function kibooMessageAlert($text){
    echo '<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
    <p><strong>'.$text.'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
}
?>