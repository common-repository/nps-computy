<?php
/*
 * Plugin Name:   NPS computy
 * Version:       2.8.0
 * Text Domain:   nps-computy
 * Plugin URI:    https://computy.ru/blog/plagin-nps-indeks-loyalnosti-klientov-dlya-wordpress/
 * Description:   Free monitoring of the NPS index (NPS) for your business. Simply add the shortcode [nps-computy] to the right place on the site, the rest of the work we will do for you.
 * Author:        computy
 * Author URI:    https://computy.ru
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	add_action( 'init', array( 'Nps_Computy_Admin', 'init' ) );
}
/*Общие переменные*/
define( 'NPS_COMPUTY_VERSION', '2.8.0' );
define( 'NPS_COMPUTY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );



/*Функция, которая запускается при активации плагина*/
register_activation_hook( __FILE__, 'nps_computy_activate' );

/*Функция, которая запускается при деактивации плагина*/
register_deactivation_hook( __FILE__, 'nps_computy_deactivate' );

$npsc_property_id_name = 'npsc_property_id';
$npsc_active_name      = 'npsc_active';

/*---------------------------------------------------------------------------*/

/*версия плагина*/
global $npsc_db_version;
$npsc_db_version = '1.16';
$dv_version=get_option( 'npsc_db_version' );
if($dv_version < $npsc_db_version){

    function npsc_admin_notice__error() {
        $class = 'notice notice-error';
        $message = __( 'An update is required for the database of the "NPS computy" plugin. Please go to the plugins page, deactivate and re-activate the "NPS computy" plugin. Sorry for the inconvenience.
', 'nps-computy' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }
    add_action( 'admin_notices', 'npsc_admin_notice__error' );
}else{}


function nps_computy_activate() {
	/*Создание таблицы в базе данных*/
	global $wpdb;
	global $npsc_db_version;

	$table_name = $wpdb->prefix . 'nps_computy';


        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		rating varchar(3) DEFAULT '' NOT NULL, 
		offers text NOT NULL,
		problems text NOT NULL,
		nameuser varchar(50) DEFAULT '' NOT NULL,
		telephone varchar(20) DEFAULT '' NOT NULL,
		email varchar(60) DEFAULT '' NOT NULL,
		url_page varchar(250) DEFAULT '' NOT NULL,
		get_name varchar(250) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );




	add_option( 'npsc_db_version', $npsc_db_version );
}

function nps_computy_deactivate(){
    delete_option('npsc_db_version'); //очищаем версию базы данных
}

/*---------------------------------------------------------------------------*/

/*Страница админки*/
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( NPS_COMPUTY_PLUGIN_DIR . 'class.nps-computy-admin.php' );
	add_action( 'init', array( 'Nps_Computy_Admin', 'init' ) );
}
/*Страница админки*/

//добавляем стили на самом сайте
function add_nps_computy_styles() {
	wp_register_style( 'nps-computy-style', plugin_dir_url( __FILE__ ) . '_inc/nps-computy-style.css' );
	wp_enqueue_style( 'nps-computy-style' );
}

add_action( 'wp_enqueue_scripts', 'add_nps_computy_styles' );

//добавляем скрипты на самом сайте
function nps_computy_script() {
	wp_register_script( 'nps-computy-script', plugin_dir_url( __FILE__ ) . '_inc/nps-computy-script.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'nps-computy-script' );
}

add_action( 'wp_enqueue_scripts', 'nps_computy_script' );

/*[nps-computy]  вывод формы голосования*/
function nps_func(): string
{
    global $post;
    $promo = '';
    $contact = '';
	$r = get_option( 'nps_option_name' );

     $npszagolovok = $r["nps-zagolovok"] ?? __( "Net Promoter Score (NPS)", "nps-computy" );

    if ( isset($_GET["nps"])){
        $npszagolovok.=' '.htmlspecialchars($_GET["nps"]);
    }

    $npsdesc = $r["nps-desc"] ?? __( "Please, assess the scale from 0 to 10, just as often as you think you need another or a collar?", "nps-computy" );
    $rcooka = $r["cooka"] ?? 0;
    $npsproblems = $r["nps-problems"] ?? __( "What do we need to improve in our work so that you can rate us 10?", "nps-computy" );
	$npsoffers =  $r["nps-offers"] ?? __( "Our bachelors to improve the quality of our services (additional services, etc.)", "nps-computy" );
	$npsuje = $r["nps-uje"] ?? __( "Excuse me! You have already passed the vote. If you forgot to supplement your feedback, write to us via feedback.", "nps-computy" );
    $npsbutton=$r["nps-button"] ?? __( "Send", "nps-computy" );
    $rpromo = $r["promo"] ?? 0;


	if ( $rpromo == '1' ) {
		$promo = '<div class="nps-computy">' . __( "Powered by", "nps-computy" ) . ' <a target="_blank" href="https://computy.ru"> computy.ru</a> </div>';
	}
   $rcontact = $r["contact"] ?? 0;
   $rcontacttel = $r["contact-tel"] ?? 0;
   $rcontactemail = $r["contact-email"] ?? 0;
	if ( $rcontact == '1' ) {
		$contact .= '<div class="input_nps"><input type="text" name="name" placeholder="' . __( "Your name", "nps-computy" ) . '"></div>';
	}
    if ($rcontacttel === '1' ) {
        $contact .= '<div class="input_nps"><input type="text" name="telephone" placeholder="' . __( "You telephone", "nps-computy" ) . '"></div>';
    }
    if ( $rcontactemail === '1' ) {
        $contact .= '<div class="input_nps"><input type="email" name="email" placeholder="' . __( "You E-mail", "nps-computy" ) . '"></div>';
    }

    $zerro = $r['zerro'] ?? '<input type="radio" id="radio-0" name="radio" value="0"><label for="radio-0"><div class="index i0">0</div></label>';
    if($zerro==='1'){$zerro='';}
    if(isset($_GET["nps"])){
        $getname = '<input type="hidden" name="get_name" value="'.htmlspecialchars($_GET["nps"]).'">';
    }else{
        $getname='';
    }
	return '
<div class="nps">
    <div class="zagolovok-nps">' . sanitize_text_field($npszagolovok). '</div>
    <style>
.error-nps::before {
	content: "'.__('Please rate!','nps-computy').'";
	display: block;
}
</style>
    <form method="post" id="nps-computy" action="javascript:void(null);" >
    <input type="hidden" id="rcooka" name="rcooka" value="'.$rcooka.'">
        <div class="question-container">
                <div class="desc-nps">' . esc_html($npsdesc). '</div>
                <div class="validationError" style="display:none">' . __( "The question is mandatory", "nps-computy" ) . '</div>
                <div class="nps-radios" >
            '.$zerro.'
            <input type="radio" id="radio-1" name="radio" value="1">
            <label for="radio-1">
                <div class="index i1">1</div>
            </label>

            <input  type="radio" id="radio-2" name="radio" value="2">
            <label for="radio-2">
                <div class="index i2">2</div>
            </label>

            <input  type="radio" id="radio-3" name="radio" value="3">
            <label for="radio-3">
                <div class="index i3">3</div>
            </label>

            <input type="radio" id="radio-4" name="radio" value="4">
            <label for="radio-4">
                <div class="index i4">4</div>
            </label>

            <input type="radio" id="radio-5" name="radio" value="5">
            <label for="radio-5">
                <div class="index i5">5</div>
            </label>

            <input  type="radio" id="radio-6" name="radio" value="6">
            <label for="radio-6">
                <div class="index i6">6</div>
            </label>

            <input  type="radio" id="radio-7" name="radio" value="7">
            <label for="radio-7">
                <div class="index i7">7</div>
            </label>

            <input  type="radio" id="radio-8" name="radio" value="8">
            <label for="radio-8">
                <div class="index i8">8</div>
            </label>
            <input type="radio" id="radio-9" name="radio" value="9">
            <label for="radio-9">
                <div class="index i9">9</div>
            </label>

            <input type="radio" id="radio-10" name="radio" value="10">
            <label for="radio-10">
                <div class="index i10">10</div>
            </label>
</div>
            </div>
        <div class="nps-input-forms">
        <div class="textarea">
            <div class="title-nps">' . esc_html($npsoffers) . '<span class="chto">' . esc_html($npsproblems) . '</span> </div>
        <textarea cols="30" rows="3" class="nps-textarea" name="problems"></textarea>
        </div>
        
        ' . $contact . '
         
<input type="hidden" name="action" value="nps_computy_ajax">
 <input type="hidden" name="url_page" value="'.get_permalink( $post->ID ).'">
 '.$getname.'
        <div class="clear">
            <button name="button" type="submit" class="nps-submit"><span class="spin"></span>' . esc_html($npsbutton) . '</button>
        </div>
</div>
    </form>
    <div id="results-nps"></div>
    <div id="youbil-computy">' . $npsuje . '</div>
    ' . $promo . '</div>';

}
add_shortcode( 'nps-computy', 'nps_func' );


/*[nps-computy-chart]  вывод результата голосования*/
function nps_func_chart()
{
    global $wpdb;

    $vsego = $wpdb->get_var('SELECT count(*) FROM ' . $wpdb->prefix . 'nps_computy WHERE rating != "" ');//всего проголосовавших
    if ($vsego == 0) {
        return _e('No one else reviews in the survey!', 'nps-computy');
    } else {

        $chart =  '<p style="font-size: 20px">' . __('Total reviews ', 'nps-computy') . ' ' . $vsego;


        $storonniki = $wpdb->get_var('SELECT count(*)  FROM ' . $wpdb->prefix . 'nps_computy' . ' WHERE rating=9 or rating=10 ');//ищем сторонников
        $procent_storonnikov = $storonniki * 100 / $vsego;
        $pr_okr_st = round($procent_storonnikov, 0);

        $protivniki = $wpdb->get_var('SELECT count(*)  FROM ' . $wpdb->prefix . 'nps_computy' . ' WHERE rating="0" or rating=1 or rating=2 or rating=3 or rating=4 or rating=5 or rating=6 ');//ищем противников
        $procent_protivniki = $protivniki * 100 / $vsego;
        $pr_okr_pr = round($procent_protivniki, 0);

        $neitral = $vsego - $protivniki - $storonniki;
        $pr_okr_nei = 100 - $pr_okr_pr - $pr_okr_st;

        $chart .=  '
 <div class="c100 p' . $pr_okr_st . ' green">
                <div class="title-nps-chart gr">' . __("Supporters", "nps-computy") . ' ' . $storonniki . '</div>
                    <span>' . $pr_okr_st . '%</span>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>

                  <div class="c100 p' . $pr_okr_pr . ' red">
                   <div class="title-nps-chart re">' . __("Critics", "nps-computy") . ' ' . $protivniki . '</div>
                    <span>' . $pr_okr_pr . '%</span>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>
                
                  <div class="c100 p' . $pr_okr_nei . ' orange">
                   <div class="title-nps-chart or">' . __("Neutral", "nps-computy") . ' ' . $neitral . '</div>
                    <span>' . $pr_okr_nei . '%</span>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>
   <div class="clear"></div>
 
 ';
        $nps1 = $storonniki - $protivniki;
        $nps2 = $nps1 / $vsego;
        $nps = $nps2 * 100;

        if (round($nps, 0) <= -30) {
            $colornpstext = '#d92b22';
        } elseif (round($nps, 0) > -30 && round($nps, 0) <= 30) {
            $colornpstext = '#e08833';
        } elseif (round($nps, 0) > 30) {
            $colornpstext = '#4db53c';
        }

        $chart .= '
              <div class="speed">
              <div class="strelka nps' . round($nps, 0) . '"></div>
              <div class="nps-text" style="color: ' . $colornpstext . '">NPS:<br> ' . round($nps, 2) . '%</div>
</div> ';
        return $chart;
    }

}
add_shortcode( 'nps-computy-chart', 'nps_func_chart' );