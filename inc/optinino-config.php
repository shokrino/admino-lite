<?php defined( 'ABSPATH' ) || exit;
/**
 * Config Optionino framework
 * آموزش برنامه نویسی مشابه همین پلاگین در سایت شکرینو
 */
function admnl_options($field) {
    return optionino_get('admnl_option',$field);
}
define('OPTNNO_TEXTDOMAIN', ADMNL_TEXTDOMAIN);

OPTNNO::set_config('admnl_option',array(
    'dev_title' => 'تنظیمات ادمینو لایت',
    'dev_version' => ADMNL_VERSION,
    'logo_url' => ADMNL_ASSETS.'/img/admino-type.png',
    'dev_textdomain' > ADMNL_TEXTDOMAIN,
    'menu_type' => 'menu',
    'menu_title' => 'ادمینو لایت',
    'page_title' => 'ادمینو لایت',
    'page_capability' => 'manage_options',
    'page_slug' => 'admnl_option'.'_settings',
    'icon_url' => admnl_options('admin_style') !== "off" ? ADMNL_ASSETS.'/img/admino.png' : '',
    'menu_priority' => 99,
    'admin_bar' => false,
    'admin_bar_priority' => null,
    'admin_bar_icon' => 'dashicons-menu',
));

OPTNNO::set_tab('admnl_option',array(
    'id' => 'general_settings',
    'title' => 'تنظمیات عمومی',
    'desc' => 'آپشن هایی که در تمامی بخش ها مشترک هستند',
    'fields' => array(
        array(
            'id' => 'persian_font',
            'type' => 'switcher',
            'title' => 'نمایش فونت فارسی',
            'desc' => 'فونت های فارسی در پیشخوان نمایش داده شوند یا غیرفعال باشد',
            'default' => true,
        ),
        array(
            'id' => 'admin_style',
            'type' => 'switcher',
            'title' => 'نمایش استایل جذاب پیشخوان',
            'desc' => 'استایل جدید در پیشخوان نمایش داده شود یا غیرفعال باشد',
            'default' => true,
        ),
        array(
            'id' => 'login_style',
            'type' => 'switcher',
            'title' => 'نمایش استایل جذاب در صفحه ورود',
            'desc' => 'استایل جدید در صفحه ورود نمایش داده شود یا غیرفعال باشد',
            'default' => true,
        ),
        array(
            'id' => 'admin_logo',
            'type' => 'image',
            'title' => 'لوگوی صفحه ورود',
            'desc' => 'لوگویی که در بخش ورود به پیشخوان نمایش داده میشود را انتخاب کنید',
            'default' => ADMNL_ASSETS.'/img/admino-type.png',
        ),
        array(
            'id' => 'admin_bg',
            'type' => 'image',
            'title' => 'عکس زمینه صفحه ورود',
            'desc' => 'زمینه ای که در بخش ورود به پیشخوان نمایش داده میشود را انتخاب کنید. نسبت تصویر 16:9 هست و لطفا از تصاویر با حجم کمتر از 500 کیلوبایت استفاده نمایید',
            'default' => ADMNL_ASSETS.'/img/background.webp',
        ),
        array(
            'id' => 'disable_backtoblog',
            'type' => 'switcher',
            'title' => 'نمایش کلید بازگشت به صفحه اصلی',
            'desc' => 'با خاموش کردن این گزینه کلید مورد نظر حذف خواهد شد',
            'default' => false,
        ),
        array(
            'id' => 'disable_signup_lostpassword',
            'type' => 'switcher',
            'title' => 'نمایش کلید فراموشی رمزعبور',
            'desc' => 'با خاموش کردن این گزینه کلید مورد نظر حذف خواهد شد',
            'default' => true,
        ),
        array(
            'id' => 'disable_wp_language_switcher',
            'type' => 'switcher',
            'title' => 'نمایش کلید تغییر زبان',
            'desc' => 'با خاموش کردن این گزینه کلید مورد نظر حذف خواهد شد',
            'default' => false,
        ),
        array(
            'id' => 'admin_color',
            'type' => 'color',
            'title' => 'رنگ اصلی',
            'desc' => 'از این رنگ در بخش های مختلف پنل پیشخوان استفاده خواهد شد',
            'default' => "#8b70cd",
        ),
    ),
));