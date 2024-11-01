<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $euCookieSet = 0;

add_action( 'send_headers', function() {
	if ( isset($_GET['nocookie']) ) {
        setcookie('simaCookie', '', 1, '/');
        global $euCookieSet;
        $euCookieSet = 0;
        wp_redirect( esc_url( remove_query_arg( 'nocookie' ) ) );
        exit();
    }
});

add_action('wp_head', function() {
    
    global $euCookieSet;
    global $deleteCookieUrlCheck;
    
    if ( !isset($_GET['nocookie']) && wp_get_referer() && ssdc_option('navigationconsent') && (!ssdc_cookie_accepted()) && (ssdc_option('boxlinkid') != get_the_ID()) ) {
        $euCookieSet = 1;
    }
    
    
    if ( ssdc_isSearchEngine() ) {
        $euCookieSet = 1;
    }
    
	wp_register_style	('basecss', plugins_url('css/style.css', __FILE__), false);
	wp_enqueue_style	('basecss');
    
    $ssdc_eclData = array(
        'euCookieSet' => $euCookieSet,
        'autoBlock' =>  ssdc_option('autoblock'),
        'expireTimer' => ssdc_get_expire_timer(),
        'scrollConsent' => ssdc_option('scrollconsent'),
        'networkShareURL' => ssdc_get_cookie_domain(),
        'isCookiePage' => ssdc_option('boxlinkid') == get_the_ID(),
        'isRefererWebsite' => ssdc_option('navigationconsent') && wp_get_referer(),
        'deleteCookieUrl' => esc_url( add_query_arg( 'nocookie', '1', get_permalink() ) )
    );
    
    wp_enqueue_script(
        'eucookielaw-scripts',
        plugins_url('js/scripts.js', __FILE__),
        array( 'jquery' ),
        '',
        true
    );
    wp_localize_script('eucookielaw-scripts','eucookielaw_data',$ssdc_eclData);
    
});

function ssdc_isSearchEngine(){
    $engines  = array(
        'google',
		'googlebot',
        'yahoo',
        'facebook',
        'twitter',
		'slurp',
		'search.msn.com',
		'nutch',
		'simpy',
		'bot',
		'aspseek',
		'crawler',
		'msnbot',
		'libwww-perl',
		'fast',
		'baidu',
	);
                
	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
        return false;
    }
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    foreach ( $engines as $engine ) {
        if (stripos($ua, $engine) !== false) {
            return true;
		}
		return false;
	}
}

function ssdc_get_cookie_domain() {
    
    if ( ssdc_option('networkshare') ) {
        return 'domain='.ssdc_option('networkshareurl').'; ';
    }
    return '';
}

function ssdc_cookie_accepted() {
    global $euCookieSet;
    
    if ( ! ssdc_option('enabled') ) { return true; }
    
    if ( ( isset( $_COOKIE['simaCookie'] ) && !isset( $_GET['nocookie'] ) ) || $euCookieSet ) {
        return true;
    } else {
        return false;
    }
}

function ssdc_get_expire_timer() {
    
    switch( ssdc_option('length') ){
        case "hours":
            $multi = 1;
            break;
        case "days":
            $multi = 1;
            break;
        case "weeks":
            $multi = 7;
            break;
        case "months":
            $multi = 30;
            break;
    }
    return $multi *  ssdc_option('lengthnum');
}
    
add_action('wp_footer', function() {
    
    if ( !ssdc_cookie_accepted() ) { 
    
        $target = '';
        if ( ssdc_option('boxlinkid') == 'C') {
            $link =  ssdc_option('customurl');
            if ( ssdc_option('boxlinkblank') ) { $target = 'target="_blank" '; }
        } else if ( ssdc_option('boxlinkid') ) {
            $link = get_permalink( apply_filters( 'wpml_object_id', ssdc_option('boxlinkid'), 'page' ) );
            if ( ssdc_option('boxlinkblank') ) { $target = 'target="_blank" '; }
        } else {
            $link = '#';
        }

        $class_bar = "";
        if (ssdc_option('position') == "bar_bottom") {
            $class_bar = " cookie_notice_bar cookie_notice_bar_bottom";
        } else if (ssdc_option('position') == "bar_top") {
            $class_bar = " cookie_notice_bar cookie_notice_bar_top";
        }

        $return = '<!--  '.get_option( 'ecl_version_number' ).' -->';
        $return .= '<div id="simaCookie_wrapper" class="pea_cook_wrapper sima_wrapper pea_cook_'.ssdc_option('position'). $class_bar .'" style="color:'.ssdc_frontstyle('fontcolor').';background:rgb('.ssdc_frontstyle('backgroundcolor').');background: rgba('.ssdc_frontstyle('backgroundcolor').',0.9);">';
        $return .= '<div class="pea_cook_content">';
        
        if (ssdc_option('systemcatcb') > 0 || ssdc_option('prefcatcb') > 0 || ssdc_option('statcatcb') > 0 || ssdc_option('markcatcb') > 0) {
        $return .= '<p>Für diese Internetseite verwenden wir Cookies für folgende Funktionen:</p>'; }
        $return .= '<div class="labels">';
        if(ssdc_option('systemcatcb') > 0) {
            $return .= '<span class="categorie_label" title="'.ssdc_option('systemcatinfo').'">System</span>';
        }
        if(ssdc_option('prefcatcb') > 0) {
            $return .= '<span class="categorie_label"  title="'.ssdc_option('prefcatinfo').'">Präferenzen</span>';
        }
        if(ssdc_option('statcatcb') > 0) {
            $return .= '<span class="categorie_label"  title="'.ssdc_option('statcatinfo').'">Statistik</span>';
        }
        if(ssdc_option('markcatcb') > 0) {
            $return .= '<span class="categorie_label"  title="'.ssdc_option('markcatinfo').'">Marketing</span>';
        }

        if (ssdc_option('systemcatcb') > 0 || ssdc_option('prefcatcb') > 0 || ssdc_option('statcatcb') > 0 || ssdc_option('markcatcb') > 0) {
            $return .= '<br/>'; }

        // Message and Button(s)
        $return .= '<div class="sima_barmessage">
        
        <p class="sima_barmessage_p">'.ssdc_option('barmessage').' <a style="color:'.ssdc_option('fontcolor').';" href="'.$link.'" '.$target.'id="fom">'.ssdc_option('barlink').'</a>

        <button id="pea_cook_btn" class="pea_cook_btn sima_button sima_accept_button" href="#">'.ssdc_option('barbutton').'</button></p>';
        $return .= '</div></div></div>'; 
        echo apply_filters( 'eu_cookie_law_frontend_banner', $return );

        $return = '<div class="pea_cook_more_info_popover"><div class="pea_cook_more_info_popover_inner" style="color:'.ssdc_frontstyle('fontcolor').';background-color: rgba('.ssdc_frontstyle('backgroundcolor').',0.9);">';
        $return .= '<p>'.ssdc_option('boxcontent').'</p><p><a style="color:'.ssdc_option('fontcolor').';" href="#" id="pea_close">'.ssdc_option('closelink').'</a></p>';
        $return .= '</div></div>';
        echo apply_filters( 'eu_cookie_law_frontend_popup', $return );
    }
}, 1000);

function ssdc_generate_cookie_notice_text($height, $width, $text) {
    return '<div class="simaCookie" style="color:'.ssdc_frontstyle('fontcolor').'; background: rgba('.ssdc_frontstyle('backgroundcolor').',0.85) url(\''.plugins_url('img/block.jpeg', __FILE__).'\') no-repeat; background-position: -30px -20px; width:'.$width.';height:'.$height.';"><span>'.$text.'</span></div><div class="clear"></div>';    
}

function ssdc_generate_cookie_notice($height, $width) {
    return ssdc_generate_cookie_notice_text($height, $width, ssdc_option('bhtmlcontent') );
}

add_shortcode( 'cookie', function ( $atts, $content = null ) {
    extract(shortcode_atts(
        array(
            'height' => '',
            'width' => '',
            'text' => ssdc_option('bhtmlcontent')
        ),
        $atts)
    );
    if ( ssdc_cookie_accepted() ) {
        return do_shortcode( $content );
    } else {
        if (!$width) { $width = ssdc_pulisci($content,'width='); }
        if (!$height) { $height = ssdc_pulisci($content,'height='); }
        return ssdc_generate_cookie_notice($height, $width);
    }
} );

function ssdc_buffer_start() { ob_start(); }
function ssdc_buffer_end() {
    $contents = ssdc_erase(ob_get_contents());
    ob_end_clean();
    echo $contents;
}

add_action('wp_head', 'ssdc_buffer_start'); 
add_action('wp_footer', 'ssdc_buffer_end'); 

function ssdc_erase($content) {
    if ( !ssdc_cookie_accepted() && ssdc_option('autoblock') &&
        !(get_post_field( 'eucookielaw_exclude', get_the_id() ) )
       ) {
        
        $content = preg_replace('#<iframe.*?\/iframe>|<object.*?\/object>|<embed.*?>#is', ssdc_generate_cookie_notice('auto', '100%'), $content);
        $content = preg_replace('#<script.(?:(?!eucookielaw_exclude).)*?\/script>#is', '', $content);
        $content = preg_replace('#<!cookie_start.*?\!cookie_end>#is', ssdc_generate_cookie_notice('auto', '100%'), $content);
        $content = preg_replace('#<div id=\"disqus_thread\".*?\/div>#is', ssdc_generate_cookie_notice('auto', '100%'), $content);
    }
    return $content;
}

//Compatibility for Jetpack InfiniteScroll
add_filter( 'infinite_scroll_js_settings', 'ssdc_infinite_scroll_js_settings' );
function ssdc_infinite_scroll_js_settings($js_settings) {
    return array_merge ( $js_settings, array( 'eucookielaw_exclude' => 1) );
}

add_filter( 'widget_text', 'do_shortcode');

function ssdc_pulisci($content,$ricerca){
	$caratteri = strlen($ricerca)+6;
	$stringa = substr($content, strpos($content, $ricerca), $caratteri);
	$stringa = str_replace($ricerca, '', $stringa);
	$stringa = trim(str_replace('"', '', $stringa));
	return $stringa;
}

function ssdc_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   return array($r, $g, $b);
}

function ssdc_frontstyle($name) {
    switch ($name) {
    case 'fontcolor':
        return  ssdc_option('fontcolor');
        break;
    case 'backgroundcolor':
        $backgroundcolors = ssdc_hex2rgb( ssdc_option('backgroundcolor') );
        return $backgroundcolors[0].','.$backgroundcolors[1].','.$backgroundcolors[2];
        break;
    }
}

function ssdc_control_shortcode( $atts ) {
    if ( !ssdc_option('enabled') ) { return; }
    if ( ssdc_cookie_accepted() ) {
        return '
            <div class="pea_cook_control" style="color:'.ssdc_frontstyle('fontcolor').'; background-color: rgba('.ssdc_frontstyle('backgroundcolor').',0.9);">
                '.ssdc_option('cc-cookieenabled').'<br>
                <button id="eu_revoke_cookies" class="eu_control_btn">'.ssdc_option('cc-disablecookie').'</button>
            </div>';
    } else {
        return '
            <div class="pea_cook_control" style="color:'.ssdc_frontstyle('fontcolor').'; background-color: rgba('.ssdc_frontstyle('backgroundcolor').',0.9);">
                '.str_replace( '%s', ssdc_option('barbutton'), ssdc_option('cc-cookiedisabled') ).'
            </div>';            
    }
}
add_shortcode( 'cookie-control', 'ssdc_control_shortcode' );

function ssdc_list_shortcode( $atts ) {
   
    echo '<h3>Active Cookies</h3>
    <table style="width:100%; word-break:break-all;">
        <tr>
            <th>'.__('Name', 'eu-cookie-law').'</th>
            <th>'.__('Value', 'eu-cookie-law').'</th> 
        </tr>';
    foreach ($_COOKIE as $key=>$val) {

        echo '<tr>';
        echo '<td>'.$key.'</td>';
        echo '<td>'.$val.'</td>';
        echo '</tr>';
    }
    echo '</table>';
    
}
add_shortcode( 'cookie-list', 'ssdc_list_shortcode' );

