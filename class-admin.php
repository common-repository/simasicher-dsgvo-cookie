<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action('admin_init', 'ssdc_init' );
function ssdc_init(){
    register_setting( 'sima_eucookie_options', 'sima_eucookie' );
    wp_register_style	('basecss', plugins_url('css/style_admin.css', __FILE__), false);
	wp_enqueue_style	('basecss');
}

add_action( 'admin_enqueue_scripts', 'ssdc_enqueue_color_picker' );
function ssdc_enqueue_color_picker( $hook_suffix ) {
    $screen = get_current_screen();
    if ($screen->id == 'toplevel_page_simaCookie_admin_page') {  
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'elc-color-picker', plugins_url('js/eucookiesettings.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}

// ADMIN PAGE
function ssdc_options_page() {
?>
    <div class="wrap sima_container">

		<h1>
            <?php esc_html_e('SimaCookie (GDPR)', 'simasicher-dsgvo-cookie'); ?>
        </h1>

        <?php
        // Save settings
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            echo '<div class="updated"><p><strong>';
            echo __('Settings Updated.','simasicher-dsgvo-cookie');
            echo '</strong></p></div>';
        } 
        // Delete settings
        if (isset($_POST['delete_all_settings']) && $_POST['delete_all_settings']) {
            delete_option('sima_eucookie');
            echo '<div class="updated settings_deleted"><p><strong>';
            echo __('Settings Deleted.','simasicher-dsgvo-cookie');
            echo '</strong></p></div>';
        }
        ?>
        
        <div class="sima_column sima_column_left">
            <form method="post" action="options.php">
                <?php settings_fields('sima_eucookie_options'); ?>
                <?php
                    ssdc_check_defaults();
                    $options = get_option('sima_eucookie');
                ?>
                <p class="submit submit_top">
                <input type="submit" name="save-settings" class="button-primary" value="<?php esc_attr_e('Save Changes', 'simasicher-dsgvo-cookie') ?>" />
                </p>
                <table class="form-table">
                    <tr valign="top"><th scope="row"><label for="enabled"><?php esc_html_e('Activate'); ?></label></th>
                        <td><input id="enabled" name="sima_eucookie[enabled]" type="checkbox" value="1" <?php checked('1', $options['enabled']); ?> /></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="autoblock"><?php esc_html_e('Auto Block', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="autoblock" name="sima_eucookie[autoblock]" type="checkbox" value="1" <?php checked('1', $options['autoblock']); ?> /><br>
    <small><?php esc_html_e('This function will automatically block iframes, embeds and scripts in your post, pages and widgets.', 'simasicher-dsgvo-cookie'); ?></small></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="tinymcebutton"><?php esc_html_e('Enable TinyMCE Button', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="tinymcebutton" name="sima_eucookie[tinymcebutton]" type="checkbox" value="1" <?php checked('1', $options['tinymcebutton']); ?> /><br>
    <small><?php esc_html_e('Click here if you want to turn on the TinyMCE button for manual insertion of SimaCookie shortcodes while editing contents.', 'simasicher-dsgvo-cookie'); ?></small></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="lengthnum">
                        <?php esc_html_e('Cookie acceptance length', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="lengthnum" type="text" class="sima_input_small" name="sima_eucookie[lengthnum]" value="<?php echo absint( $options['lengthnum'] ); ?>" size="5" />
                            <select name="sima_eucookie[length]">
                                <option value="days"<?php if ($options['length'] == 'days') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('days', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="weeks"<?php if ($options['length'] == 'weeks') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('weeks', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="months"<?php if ($options['length'] == 'months') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('months', 'simasicher-dsgvo-cookie'); ?></option>
                            </select><br>
    <small><?php esc_html_e('Once the user clicks accept the bar will disappear. You can set how long this will apply for before the bar reappears to the user.', 'simasicher-dsgvo-cookie'); ?> <?php esc_html_e('Set "0" for SESSION cookie.', 'simasicher-dsgvo-cookie'); ?></small>
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="scrollconsent"><?php esc_html_e('Scroll Consent', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="scrollconsent" name="sima_eucookie[scrollconsent]" type="checkbox" value="1" <?php checked('1', $options['scrollconsent']); ?> /><br>
    <small><?php esc_html_e('Click here if you want to consider scrolling as cookie acceptation. Users should be informed about this...', 'simasicher-dsgvo-cookie'); ?></small></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="navigationconsent"><?php esc_html_e('Navigation Consent', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="navigationconsent" name="sima_eucookie[navigationconsent]" type="checkbox" value="1" <?php checked('1', $options['navigationconsent']); ?> /><br>
    <small><?php esc_html_e('Click here if you want to consider continuing navigation as cookie acceptation. Users should be informed about this...', 'simasicher-dsgvo-cookie'); ?></small></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="networkshare"><?php esc_html_e('Share Cookie across Network', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="networkshare" name="sima_eucookie[networkshare]" type="checkbox" value="1" <?php checked('1', $options['networkshare']); ?> /><br>
    <small><?php esc_html_e('Click here if you want to share SimaCookie across your network (subdomains or multisite)', 'simasicher-dsgvo-cookie'); ?></small></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="networkshareurl">
                        <?php esc_html_e('Network Domain', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="networkshareurl" type="text" name="sima_eucookie[networkshareurl]" value="<?php echo esc_attr( $options['networkshareurl'] ); ?>" size="40" /></td>
                    </tr>
                </table>
            <hr>
                <h3 class="title"><?php esc_html_e('Appearance'); ?></h3>
                <table class="form-table">
                    <tr valign="top"><th scope="row"><label for="position"><?php esc_html_e('Position', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td>
                            <select name="sima_eucookie[position]">
                                <option value="topleft"<?php if ($options['position'] == 'topleft') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Top Left', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="topcenter"<?php if ($options['position'] == 'topcenter') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Top Center', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="topright"<?php if ($options['position'] == 'topright') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Top Right', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="bottomleft"<?php if ($options['position'] == 'bottomleft') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Bottom Left', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="bottomcenter"<?php if ($options['position'] == 'bottomcenter') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Bottom Center', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="bottomright"<?php if ($options['position'] == 'bottomright') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Bottom Right', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="overlay"<?php if ($options['position'] == 'overlay') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Overlay', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="bar_top"<?php if ($options['position'] == 'bar_top') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Bar Top', 'simasicher-dsgvo-cookie'); ?></option>
                                <option value="bar_bottom"<?php if ($options['position'] == 'bar_bottom') { echo ' selected="selected"'; } ?>>
                                    <?php esc_html_e('Bar Bottom', 'simasicher-dsgvo-cookie'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="backgroundcolor">
                        <?php esc_html_e('Background Color', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="backgroundcolor" type="text" name="sima_eucookie[backgroundcolor]" value="<?php echo $options['backgroundcolor']; ?>" class="color-field" data-default-color="#000000"/></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="fontcolor">
                        <?php esc_html_e('Font Color', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="fontcolor" type="text" name="sima_eucookie[fontcolor]" value="<?php echo $options['fontcolor']; ?>"  class="color-field" data-default-color="#ffffff"/></td>
                    </tr>
                </table>
            <hr>
                <h3 class="title"><?php esc_html_e('Content'); ?></h3>
                <table class="form-table">
                    <tr valign="top"><th scope="row"><label for="barmessage">
                        <?php esc_html_e('Bar Message', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><textarea name='sima_eucookie[barmessage]' id='barmessage' rows='5' ><?php echo esc_textarea( $options['barmessage'] ); ?></textarea></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="barlink">
                        <?php esc_html_e('More Info Text', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="barlink" type="text" name="sima_eucookie[barlink]" value="<?php echo esc_attr( $options['barlink'] ); ?>" /></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="barbutton">
                        <?php esc_html_e('Accept Text', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="barbutton" type="text" name="sima_eucookie[barbutton]" value="<?php echo esc_attr( $options['barbutton'] ); ?>" /></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="boxlinkid">
                        <?php esc_html_e('Bar Link', 'simasicher-dsgvo-cookie'); ?><br/><small>
                        <?php esc_html_e('Use this field if you want to link a page instead of showing the popup', 'simasicher-dsgvo-cookie'); ?></small></label></th>
                        <td>
                        <?php
                        
                        if(isset($options['boxlinkid']) ) {
                            $boxlinkId = $options['boxlinkid'];
                        } else {
                            $boxlinkId = "";
                        }
                        $args = array(
                            'depth'                 => 0,
                            'child_of'              => 0,
                            'selected'              => $boxlinkId,
                            'echo'                  => 0,
                            'name'                  => 'sima_eucookie[boxlinkid]',
                            'id'                    => 'boxlinkid',
                            'show_option_none'      => __('Custom URL'),
                            'show_option_no_change' => null,
                            'option_none_value'     => 'C',
                        ); ?>

                        <?php
                        $lol = wp_dropdown_pages($args);
                        $add = null;
                        if ( $boxlinkId == 'C' ) { $add = ' selected="selected" '; }

                        $end = '</select>';
                        $lol = preg_replace('#</select>$#', $end, trim($lol)); 
                        echo $lol; ?>
                            <br><br><input id="boxlinkblank" name="sima_eucookie[boxlinkblank]" type="checkbox" value="1" <?php checked('1', $options['boxlinkblank']); ?> /><label for="boxlinkblank"><small> <?php esc_html_e('Open link in a new tab (adds target="_blank")', 'simasicher-dsgvo-cookie'); ?> </small></label>
                        </td>
                        
                    </tr>
                    <tr valign="top"><th scope="row"><label for="customurl">
                        <?php esc_html_e('Custom URL'); ?></label></th>
                        <td><input id="customurl" type="text" name="sima_eucookie[customurl]" value="<?php echo esc_attr( $options['customurl'] ); ?>" />
                            <small> <?php esc_html_e('Enter the destination URL', 'simasicher-dsgvo-cookie'); ?></small></td>
                    </tr>
                    <!-- <tr valign="top"><th scope="row"><label for="closelink">
                        <?php esc_html_e('"Close Popup" Text', 'simasicher-dsgvo-cookie'); ?></label></th>
                        <td><input id="closelink" type="text" name="sima_eucookie[closelink]" value="<?php echo esc_attr( $options['closelink'] ); ?>" /></td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="boxcontent">
                        <?php esc_html_e('Popup Box Content', 'simasicher-dsgvo-cookie'); ?><br>
                        <small><?php esc_html_e('Use this to add a popup that informs your users about your cookie policy', 'simasicher-dsgvo-cookie'); ?></small></label></th>
                        <td>
    <textarea style='font-size: 90%;' name='sima_eucookie[boxcontent]' id='boxcontent' rows='9' ><?php echo esc_textarea( $options['boxcontent'] ); ?></textarea>
                        </td>
                    </tr> -->
                    <tr valign="top"><th scope="row"><label for="bhtmlcontent">
                        <?php esc_html_e('Blocked code message', 'simasicher-dsgvo-cookie'); ?><br>
                        <small><?php esc_html_e('This is the message that will be displayed for locked-code areas', 'simasicher-dsgvo-cookie'); ?></small></label></th>
                        <td>
    <textarea style='font-size: 90%;' name='sima_eucookie[bhtmlcontent]' id='bhtmlcontent' rows='9' ><?php echo esc_textarea( $options['bhtmlcontent'] ); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="categories">
                        <?php esc_html_e('Show categories', 'simasicher-dsgvo-cookie'); ?><br/><small>
                        <?php esc_html_e('Inform your users about different kinds of used cookies. Fill in the purposes of the cookies. Separate more purposes with commas.', 'simasicher-dsgvo-cookie'); ?></small></label></th>
                        <td style='padding-top: 20px;'>
                    
                            <input id="systemcatcb" name="sima_eucookie[systemcatcb]" type="checkbox" value="1" <?php checked('1', $options['systemcatcb']); ?> />
                            <label for="systemcatcb"><small><?php esc_html_e('System'); ?></small></label> 
                            <textarea class='sima_texarea_small' name='sima_eucookie[systemcatinfo]' id='systemcatinfo' rows='3' ><?php echo esc_textarea( $options['systemcatinfo'] );?></textarea>
                            <small> <?php esc_html_e(' displayed note', 'simasicher-dsgvo-cookie'); ?></small>

                            <br><br>
                            
                            <input id="prefcatcb" name="sima_eucookie[prefcatcb]" type="checkbox" value="1" <?php checked('1', $options['prefcatcb']); ?> />
                            <label for="prefcatcb"><small><?php esc_html_e('Preferences'); ?></small></label>
                            <textarea class='sima_texarea_small' name='sima_eucookie[prefcatinfo]' id='prefcatinfo' rows='3' ><?php echo esc_textarea( $options['prefcatinfo'] );?></textarea>
                            <small> <?php esc_html_e(' displayed note', 'simasicher-dsgvo-cookie'); ?></small>

                            <br><br>
                            
                            <input id="statcatcb" name="sima_eucookie[statcatcb]" type="checkbox" value="1" <?php checked('1', $options['statcatcb']); ?> />
                            <label for="statcatcb"><small><?php esc_html_e('Statistics'); ?></small></label>
                            <textarea class='sima_texarea_small' name='sima_eucookie[statcatinfo]' id='statcatinfo' rows='3' ><?php echo esc_textarea( $options['statcatinfo'] );?></textarea>
                            <small> <?php esc_html_e(' displayed note', 'simasicher-dsgvo-cookie'); ?></small>

                            <br><br>

                            <input id="markcatcb" name="sima_eucookie[markcatcb]" type="checkbox" value="1" <?php checked('1', $options['markcatcb']); ?> />
                            <label for="markcatcb"><small><?php esc_html_e('Marketing'); ?></small></label>
                            <textarea class='sima_texarea_small' name='sima_eucookie[markcatinfo]' id='markcatinfo' rows='3' ><?php echo esc_textarea( $options['markcatinfo'] );?></textarea>
                            <small> <?php esc_html_e(' displayed note', 'simasicher-dsgvo-cookie'); ?></small>

                        </td>
                        
                    </tr>
                    <tr>
                </table>
                    <hr>
                    <h3 class="title">Shortcode [cookie-control]</h3>
                <table class="form-table">
                    </tr>
                        <tr valign="top"><th scope="row"><label for="cc-cookieenabled">
                        <?php esc_html_e('Cookie enabled message', 'simasicher-dsgvo-cookie'); ?><br>
                        <small><?php esc_html_e('This is the message that will be displayed when cookie are enabled', 'simasicher-dsgvo-cookie'); ?></small></label></th>
                        <td>
    <textarea style='font-size: 90%;' name='sima_eucookie[cc-cookieenabled]' id='cc-cookieenabled' rows='9' ><?php echo esc_textarea( $options['cc-cookieenabled'] ); ?></textarea><br>
                        
                        <label style="font-size:0.9em;font-weight:bold;" for="cc-disablecookie"><?php esc_html_e('"Disable Cookie" Text', 'simasicher-dsgvo-cookie'); ?></label>
                        <input id="cc-disablecookie" type="text" name="sima_eucookie[cc-disablecookie]" value="<?php echo $options['cc-disablecookie']; ?>" />
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row"><label for="cc-cookiedisabled">
                        <?php esc_html_e('Cookie disabled message', 'simasicher-dsgvo-cookie'); ?><br>
                        <small><?php esc_html_e('This is the message that will be displayed when cookie are not accepted', 'simasicher-dsgvo-cookie'); ?></small></label></th>
                        <td>
    <textarea style='font-size: 90%;' name='sima_eucookie[cc-cookiedisabled]' id='cc-cookiedisabled' rows='9' ><?php echo esc_textarea( $options['cc-cookiedisabled'] ); ?></textarea>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                
                <input type="submit" name="save-settings" class="button-primary" value="<?php esc_attr_e('Save Changes', 'simasicher-dsgvo-cookie') ?>" />
                </p>
            </form>

            <form method="post" action="">
            <p class="submit delete_settings">
                <input type="submit" name="delete_all_settings" class="button-secondary" value="<?php esc_attr_e('Delete settings and reset', 'simasicher-dsgvo-cookie') ?>" onclick="return confirmDeleteSettings();" />
                <span class="delete_settings_span"><em>
                    <?php esc_html_e('Warning: this will actually delete', 'simasicher-dsgvo-cookie'); ?>
                    <br>
                    <?php esc_html_e(' your current settings.', 'simasicher-dsgvo-cookie'); ?></em></span>
            </p>
            </form>

            <script type="text/javascript">
            function confirmDeleteSettings() {
                return confirm('Are you sure you want to delete all your settings?');
            }
            </script>
            
        </div>

        <div class="sima_column sima_column_right">
            
            <div class="sima_logo">
                <a class="sima_logo_a" href="https://www.simasicher.com/" target="_blank" title="simasicher.com">
                    <img class="sima_logo_img" src="<?php echo plugins_url('img/simasicher_logo_name.png',__FILE__); ?>" />
                </a>
            </div>

            <br>

            <h3><?php esc_html_e('Rate us', 'simasicher-dsgvo-cookie'); ?></h3>

            <p><?php esc_html_e('If you like our plugin please show your support with a review on ', 'simasicher-dsgvo-cookie'); ?><a href="https://wordpress.org/support/plugin/simasicher-dsgvo-cookie/reviews/?filter=5" target="_blank">WordPress.org</a><?php esc_html_e('. Thanks in advance!', 'simasicher-dsgvo-cookie'); ?></p>

            <br>

            <h3><?php esc_html_e('Help and Support', 'simasicher-dsgvo-cookie'); ?></h3>
            <ul>
                <li><a href="https://wordpress.org/support/plugin/simasicher-dsgvo-cookie" target="_blank"><?php esc_html_e('Forum', 'simasicher-dsgvo-cookie'); ?></a></li>
                <li><a href="mailto:support@simasicher.com?subject=Support Request for SimaCookie"><?php esc_html_e('Contact our Support', 'simasicher-dsgvo-cookie'); ?></a></li>
                <li><a href="mailto:feedback@simasicher.com?subject=Feature Suggestion for SimaCookie"><?php esc_html_e('Suggest a Feature', 'simasicher-dsgvo-cookie'); ?></a></li>
                <li><a href="https://www.simasicher.com/simasicher-cookie-plugin/" target="_blank"><?php esc_html_e('About', 'simasicher-dsgvo-cookie'); ?></a></li>
            </ul>
            
            
        </div>


		
	</div>
<?php
}

function ssdc_list_charts_page()
{
    
    add_menu_page(
        'SimaCookie',
        'SimaCookie',
        'manage_options',
        'simaCookie_admin_page',
        'ssdc_options_page',
        'dashicons-shield-alt',
        20
    );
}
add_action('admin_menu', 'ssdc_list_charts_page');
?>