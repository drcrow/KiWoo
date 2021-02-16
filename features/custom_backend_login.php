<?php
/**
 * Alter WordPress login page
 */

function kibooLoginPageLogo() {
?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?=plugin_dir_url( __FILE__ ); ?>/../../img/icon-clarika.svg);
            margin: 0 auto;
            filter: grayscale(100%);
        }
    </style>
<?php
}

function kibooLoginPageLink($url) {
    return 'https://clarikagroup.com/';
}


if(kibooGetOption('custom_login') == 1){
    add_action( 'login_enqueue_scripts', 'kibooLoginPageLogo' );
    add_filter( 'login_headerurl', 'kibooLoginPageLink' );
}
?>