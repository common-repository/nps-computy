<?php
/*class admin page*/
class Nps_Computy_Admin {
    public static function init() {

        add_action( 'admin_menu', array( 'Nps_Computy_Admin', 'add_admin_menu' ) );/* инициализируем меню в админке*/
        add_action( 'admin_enqueue_scripts', array( 'Nps_Computy_Admin', 'load_scripts' ) );/*Загружаем скрипты и стили*/
        add_action( 'admin_init', array( 'Nps_Computy_Admin', 'plugin_settings' ) );/*Вывод настроек в меню*/
        add_action( 'wp_ajax_nps_computy_ajax', array( 'Nps_Computy_Admin', 'nps_computy_ajax' ) );//отправка данных аякс
        add_action( 'wp_ajax_nopriv_nps_computy_ajax', array( 'Nps_Computy_Admin', 'nps_computy_ajax' ) );//отправка данных аякс


        add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . 'index.php' ), array( 'Nps_Computy_Admin', 'admin_plugin_settings_link' ) );/*добавляем ссылку на настройки на странице плагинов */
    }

    public static function add_admin_menu() {
        $hello1 = __( 'NPS Monitoring', 'nps-computy' );
        add_options_page( ' ', $hello1, 'manage_options', 'nps-plugin-options', array( 'Nps_Computy_Admin', 'nps_plugin_options' ) );
    }

    public static function admin_plugin_settings_link( $links ) {
        $settings_link = '<a href="options-general.php?page=nps-plugin-options">Настройки и результаты</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    public static function load_scripts() {
        wp_register_style( 'nps-computy-style-admin.css', plugin_dir_url( __FILE__ ) . '_inc/nps-computy-style-admin.css', array(), NPS_COMPUTY_VERSION );


        wp_enqueue_style( 'nps-computy-style-admin.css' );






        wp_register_style( 'datatables.min.css', plugin_dir_url( __FILE__ )  . '_inc/datatables/datatables.min.css', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'jquery.dataTables.min.js', plugin_dir_url( __FILE__ )  . '_inc/datatables/jquery.dataTables.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'dataTables.responsive.min.js', plugin_dir_url( __FILE__ )  . '_inc/datatables/Responsive-2.2.5/js/dataTables.responsive.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_style( 'datatablesres.min.css', plugin_dir_url( __FILE__ )  . '_inc/datatables/Responsive-2.2.5/css/responsive.dataTables.min.css', array(), NPS_COMPUTY_VERSION );

        wp_enqueue_style( 'datatables.min.css' );
        wp_enqueue_script( 'jquery.dataTables.min.js' );
        wp_enqueue_script( 'dataTables.responsive.min.js' );
        wp_enqueue_style( 'datatablesres.min.css' );


        wp_register_style( 'button.min.css', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/buttons.dataTables.min.css', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'button-js1', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/buttons.html5.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'button-js2', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/buttons.print.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'button-js3', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/dataTables.buttons.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'button-js4', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/jszip.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'button-js5', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/pdfmake.min.js', array(), NPS_COMPUTY_VERSION );
        wp_register_script( 'button-js6', plugin_dir_url( __FILE__ )  . '_inc/datatables/buttons/vfs_fonts.js', array(), NPS_COMPUTY_VERSION );


        wp_enqueue_style( 'button.min.css' );

        wp_enqueue_script( 'button-js2' );
        wp_enqueue_script( 'button-js3' );
        wp_enqueue_script( 'button-js4' );
        wp_enqueue_script( 'button-js5' );
        wp_enqueue_script( 'button-js6' );
        wp_enqueue_script( 'button-js1' );
    }

    public static function plugin_settings() {
        // параметры: $option_group, nps_option_name, $sanitize_callback
        register_setting( 'option_group_nps', 'nps_option_name', 'sanitize_callback' );
        $trans1  = __( 'Text output settings', 'nps-computy' );
        $trans2  = __( 'Title', 'nps-computy' );
        $trans3  = __( 'Text under the title', 'nps-computy' );
        $trans4  = __( 'The text of the proposal for improving the quality', 'nps-computy' );
        $trans5  = __( 'The text of the question that needs improvement (If answer from 0-9)', 'nps-computy' );
        $trans6  = __( 'Thank-you text after the client\'s response', 'nps-computy' );
        $trans7  = __( 'Thank you text for the highest score', 'nps-computy' );
        $trans8  = __( 'Pop-up text if the client has already polled', 'nps-computy' );
        $trans9  = __( 'After how many days can the customer again be interviewed?', 'nps-computy' );
        $trans10 = __( 'Please leave a link to our website computy.ru. This will promote our plug-in in the world. Thank you!', 'nps-computy' );
        $trans11 = __( 'Show name?', 'nps-computy' );
        $trans15 = __( 'Show phone number?', 'nps-computy' );
        $trans16 = __( 'Show email?', 'nps-computy' );
        $trans12 = __('Name of the submit button','nps-computy');
        $trans13 = __('Rederct page after poll (enter full url)','nps-computy');
        $trans14 = __('Remove vote "zero"?','nps-computy');
        // параметры: $id, $title, $callback, $page
        add_settings_section( 'section_id', $trans1, '', 'primer_page' );
        // параметры: $id, $title, $callback, $page, $section, $args
        add_settings_field( 'primer_field1', $trans2, array( 'Nps_Computy_Admin', 'fill_primer_field1' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field2', $trans3, array( 'Nps_Computy_Admin', 'fill_primer_field2' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field3', $trans4, array( 'Nps_Computy_Admin', 'fill_primer_field3' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field4', $trans5, array( 'Nps_Computy_Admin', 'fill_primer_field4' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field5', $trans6, array( 'Nps_Computy_Admin', 'fill_primer_field5' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field6', $trans7, array( 'Nps_Computy_Admin', 'fill_primer_field6' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field7', $trans8, array( 'Nps_Computy_Admin', 'fill_primer_field7' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field12', $trans12, array( 'Nps_Computy_Admin', 'fill_primer_field12' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field8', $trans9, array( 'Nps_Computy_Admin', 'fill_primer_field8' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field10', $trans11, array( 'Nps_Computy_Admin', 'fill_primer_field10' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field15', $trans15, array( 'Nps_Computy_Admin', 'fill_primer_field15' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field16', $trans16, array( 'Nps_Computy_Admin', 'fill_primer_field16' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field11', $trans14, array( 'Nps_Computy_Admin', 'fill_primer_field11' ), 'primer_page', 'section_id' );

        add_settings_field( 'primer_field9', $trans10, array( 'Nps_Computy_Admin', 'fill_primer_field9' ), 'primer_page', 'section_id' );
        add_settings_field( 'primer_field13', $trans13, array( 'Nps_Computy_Admin', 'fill_primer_field13' ), 'primer_page', 'section_id' );
    }

    ## Заполняем опцию 13
    public static function fill_primer_field13() {
        $val = get_option( 'nps_option_name' );
        $val =  $val['nps-redirectpage'] ?? '';
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-redirectpage]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    ## Заполняем опцию 1
    public static function fill_primer_field1() {
        $val = get_option( 'nps_option_name' );
        $val = $val['nps-zagolovok'] ?? __( 'Net Promoter Score (NPS)', 'nps-computy' );
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-zagolovok]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 2
    public static function fill_primer_field2() {
        $trans = __( 'Please, assess the scale from 0 to 10, just as often as you think you need another or a collar?', 'nps-computy' );
        $val   = get_option( 'nps_option_name' );
        $val =  $val['nps-desc'] ?? $trans;
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-desc]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 3
    public static function fill_primer_field3() {
        $trans = __( 'Our bachelors to improve the quality of our services (additional services, etc.)', 'nps-computy' );
        $val = get_option( 'nps_option_name' );
        $val =  $val['nps-offers'] ?? $trans;
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-offers]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 4
    public static function fill_primer_field4() {
        $trans = __( 'What do we need to improve in our work so that you can rate us 10?', 'nps-computy' );
        $val = get_option( 'nps_option_name' );
        $val =  $val['nps-problems'] ?? $trans;
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-problems]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 5
    public static function fill_primer_field5() {
        $trans = __( 'Thank you for the assessment! We take into account your wishes!', 'nps-computy' );
        $val = get_option( 'nps_option_name' );
        $val =  $val['nps-thank'] ?? $trans;
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-thank]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 56
    public static function fill_primer_field6() {
        $trans = __( 'Evaluation is accepted! Thank you for the highest score!', 'nps-computy' );
        $val = get_option( 'nps_option_name' );
        $val = $val['nps-thank10'] ?? $trans;
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-thank10]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 7
    public static function fill_primer_field7() {
        $trans = __( 'Excuse me! You have already passed the vote. If you forgot to supplement your feedback, write to us via feedback.', 'nps-computy' );
        $val = get_option( 'nps_option_name' );
        $val = $val['nps-uje'] ?? $trans;
        ?>
        <input style="width: 100%" type="text" name="nps_option_name[nps-uje]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    ##Name submit button nps-button
    public static function fill_primer_field12() {
        $trans = __( 'Send', 'nps-computy' );
        $val = get_option( 'nps_option_name' );
        $val = $val['nps-button'] ?? $trans;
        ?>
        <input type="text" name="nps_option_name[nps-button]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 8
    public static function fill_primer_field8() {
        $val = get_option( 'nps_option_name' );
        $val = $val['cooka'] ?? '0';
        ?>
        <input style="width: 100px" type="text" name="nps_option_name[cooka]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

## Заполняем опцию 9
    public static function fill_primer_field9() {
        $val = get_option( 'nps_option_name' );
        $vp = $val['promo'] ?? '';

        $val = $val ? $vp : null;
        ?>
        <label><input type="checkbox" name="nps_option_name[promo]" value="1" <?php checked( 1, $val ) ?> /> <?php _e( ' link to our website', 'nps-computy' ); ?>
        </label>
        <?php
    }

    public static function fill_primer_field10() {
        $val = get_option( 'nps_option_name' );
        $vc = $val['contact'] ?? '';
        $val = $val ? $vc : null;
        ?>
        <label><input type="checkbox" name="nps_option_name[contact]" value="1" <?php checked( 1, $val ) ?> /> </label>
        <?php
    }
    public static function fill_primer_field15() {
        $val = get_option( 'nps_option_name' );
        $vc = $val['contact-tel'] ?? '';
        $val = $val ? $vc : null;
        ?>
        <label><input type="checkbox" name="nps_option_name[contact-tel]" value="1" <?php checked( 1, $val ) ?> /> </label>
        <?php
    }
    public static function fill_primer_field16() {
        $val = get_option( 'nps_option_name' );
        $vc = $val['contact-email'] ?? '';
        $val = $val ? $vc : null;
        ?>
        <label><input type="checkbox" name="nps_option_name[contact-email]" value="1" <?php checked( 1, $val ) ?> /> </label>
        <?php
    }
    public static function fill_primer_field11() {
        $val = get_option( 'nps_option_name' );
        $checked = isset($val['zerro']) ? "checked" : "";
        ?>
        <label><input type="checkbox" name="nps_option_name[zerro]" value="1" <?php echo $checked; ?> /> </label>
        <?php
    }

## Очистка данных
    public static function sanitize_callback( $options ) {
        // очищаем
        foreach ( $options as $name => & $val ) {

            if ( $name == 'promo' ){
                $val = intval( $val );
            }else{
                $val = wp_strip_all_tags( $val );
            }



        }

        return $options;
    }

    public static function nps_plugin_options() {

        if ( current_user_can( 'manage_options' ) ) {
            global $wpdb;
            if($_SERVER["REQUEST_METHOD"]==="POST"){

                $post_id = esc_html($_POST['id']) ?? '';
                $event = esc_html($_POST['event']) ?? '';
                $nonce = esc_html($_POST['_wpnonce']) ?? '';

                if($event==='delete' && wp_verify_nonce( $nonce, 'nps-nonce')){
                    $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."nps_computy WHERE id= %d", $post_id));
                }
                if($event==='delete_all' && wp_verify_nonce( $nonce, 'nps-nonce')){
                    $wpdb->query("DELETE FROM ".$wpdb->prefix."nps_computy");
                }




            }

        }



        // тут уже будет находиться содержимое страницы настроек
        ?>
        <div class="wrap nps-computy-admin">

        <h2><?php echo _e( 'NPS Monitoring', 'nps-computy' ), ' <small>V', NPS_COMPUTY_VERSION.'</small>'; ?></h2>
        <p><?php $bd_version = get_option( 'npsc_db_version' );
            echo _e( 'bd version: ', 'nps-computy' )," ", $bd_version; ?>
        </p>
        <p><?php echo _e( 'With the support of <a href="https://computy.ru" target="_blank" title="Development and support of sites on WordPress"> Computy </a>', 'nps-computy' ) ?><br>
            <a href="https://yoomoney.ru/to/410011302808683" target="_blank"><?php echo __( 'Throw money at me!', 'nps-computy' );?></a><br>
            <a href="https://computy.ru/blog/nps-plugin/" target="_blank"><?php echo __( 'About plugin', 'nps-computy' );?></a>
        </p>

        <hr />
        <h2><?php echo _e( 'Configuring the Plugin', 'nps-computy' ); ?></h2>

        <?php echo _e( ' In the code editor, insert the <b> [nps-computy] </b> shortcode in the right place and the 
 plug-in is ready for use.<br>If you want to insert directly into the site code, 
 then paste this code:<b> &#60;?php echo do_shortcode( \'[nps-computy]\' ); ?&#62;</b><br>
 if you want to publish survey results on a site such as in the administrative panel, then you need to add a shortcode <b> [nps-computy-chart]</b> ', 'nps-computy' );
      ?>
 <p><?php echo __('Since version 2.6.0, it has become possible to create several surveys and view statistics for each.', 'nps-computy'); ?><br>
 <?php echo __('To use multiple polls, simply add a get-request to the page where the poll is taking place, for example: ?nps=poll name', 'nps-computy'); ?><br>
 <?php echo __('After that your title will change to: "Net Promoter Score (NPS) <b>poll name</b>". There can be any number of such headers. Statistics for each survey can also be displayed.', 'nps-computy'); ?></p>

        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <form action="options.php" method="POST">
                <?php
                settings_fields( 'option_group_nps' );     // скрытые защитные поля
                do_settings_sections( 'primer_page' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
                submit_button();
                ?>
            </form>
        </div>

        <hr />
        <h2><?php echo _e( 'Description of the plugin', 'nps-computy' ) ?></h2>
        <?php echo _e( '<p><b>NPS Customer Loyalty Index</b> (Net Promoter Score, Net Support Index) is easy to calculate a metric aimed at assessing the loyalty of customers of the company or buyers of any product. It is believed that the NPS index is closely correlated with the company\'s revenues and the company with a high NPS indicator has the tendency to grow much faster than its competitors.</p> <p>By polling we get the data that is further processed.</p>', 'nps-computy' ) ?>

        <img alt="nps" style="" src="<?php echo plugins_url() ?>/nps-computy/img/nps.jpg" />

        <h2 style="font-size: 30px;"><?php echo _e( 'Received data on your site', 'nps-computy' ) ?></h2>

        <div style="display:table; ">
        <div>
        <?php
        global $wpdb;

        if(isset($_POST['data1'])) { $data1 = $_POST['data1'];  } else {
            $thisDate = gmdate('Y-m-d');
           $data1 = gmdate('Y-m-d', strtotime($thisDate. " - 35 year"));

        }
        if(isset($_POST['data2'])) {
            $daythis= gmdate('Y-m-d');
            $data22 = $_POST['data2'];
            $data2 = gmdate('Y-m-d',strtotime($daythis . "+1 days"));

            if($data22 === '') { $data22 = $data2   = gmdate('Y-m-d',strtotime($daythis . "+1 days"));  }

        } else { $data22 = gmdate('Y-m-d'); $data2   = gmdate('Y-m-d',strtotime($data22 . "+1 days")); }


        $poll_in_db = '';
        $pol_name_value= '';
        if(!empty($_POST['pol_name'])) {
            $pol_name_value  = esc_attr($_POST['pol_name']);
            $poll_in_db = "`get_name` LIKE '$pol_name_value' AND";
        }

        echo "<form method='post'>
 " . __( "From", "nps-computy" ) . ": <input id='data1' class='input-medium datepicker' type='date' name='data1' value='".$data1."'/>
  " . __( "to", "nps-computy" ) . ": <input class='input-medium datepicker' type='date' name='data2' value='".$data22."'/>
   " . __( "Poll name", "nps-computy" ) . ": <input type='text' name='pol_name' value='".$pol_name_value."'/>
 <input type='submit' style='margin-top: -3px' class='btn btn-success' value='" . __( "Calculate", "nps-computy" ) . "'>
 </form>

 <br>";

$vsego1 = $wpdb->get_results(  "SELECT *, DATE_FORMAT(time, '%d.%m.%Y %H:%i') FROM {$wpdb->prefix}nps_computy WHERE $poll_in_db `rating` != '' AND  `time` BETWEEN '$data1'  AND  '$data2' ");//всего проголосовавших

        $vsego = count($vsego1);

        if ( $vsego === 0 ) {
            echo _e( 'No one else participated in the survey!', 'nps-computy' );
        } else {
            echo '<p style="font-size: 20px">' . __( 'Total participated ', 'nps-computy' ) . ' ' . $vsego;



            $storonniki = $wpdb->get_results( "SELECT *, DATE_FORMAT(time, '%d.%m.%Y %H:%i')  FROM {$wpdb->prefix}nps_computy WHERE $poll_in_db `rating` > 8 AND  `time` BETWEEN '$data1' AND '$data2'  " );
            $storonnikio = count($storonniki);

            $procent_storonnikov = $storonnikio * 100 / $vsego;
            $pr_okr_st           = round( $procent_storonnikov, 0 );

            $protivniki1         = $wpdb->get_results("SELECT *, DATE_FORMAT(time, '%d.%m.%Y %H:%i')  FROM {$wpdb->prefix}nps_computy  WHERE $poll_in_db `rating` < 7  AND  `time` BETWEEN  '$data1'  AND '$data2' " );//ищем противников
            $protivniki = count($protivniki1);
            $procent_protivniki = $protivniki * 100 / $vsego;
            $pr_okr_pr          = round( $procent_protivniki, 0 );

            $neitral    = $vsego - $protivniki - $storonnikio;
            $pr_okr_nei = 100 - $pr_okr_pr - $pr_okr_st;

            echo '
 <div class="c100 p' . $pr_okr_st . ' green">
                <div class="title-nps-chart gr">' . __( "Supporters", "nps-computy" ) . ' ' . $storonnikio . '</div>
                    <span>' . $pr_okr_st . '%</span>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>

                  <div class="c100 p' . $pr_okr_pr . ' red">
                   <div class="title-nps-chart re">' . __( "Critics", "nps-computy" ) . ' ' . $protivniki . '</div>
                    <span>' . $pr_okr_pr . '%</span>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>
                
                  <div class="c100 p' . $pr_okr_nei . ' orange">
                   <div class="title-nps-chart or">' . __( "Neutral", "nps-computy" ) . ' ' . $neitral . '</div>
                    <span>' . $pr_okr_nei . '%</span>
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div>
   <div class="clear"></div>
 
 ';
            $nps1 = $storonnikio - $protivniki;
            $nps2 = $nps1 / $vsego;
            $nps  = $nps2 * 100;

            if ( round( $nps, 0 ) <= - 30 ) {
                $colornpstext = '#d92b22';
            } elseif ( round( $nps, 0 ) > - 30 && round( $nps, 0 ) <= 30 ) {
                $colornpstext = '#e08833';
            } elseif ( round( $nps, 0 ) > 30 ) {
                $colornpstext = '#4db53c';
            }

            echo '
              <div class="speed">
              <div class="strelka nps' . round( $nps, 0 ) . '"></div>
              <div class="nps-text" style="color: ' . $colornpstext . '">NPS:<br> ' . round( $nps, 2 ) . '%</div>
</div> ';


 $table_npss = $wpdb->get_results( "SELECT *, DATE_FORMAT(time, '%d.%m.%Y %H:%i') as date  FROM {$wpdb->prefix}nps_computy WHERE $poll_in_db rating != '' AND  `time` BETWEEN '$data1' AND '$data2'" );


            echo "<h2>" . __( "Output of data obtained as a result of a survey", "nps-computy" ) ."</h2>";
            ?>
 
<script>
jQuery(document).ready(function() {
    jQuery('#table-nps').DataTable(
         {      responsive: true,
                    <?php if(current_user_can('manage_options')){?>
                    dom: 'Bfrtip' ,
                    buttons: [
                        'excel', 'pdf', 'print'
                    ],
                    <?php } ?>
                    <?php if(!current_user_can('manage_options')){?>
                      'sDom': '"top"i',

                    <?php } ?>

                }
    );
} );

 
</script>

 
<?php
 

echo "<table class='table-nps' id='table-nps'>
<thead><tr>
<td>№</td>
<td>" . __( "Date of poll", "nps-computy" ) . "</td>
<td>" . __( "Rating", "nps-computy" ) . "</td>
<td>" . __( "Suggestions and what should be improved to evaluate 10?", "nps-computy" ) . "</td>
<td>" . __( "Name user", "nps-computy" ) . "</td>
<td>" . __( "User contacts", "nps-computy" ) . "</td>
<td>" . __( "Link from which sent", "nps-computy" ) . "</td>
<td>" . __( "Poll Name", "nps-computy" ) . "</td>";

            global $user_ID;
            if( is_super_admin( $user_ID ) ){
                echo "<td>" . __( "Delete", "nps-computy" ) . "</td>";
            }

            echo "</tr></thead><tbody>";

            $i = 1;
            foreach ( $table_npss as $table_nps ) {
                echo "<tr><td>" . $i ++ . "</td>";
                echo "<td>" .current_time($table_nps->date)  . "</td>";
                echo "<td>" . $table_nps->rating . "</td>";
                echo "<td>". $table_nps->offers ." ". $table_nps->problems . "</td>";
                echo "<td>" . $table_nps->nameuser . "</td>";
                echo "<td>" . $table_nps->telephone . " " . $table_nps->email . "</td>";
                echo "<td>" . $table_nps->url_page . "</td>";
                echo "<td>" . $table_nps->get_name . "</td>";
                if( is_super_admin( $user_ID ) ){
                    $nonce = wp_create_nonce('nps-nonce');
                    echo '<td>
<form method="post">
<input type="hidden" id="_wpnonce" name="_wpnonce" value="'.$nonce.'" />
<input type="hidden" value="' . $table_nps->id .'" name="id">
<input type="hidden" value="delete" name="event">
<input type="submit" class="nps-admin-delete" value="'.__( "Delete", "nps-computy" ).'">
</form></td>';
                }
                echo "</tr>";

            }
            echo "</tbody></table>";

            if(empty($_POST['data1'])  &&  empty($_POST['data2']) && is_super_admin( $user_ID )){

                $nonce = wp_create_nonce('nps-nonce');
                    echo '<form method="post">
<input type="hidden" id="_wpnonce" name="_wpnonce" value="'.$nonce.'" />
<input type="hidden" value="delete_all" name="event">
<input type="submit" onclick=\'return confirm("'.__( "Are you sure you want to delete the entire table?", "nps-computy" ).'") \' class="nps-admin-delete" value="'.__( "Delete all", "nps-computy" ).'">
</form> ';
            }
            }
            ?>

            </div>
            </div>
            </div>

            <?php
        }





    /*Обработчик запроса*/
    public static function nps_computy_ajax() {
        $rating = _sanitize_text_fields( $_POST['radio'] );

        if ( $rating > 10 || $rating < 0 ) {
            echo _e( "Hi, hacker!Something went wrong?))", "nps-computy" );
            exit;
        }
        if(isset($_POST['offers'])){
            $offers        = _sanitize_text_fields( $_POST['offers'] ) ;
        }else{
            $offers ='';
        }

        if(isset($_POST['problems'])){
            $problems        = _sanitize_text_fields( $_POST['problems'] ) ;
        }else{
            $problems ='';
        }

        if(isset($_POST['name'])){
            $nameuser        = _sanitize_text_fields( $_POST['name'] ) ;
        }else{
            $nameuser ='';
        }
        if(isset($_POST['email'])){
            $emailuser        = _sanitize_text_fields( $_POST['email'] ) ;
        }else{
            $emailuser ='';
        }
        if(isset($_POST['get_name'])){
            $get_name       = _sanitize_text_fields( $_POST['get_name'] ) ;
            $get_name_line = '<p>' . __( "Poll Name: ", "nps-computy" ) . $get_name . '</p>';
        }else{
            $get_name ='';
            $get_name_line='';
        }

        if(isset($_POST['telephone'])){
            $telephone        = _sanitize_text_fields( $_POST['telephone'] ) ;
        }else{
            $telephone ='';
        }


        $url_page      = esc_url( $_POST['url_page'] );
        $date          = current_time('Y-m-d H:i:s');
        $date_for_mail = current_time( 'd.m.Y H:i' );
        global $wpdb;

        // подготавливаем данные
        $table_name = $wpdb->prefix . 'nps_computy';

        // вставляем строку в таблицу
        $wpdb->insert(
            $table_name, array(
            'rating'    => $rating,
            'offers'    => $offers,
            'problems'  => $problems,
            'nameuser'  => $nameuser,
            'telephone' => $telephone,
            'email' => $emailuser,
            'url_page'  => $url_page,
            'get_name'  => $get_name,
            'time'      => $date ), array( "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s" )
        );

        $admin_email  = get_option( 'admin_email' );
        $multiple_to_recipients = array( $admin_email );


        add_filter( 'wp_mail_content_type', 'wp_mail_content_type_nps' );
        function wp_mail_content_type_nps( $content_type ): string
        {
            return 'text/html';
        }



        $transqw = __( 'New assessment of NPS', 'nps-computy' );
        wp_mail( $multiple_to_recipients, $transqw, '
            <p>' . __( "Date: ", "nps-computy" ) . $date_for_mail . '</p>
            <p>' . __( "Link from which sent: ", "nps-computy" ) . $url_page . '</p>
            '.$get_name_line.'
            <p>' . __( "Name: ", "nps-computy" ) . $nameuser . '</p>
            <p>' . __( "Telephone: ", "nps-computy" ) . $telephone . '</p>
            <p>' . __( "E-mail: ", "nps-computy" ) . $emailuser . '</p>
            <p>' . __( "Rating: ", "nps-computy" ) . $rating . '</p>
            <p>' . __( "What you need to do to evaluate 10: ", "nps-computy" ) . $problems . '</p>' );

        $reder = get_option( 'nps_option_name' );
        if (  $reder["nps-redirectpage"] !='') {
            echo '<meta http-equiv="refresh" content="0;'.$reder["nps-redirectpage"].'">';
        }

        function set_html_content_type(): string
        {
            return 'text/html';
        }

        $r = get_option( 'nps_option_name' );

        $thnk10 = $r["nps-thank"] ?? _e( "Thank you for rating! We will consider your wishes!  ", "nps-computy" );
        $thnk9 =  $r["nps-thank10"] ?? _e( "Evaluation is accepted! Thank you for the highest score!", "nps-computy" );



        if ( $rating < '10' ) {
            echo $thnk10;
        } else {
            echo $thnk9;
        }
        die();
    }
}