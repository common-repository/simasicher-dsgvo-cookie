<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $ssdc_defaults = array (
        array('enabled', '0'),
        array('lengthnum', '1'),
        array('length', 'months'),
        array('position', 'bottomright'),
        array('barmessage', __('By continuing to use the site, you agree to the use of cookies.', 'simasicher-dsgvo-cookie')),
        array('barlink', __('More information', 'simasicher-dsgvo-cookie')),
        array('barbutton', __('Accept', 'simasicher-dsgvo-cookie')),
        array('closelink', __('Close', 'simasicher-dsgvo-cookie')),
        array('boxcontent', __('The cookie settings on this website are set to "allow cookies" to give you the best browsing experience possible.  If you continue to use this website without changing your cookie settings or you click "Accept" below then you are consenting to this.', 'simasicher-dsgvo-cookie')),
        array('bhtmlcontent', __('<b>Content not available.</b><br><small>Please allow cookies by clicking Accept on the banner</small>', 'simasicher-dsgvo-cookie')),
        array('backgroundcolor', '#000000'),
        array('fontcolor', '#FFFFFF'),
        array('autoblock', '0'),
        array('boxlinkblank', '0'),
        array('tinymcebutton', '0'),
        array('scrollconsent', '0'),
        array('navigationconsent', '0'),
        array('networkshare', '0'),
        array('onlyeuropean', '0'),
        array('customurl', get_site_url() ),
        array('cc-disablecookie', __('Revoke cookie consent', 'simasicher-dsgvo-cookie')),
        array('cc-cookieenabled', __('Cookies are enabled', 'simasicher-dsgvo-cookie')),
        array('cc-cookiedisabled', __('Cookies are disabled<br>Accept Cookies by clicking "%s" in the banner.', 'simasicher-dsgvo-cookie')),
		array('systemcatcb', '0'),
		array('systemcatinfo', ' '),
		array('prefcatcb', '0'),
		array('prefcatinfo', ' '),
		array('statcatcb', '0'),
		array('statcatinfo', ' '),
		array('markcatcb', '0'),
        array('markcatinfo', ' '),
        array('license', ''),
        array('networkshareurl', ssdc_getshareurl())
    );

    $ssdc_options = get_option('sima_eucookie');
    $conta = count($ssdc_defaults);
    if (true) { //count($ssdc_options) > 1
        for($i=0;$i<$conta;$i++){
            if (!$ssdc_options[$ssdc_defaults[$i][0]]) {
                $ssdc_options[$ssdc_defaults[$i][0]] = $ssdc_defaults[$i][1];
                update_option('sima_eucookie', $ssdc_options);            
            }
        }
    }

    function ssdc_getshareurl() {
        if ( is_multisite() ) {
            $sURL = network_site_url();
        } else {
            $sURL = site_url();
        }
        $sURL    = site_url(); // WordPress function
        $asParts = parse_url( $sURL ); // PHP function

        if ( ! $asParts )
          wp_die( 'ERROR: Path corrupt for parsing.' ); // replace this with a better error result

        $sScheme = $asParts['scheme'];
        if (isset($asParts['port'])) {
            $nPort   = $asParts['port'];
        }
        $sHost   = $asParts['host'];
        $nPort   = 80 == $nPort ? '' : $nPort;
        $nPort   = 'https' == $sScheme AND 443 == $nPort ? '' : $nPort;
        $sPort   = ! empty( $sPort ) ? ":$nPort" : '';
        $sReturn = $sHost . $sPort;

        return $sReturn;
    }
?>