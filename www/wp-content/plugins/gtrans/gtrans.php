<?php
/*
Plugin Name: gtrans
Description: Google Translate Element
Version: 1.0.17
Author: GTranslate

*/

/*  Copyright 2011 GTranslate  (email : info [] gtranslate.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//require_once 'plugin-updates/plugin-update-checker.php';
//$MyUpdateChecker = new PluginUpdateChecker('http://joomla-gtranslate.googlecode.com/svn/trunk/wp_metadata.json', __FILE__, 'gtranslate');

add_action('widgets_init', array('gtrans', 'register'));
register_activation_hook(__FILE__, array('gtrans', 'activate'));
register_deactivation_hook(__FILE__, array('gtrans', 'deactivate'));
add_action('admin_menu', array('gtrans', 'admin_menu'));
add_shortcode('gtrans', array('gtrans', 'widget_code'));

class gtrans extends WP_Widget {
    function activate() {
        $data = array('gtrans_title' => 'Website Translator',);
        $data = get_option('gtrans');
        gtrans::load_defaults(& $data);

        add_option('gtrans', $data);
    }

    function deactivate() {}

    function control() {
        $data = get_option('gtrans');
        ?>
        <p><label>Title: <input name="gtrans_title" type="text" class="widefat" value="<?php echo $data['gtrans_title']; ?>"/></label></p>
        <p>Please go to Settings -> gtrans for configuration.</p>
        <?php
        if (isset($_POST['gtrans_title'])){
            $data['gtrans_title'] = attribute_escape($_POST['gtrans_title']);
            update_option('gtrans', $data);
        }
    }

    function widget($args) {
        $data = get_option('gtrans');
        gtrans::load_defaults(& $data);

        echo $args['before_widget'];
        echo $args['before_title'] . '<a href="http://gtranslate.net/?xyz=1108" rel="follow" target="_blank">' . $data['gtrans_title'] . '</a>' . $args['after_title'];
        echo self::widget_code();
        echo $args['after_widget'];
        echo '<noscript>JavaScript is required to use <a href="http://gtranslate.net/" title="Multilingual Website">GTranslate</a></noscript>';
    }

    function widget_code($atts = array()) {
        $data = get_option('gtrans');
        gtrans::load_defaults(& $data);
        $mixed_language = $data['mixed_language'] ? 'true' : 'false';

        $script = <<< EOM
<div id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: '{$data[default_language]}',
    autoDisplay: false,
    multilanguagePage: $mixed_language,
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script><script src="http://tdn.gtranslate.net/tdn-bin/queue.js"></script>
EOM;

        return $script;
    }

    function register() {
        wp_register_sidebar_widget('gtrans', 'gtrans', array('gtrans', 'widget'), array('description' => __('Google Translate Element')));
        wp_register_widget_control('gtrans', 'gtrans', array('gtrans', 'control'));
    }

    function admin_menu() {
        add_options_page('gtrans options', 'gtrans', 'administrator', 'gtrans_options', array('gtrans', 'options'));
    }

    function options() {
        ?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"><br/></div>
        <h2>GTranslate</h2>
        <?php
        if($_POST['save'])
            gtrans::control_options();
        $data = get_option('gtrans');
        gtrans::load_defaults(& $data);

        $site_url = site_url();

        extract($data);

?>
        <form id="gtrans" name="form1" method="post" action="<?php echo admin_url() . '/options-general.php?page=gtrans_options' ?>">
        <p>If you would like to have flags download and install <a href="http://gtranslate.net/features?p=wp&xyz=1108" target="_blank">GTranslate Free</a> from our website.<br /><img src="http://gtranslate.net/images/gtranslate_free_screenshot.jpg" alt="" title="GTranslate free screenshot"/></p>
        <p>If you would like to <b>edit translations manually</b> and have <b>SEF URLs</b> (<?php echo $site_url; ?><b>/es/</b>, <?php echo $site_url; ?><b>/fr/</b>, <?php echo $site_url; ?><b>/it/</b>, etc.) for translated languages or you want your <b>translated pages to be indexed</b> in search engines to <b>increase international traffic</b> you may consider <a href="http://gtranslate.net/features?p=wp&xyz=1108" target="_blank">GTranslate Pro</a> version.</p>
        <p>If you would like to use our next generation <b>cloud service</b> which will allow you to <b>host your languages</b> on top level country domain name (ccTLD) to <b>rank higher</b> on local search engines results you may consider <a href="http://gtranslate.net/features?p=wp&xyz=1108" target="_blank">GTranslate Enterprise</a> a <a href="http://gtranslate.net/translation-delivery-network" target="_blank">Translation Delivery Network</a>. In that case for example for Spanish you can have <b>es.domain.com</b> or <b>domain.es</b> if you own it.</p>
        <h4>Widget options</h4>
        <table style="font-size:11px;">
        <tr>
            <td class="option_name">Default language:</td>
            <td>
                <select id="default_language" name="default_language">
                    <option value="auto" <?php if($data['default_language'] == 'auto') echo 'selected'; ?>>Detect language</option>
                    <option value="af" <?php if($data['default_language'] == 'af') echo 'selected'; ?>>Afrikaans</option>
                    <option value="sq" <?php if($data['default_language'] == 'sq') echo 'selected'; ?>>Albanian</option>
                    <option value="ar" <?php if($data['default_language'] == 'ar') echo 'selected'; ?>>Arabic</option>
                    <option value="hy" <?php if($data['default_language'] == 'hy') echo 'selected'; ?>>Armenian</option>
                    <option value="az" <?php if($data['default_language'] == 'az') echo 'selected'; ?>>Azerbaijani</option>
                    <option value="eu" <?php if($data['default_language'] == 'eu') echo 'selected'; ?>>Basque</option>
                    <option value="be" <?php if($data['default_language'] == 'be') echo 'selected'; ?>>Belarusian</option>
                    <option value="bg" <?php if($data['default_language'] == 'bg') echo 'selected'; ?>>Bulgarian</option>
                    <option value="ca" <?php if($data['default_language'] == 'ca') echo 'selected'; ?>>Catalan</option>
                    <option value="zh-CN" <?php if($data['default_language'] == 'zh-CN') echo 'selected'; ?>>Chinese (Simplified)</option>
                    <option value="zh-TW" <?php if($data['default_language'] == 'zh-TW') echo 'selected'; ?>>Chinese (Traditional)</option>
                    <option value="hr" <?php if($data['default_language'] == 'hr') echo 'selected'; ?>>Croatian</option>
                    <option value="cs" <?php if($data['default_language'] == 'cs') echo 'selected'; ?>>Czech</option>
                    <option value="da" <?php if($data['default_language'] == 'da') echo 'selected'; ?>>Danish</option>
                    <option value="nl" <?php if($data['default_language'] == 'nl') echo 'selected'; ?>>Dutch</option>
                    <option value="en" <?php if($data['default_language'] == 'en') echo 'selected'; ?>>English</option>
                    <option value="et" <?php if($data['default_language'] == 'et') echo 'selected'; ?>>Estonian</option>
                    <option value="tl" <?php if($data['default_language'] == 'tl') echo 'selected'; ?>>Filipino</option>
                    <option value="fi" <?php if($data['default_language'] == 'fi') echo 'selected'; ?>>Finnish</option>
                    <option value="fr" <?php if($data['default_language'] == 'fr') echo 'selected'; ?>>French</option>
                    <option value="gl" <?php if($data['default_language'] == 'gl') echo 'selected'; ?>>Galician</option>
                    <option value="ka" <?php if($data['default_language'] == 'ka') echo 'selected'; ?>>Georgian</option>
                    <option value="de" <?php if($data['default_language'] == 'de') echo 'selected'; ?>>German</option>
                    <option value="el" <?php if($data['default_language'] == 'el') echo 'selected'; ?>>Greek</option>
                    <option value="ht" <?php if($data['default_language'] == 'ht') echo 'selected'; ?>>Haitian Creole</option>
                    <option value="iw" <?php if($data['default_language'] == 'iw') echo 'selected'; ?>>Hebrew</option>
                    <option value="hi" <?php if($data['default_language'] == 'hi') echo 'selected'; ?>>Hindi</option>
                    <option value="hu" <?php if($data['default_language'] == 'hu') echo 'selected'; ?>>Hungarian</option>
                    <option value="is" <?php if($data['default_language'] == 'is') echo 'selected'; ?>>Icelandic</option>
                    <option value="id" <?php if($data['default_language'] == 'id') echo 'selected'; ?>>Indonesian</option>
                    <option value="ga" <?php if($data['default_language'] == 'ga') echo 'selected'; ?>>Irish</option>
                    <option value="it" <?php if($data['default_language'] == 'it') echo 'selected'; ?>>Italian</option>
                    <option value="ja" <?php if($data['default_language'] == 'ja') echo 'selected'; ?>>Japanese</option>
                    <option value="ko" <?php if($data['default_language'] == 'ko') echo 'selected'; ?>>Korean</option>
                    <option value="lv" <?php if($data['default_language'] == 'lv') echo 'selected'; ?>>Latvian</option>
                    <option value="lt" <?php if($data['default_language'] == 'lt') echo 'selected'; ?>>Lithuanian</option>
                    <option value="mk" <?php if($data['default_language'] == 'mk') echo 'selected'; ?>>Macedonian</option>
                    <option value="ms" <?php if($data['default_language'] == 'ms') echo 'selected'; ?>>Malay</option>
                    <option value="mt" <?php if($data['default_language'] == 'mt') echo 'selected'; ?>>Maltese</option>
                    <option value="no" <?php if($data['default_language'] == 'no') echo 'selected'; ?>>Norwegian</option>
                    <option value="fa" <?php if($data['default_language'] == 'fa') echo 'selected'; ?>>Persian</option>
                    <option value="pl" <?php if($data['default_language'] == 'pl') echo 'selected'; ?>>Polish</option>
                    <option value="pt" <?php if($data['default_language'] == 'pt') echo 'selected'; ?>>Portuguese</option>
                    <option value="ro" <?php if($data['default_language'] == 'ro') echo 'selected'; ?>>Romanian</option>
                    <option value="ru" <?php if($data['default_language'] == 'ru') echo 'selected'; ?>>Russian</option>
                    <option value="sr" <?php if($data['default_language'] == 'sr') echo 'selected'; ?>>Serbian</option>
                    <option value="sk" <?php if($data['default_language'] == 'sk') echo 'selected'; ?>>Slovak</option>
                    <option value="sl" <?php if($data['default_language'] == 'sl') echo 'selected'; ?>>Slovenian</option>
                    <option value="es" <?php if($data['default_language'] == 'es') echo 'selected'; ?>>Spanish</option>
                    <option value="sw" <?php if($data['default_language'] == 'sw') echo 'selected'; ?>>Swahili</option>
                    <option value="sv" <?php if($data['default_language'] == 'sv') echo 'selected'; ?>>Swedish</option>
                    <option value="th" <?php if($data['default_language'] == 'th') echo 'selected'; ?>>Thai</option>
                    <option value="tr" <?php if($data['default_language'] == 'tr') echo 'selected'; ?>>Turkish</option>
                    <option value="uk" <?php if($data['default_language'] == 'uk') echo 'selected'; ?>>Ukrainian</option>
                    <option value="ur" <?php if($data['default_language'] == 'ur') echo 'selected'; ?>>Urdu</option>
                    <option value="vi" <?php if($data['default_language'] == 'vi') echo 'selected'; ?>>Vietnamese</option>
                    <option value="cy" <?php if($data['default_language'] == 'cy') echo 'selected'; ?>>Welsh</option>
                    <option value="yi" <?php if($data['default_language'] == 'yi') echo 'selected'; ?>>Yiddish</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="option_name">Mixed language content:</td>
            <td><input id="mixed_language" name="mixed_language" value="1" type="checkbox" <?php if($data['mixed_language']) echo 'checked'; ?> /></td>
        </tr>
        </table>

        <h4>Videos</h4>
        <iframe src="http://player.vimeo.com/video/30132555?title=1&amp;byline=0&amp;portrait=0" width="568" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
        <iframe src="http://player.vimeo.com/video/38686858?title=1&amp;byline=0&amp;portrait=0" width="568" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

        <?php wp_nonce_field('gtrans-save'); ?>
        <p class="submit"><input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes'); ?>" /></p>
        </form>
        </div>
        <?php
    }

    function control_options() {
        check_admin_referer('gtrans-save');

        $data = get_option('gtrans');

        $data['mixed_language'] = isset($_POST['mixed_language']) ? $_POST['mixed_language'] : '';
        $data['default_language'] = $_POST['default_language'];
        $data['incl_langs'] = $_POST['incl_langs'];

        echo '<p style="color:red;">Changes Saved</p>';
        update_option('gtrans', $data);
    }

    function load_defaults(& $data) {
        $data['mixed_language'] = isset($data['mixed_language']) ? $data['mixed_language'] : '';
        $data['default_language'] = isset($data['default_language']) ? $data['default_language'] : 'en';
        $data['incl_langs'] = isset($data['incl_langs']) ? $data['incl_langs'] : array();
    }
}