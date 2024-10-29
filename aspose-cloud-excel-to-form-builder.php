<?php
/*
Plugin Name: Aspose.Cells Forms
Plugin URI:
Description: Aspose.Cells Forms Plugin for WordPress allows site administrators/ownwers to create interactive forms using Microsoft Excel files
Version: 2.0
Author: aspose.cloud Marketplace
Author URI: https://www.aspose.cloud/

*/

require __DIR__.'/vendor/autoload.php';

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

function register_aspose_excel_to_form_menu_page() {
    add_menu_page( 'Aspose.Cells Forms', 'Aspose.Cells Forms', 'edit_published_posts', 'aspose-cloud-excel-to-form-builder/excel-to-form-admin.php', 'aspose_cloud_excel_to_form_builder_admin_page', 'dashicons-admin-page', 30 );
    add_options_page('Aspose.Cells Forms Configurations Page', __('Aspose.Cells Forms', 'aspose-cloud-excel-to-form-builder'), 'activate_plugins', 'apc-excel-to-form-settings', 'AsposeExcelToFormAdminContent');
}

function aspose_cloud_excel_to_form_builder_admin_page() {

    $ape_sid = get_option('aspose-cloud-app-sid');
    $ape_key = get_option('aspose-cloud-app-key');

    if(empty($ape_sid) || empty($ape_key)) {
        echo '<div><h2 style="color: red">Please enter Aspose SID and Key on plugin settings page.</h2></div>';
     //   return;
    }

    if(isset($_POST['apc_generate_short_code'])) {

        $post_params = $_POST;

        require_once('apc-sdk-calls.php');

    }

    require_once('aspose-cloud-excel-to-form-admin-main.php');
}

add_action( 'admin_menu', 'register_aspose_excel_to_form_menu_page' );


// Defineing the Activator URL
if (!defined("ASPOSE_CLOUD_MARKETPLACE_ACTIVATOR_URL")) {
	define("ASPOSE_CLOUD_MARKETPLACE_ACTIVATOR_URL","https://activator.marketplace.aspose.cloud/activate");
}
// Setting up Secret key	
if(!get_option("aspose-cloud-activation-secret")){
	update_option("aspose-cloud-activation-secret", bin2hex(random_bytes(64)));						
}
function apc_excel_to_form_builder_enqueue_scripts() {

    // using thickbox for media uploader popup
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

    // register plugin script file

  //  wp_register_script( 'apc_excel_to_form_builder_script', plugins_url( 'js/script.js', __FILE__ ), array('jquery') );

  //  wp_enqueue_script( 'apc_excel_to_form_builder_script' );

}

add_action('admin_init', 'apc_excel_to_form_builder_enqueue_scripts');



/**
 * Pluing settings page
 * @param no-param
 * @return jwt based token
 */	
    function getToken_aspsoe_cells_forms() {
        if (!array_key_exists("token", $_REQUEST) || !get_option("aspose-cloud-activation-secret")) {
            return null;
        }
        try {
            //print_r($_REQUEST["token"]);
            $token = (new Parser())->parse($_REQUEST["token"]);
        } catch (Exception $x) {
            return null;
        }
        if (!($token->hasClaim("iss")) || $token->getClaim("iss") !== "https://activator.marketplace.aspose.cloud/") {
            return null;
        }
        $signer = new Sha256();
        $key = new Key(get_option("aspose-cloud-activation-secret"));
        if (!$token->verify($signer, $key)) {
            update_option("aspose-cloud-activation-secret", null);
            wp_die("Unable to verify token signature.");
        }
        return $token;
    }	

/**
 * Pluing settings page
 * @param no-param
 * @return no-return
 */
function AsposeExcelToFormAdminContent() {

    // Creating the admin configuration interface
    ?>
    <div class="wrap">
    <h2><?php echo __('Aspose.Cells Forms Options', 'aspose-cloud-excel-to-form-builder');?></h2>
    <br class="clear" />

    <div class="metabox-holder has-right-sidebar" id="poststuff">
    <div class="inner-sidebar" id="side-info-column">
        <div class="meta-box-sortables ui-sortable" id="side-sortables">
            <div id="AsposePostsExporterOptions" class="postbox">
                <div title="Click to toggle" class="handlediv"><br /></div>
                <h3 class="hndle"><?php echo __('Support / Manual', 'aspose-cloud-excel-to-form-builder'); ?></h3>
                <div class="inside">
                    <p style="margin:15px 0px;"><?php echo __('For any suggestion / query / issue / requirement, please feel free to drop an email to', 'aspose-cloud-excel-to-form-builder'); ?> <a href="/cdn-cgi/l/email-protection#87eae6f5ece2f3f7ebe6e4e2c7e6f4f7e8f4e2a9e4e8eab8f4f2e5ede2e4f3bac6f4f7e8f4e2a7c3e8e4a7c2fff7e8f5f3e2f5">marketplace@aspose.com</a>.</p>
                    <p style="margin:15px 0px;"><?php echo __('Get the', 'aspose-cloud-excel-to-form-builder'); ?> <a href="#" target="_blank"><?php echo __('Manual here', 'aspose-cloud-excel-to-form-builder'); ?></a>.</p>

                </div>
            </div>

            <div id="AsposePostsExporterOptions" class="postbox">
                <div title="Click to toggle" class="handlediv"><br /></div>
                <h3 class="hndle"><?php echo __('Review', 'aspose-cloud-excel-to-form-builder'); ?></h3>
                <div class="inside">
                    <p style="margin:15px 0px;">
                        <?php echo __('Please feel free to add your reviews on', 'aspose-cloud-excel-to-form-builder'); ?> <a href="http://wordpress.org/support/view/plugin-reviews/aspose-cloud-excel-to-form-builder" target="_blank"><?php echo __('Wordpress', 'aspose-cloud-excel-to-form-builder');?></a>.</p>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div id="post-body">
        <div id="post-body-content">
            <div class="postbox">
                <h3 class="hndle">aspose.cloud Subscription</h3>
                <div class="inside">
                    <p>
                        <?php
                        if (array_key_exists("token", $_REQUEST) ){
                            if (!(getToken_aspsoe_cells_forms()->hasClaim("aspose-cloud-app-sid")) || !(getToken_aspsoe_cells_forms()->hasClaim("aspose-cloud-app-key"))) {
                                wp_die("The token has some invalid data");
                            }
                            update_option("aspose-cloud-app-sid", getToken_aspsoe_cells_forms()->getClaim("aspose-cloud-app-sid"));
                            update_option("aspose-cloud-app-key", getToken_aspsoe_cells_forms()->getClaim("aspose-cloud-app-key"));
                            update_option("aspose-cloud-activation-secret", null);
                    	}
                    	?>                            
                    </p>
                    <?php if (strlen(get_option("aspose-cloud-app-sid")) < 1): ?>
                    <p>
                        <a class="button-primary" href="<?php echo ASPOSE_CLOUD_MARKETPLACE_ACTIVATOR_URL; ?>?callback=<?php echo urlencode(site_url()."/wp-admin/options-general.php?page=apc-excel-to-form-settings"); ?>&secret=<?php echo get_option("aspose-cloud-activation-secret"); ?>">
                            <b>Enable FREE and Unlimited Access</b>
                        </a>
                    </p>
                    <p style="font-size: xx-small">
                        Your website URL
                        <i><?php echo site_url(); ?></i>
                        and admin email
                        <i><?php echo get_bloginfo("admin_email"); ?></i>
                        will be sent to
                        <i>aspose.cloud</i>
                        during the process.
                    </p>
                    <?php else: ?>
                    <h4>
                        <button disabled="disabled">FREE Unlimited Access is enabled</button>                                
                    </h4>
                    <p style="font-size: xx-small">
                        App SID:<?php echo get_option("aspose-cloud-app-sid"); ?><br>
                        You can disable FREE Unlimited Access by deactivating/uninstalling the plugin.
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php
}

add_filter('plugin_action_links', 'AsposePostsExporterPluginLinks', 10, 2);

/**
 * Create the settings link for this plugin
 * @param $links array
 * @param $file string
 * @return $links array
 */
function AsposePostsExporterPluginLinks($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=apc-excel-to-form-settings') . '">' . __('Settings', 'aspose-cloud-excel-to-form-builder') . '</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}


/**
 * For removing options
 * @param no-param
 * @return no-return
 */
function UnsetOptionsAsposePostsExporter() {
    
    // Deleting the older setting options on plugin uninstall
    delete_option('aspose_cloud_excel_to_form_app_sid');
    delete_option('aspose_cloud_excel_to_form_app_key');

    // Deleting the added options on plugin uninstall
    delete_option('aspose-cloud-app-sid');
    delete_option('aspose-cloud-app-key');

}

register_uninstall_hook(__FILE__, 'UnsetOptionsAsposePostsExporter');

function AsposePostsExporterAdminRegisterSettings() {

    global $wpdb;

    $create_new_table = '
        CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'apc_shortcodes` (
        `id` int(11) NOT NULL,
          `filename` text NOT NULL,
          `head_row` text NOT NULL
        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
        ';

    $wpdb->query($create_new_table);
    // Registering the settings

    register_setting('aspose_posts_exporter_options', 'aspose-cloud-app-sid');
    register_setting('aspose_posts_exporter_options', 'aspose-cloud-app-key');


}

add_action('admin_init', 'AsposePostsExporterAdminRegisterSettings');


if (check_upload_aspose_excel_context('APC-Select-Excel-File')) {


    add_filter('media_upload_tabs', 'apc_excel_to_form_builder_uploader_tabs', 10, 1);
    add_filter('attachment_fields_to_edit', 'apc_excel_to_form_builder_uploader_action_button', 20, 2);
    add_filter('media_send_to_editor', 'apc_excel_to_form_builder_uploader_file_selected', 10, 3);
//    add_filter('upload_mimes', 'apc_excel_to_form_builder_uploader_upload_mimes', 10, 3);

}

function apc_excel_to_form_builder_uploader_tabs($_default_tabs) {

    unset($_default_tabs['type_url']);
    return($_default_tabs);
}

function apc_excel_to_form_builder_uploader_upload_mimes ( $existing_mimes=array() ) {


    $existing_mimes = array();
    $existing_mimes['doc'] = 'application/msword';
    $existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

    return $existing_mimes;
}

function apc_excel_to_form_builder_uploader_action_button($form_fields, $post) {

    $send = "<input type='submit' class='button-primary' name='send[$post->ID]' value='" . esc_attr__( 'Use this File For Form Builder' ) . "' />";

    $form_fields['buttons'] = array('tr' => "\t\t<tr class='submit'><td></td><td class='savesend'>$send</td></tr>\n");
    $form_fields['context'] = array( 'input' => 'hidden', 'value' => 'APC-Select-Excel-File' );
    return $form_fields;
}


function apc_excel_to_form_builder_uploader_file_selected($html, $send_id) {

    $file_url = wp_get_attachment_url($send_id);
    $file_url = basename($file_url);
    ?>
    <script type="text/javascript">
        /* <![CDATA[ */
        var win = window.dialogArguments || opener || parent || top;

        win.jQuery( '#excel_file_name' ).val('<?php echo $file_url;?>');

        win.jQuery('.tb-close-icon').trigger('click');

    </script>
    <?php
    return '';
}

function add_aspose_excel_context_to_url($url, $type) {
    //if ($type != 'image') return $url;
    if (isset($_REQUEST['context'])) {
        $url = add_query_arg('context', $_REQUEST['context'], $url);
    }
    return $url;
}


function check_upload_aspose_excel_context($context) {
    if (isset($_REQUEST['context']) && $_REQUEST['context'] == $context) {
        add_filter('media_upload_form_url', 'add_aspose_excel_context_to_url', 10, 2);
        return TRUE;
    }
    return FALSE;
}

require_once('apc-shortcodes.php');
