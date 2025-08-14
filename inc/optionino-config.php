<?php defined( 'OPTNNO_PATH' ) || exit;
/**
 * Config OPTNNO framework
 * آموزش برنامه نویسی مشابه همین پلاگین در سایت شکرینو
 */
function admnl_options($field) {
    return optionino_get('admnl_option',$field);
}

OPTNNO::set_config('admnl_option',array(
    'dev_title' => 'تنظیمات ادمینو',
    'dev_version' => ADMNL_VERSION,
    'logo_url' => ADMNL_ASSETS.'/img/admino-type.png',
    'dev_textdomain' => ADMNL_TEXTDOMAIN,
    'menu_type' => 'menu',
    'menu_title' => 'ادمینو لایت',
    'page_title' => 'ادمینو',
    'page_capability' => 'manage_options',
    'page_slug' => 'admnl_option'.'_settings',
    'icon_url' => admnl_options('admin_style') !== "off" ? ADMNL_ASSETS.'/img/admino-19px.png' : '',
    'menu_priority' => 99,
    'admin_bar' => false,
    'admin_bar_priority' => null,
    'admin_bar_icon' => 'dashicons-menu',
));

OPTNNO::set_tab('admnl_option',array(
    'id' => 'general_settings',
    'title' => 'تنظیمات عمومی',
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
            'id'      => 'admin_style',
            'type'    => 'switcher',
            'title'   => 'نمایش استایل جذاب پیشخوان',
            'desc'    => 'استایل جدید در پیشخوان نمایش داده شود یا غیرفعال باشد',
            'default' => true,
        ),
        array(
            'id'      => 'admin_style_type',
            'type'    => 'buttonset',
            'title'   => 'نوع استایل پیشخوان',
            'desc'    => 'کدام استایل برای پیشخوان شما لود شود',
            'options' => array(
                'shokrino' => 'استایل شکرینو',
                'dark'     => 'استایل دارک',
            ),
            'default' => 'shokrino',
            'require' => array(
                array('admin_style', '=', true),
            ),
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
            'require' => array(
                array('login_style', '=', true),
            ),
        ),
        array(
            'id'      => 'admin_bg_login_type',
            'type'    => 'buttonset',
            'title'   => 'نوع بک گراند صفحه ورود',
            'desc'    => 'کدام استایل برای بک گراند لود شود',
            'options' => array(
                'image' => 'عکس بک گراند',
                'color'     => 'رنگ ثابت',
            ),
            'default' => 'color',
        ),
        array(
            'id' => 'admin_bg',
            'type' => 'image',
            'title' => 'عکس زمینه صفحه ورود',
            'desc' => 'زمینه ای که در بخش ورود به پیشخوان نمایش داده میشود را انتخاب کنید. نسبت تصویر 16:9 هست و لطفا از تصاویر با حجم کمتر از 500 کیلوبایت استفاده نمایید',
            'default' => ADMNL_ASSETS.'/img/background.webp',
            'require' => array('admin_bg_login_type', '=', 'image'),
        ),
        array(
            'id' => 'admin_bg_color',
            'type' => 'color',
            'title' => 'رنگ زمینه صفحه ورود',
            'desc' => 'زمینه صفحه ورود بصورت یکپارچه به این رنگ خواهد بود',
            'default' => '#fff',
            'require' => array('admin_bg_login_type', '=', 'color'),
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
OPTNNO::set_tab('admnl_option', array(
  'id'    => 'branding_tab',
  'title' => 'شخصی‌سازی عملکرد',
  'desc'  => 'تغییر ویرایشگر و مخفی سازی لوگو ها و کلید های پیشخوان',
  'svg_logo' => '<svg
    xmlns="http://www.w3.org/2000/svg"
    width="32"
    height="32"
    viewBox="0 0 24 24"
    fill="none"
    stroke="#000000"
    stroke-width="1.5"
    stroke-linecap="round"
    stroke-linejoin="round"
    >
    <path d="M9.785 6l8.215 8.215l-2.054 2.054a5.81 5.81 0 1 1 -8.215 -8.215l2.054 -2.054z" />
    <path d="M4 20l3.5 -3.5" />
    <path d="M15 4l-3.5 3.5" />
    <path d="M20 9l-3.5 3.5" />
    </svg>',
  'fields'=> array(
    array(
      'id'      => 'classic_editor',
      'type'    => 'switcher',
      'title'   => 'نمایش ویرایشگر کلاسیک',
      'desc'    => 'بجای ویرایشگر بلوک از ویرایشگر کلاسیک برای نوشتن توضیحات و سایر موارد برای نوشته ها و برگه ها و ... استفاده شود.',
      'default' => true,
    ),
    array(
      'id'      => 'wl_hide_wp',
      'type'    => 'switcher',
      'title'   => 'حذف لوگوی وردپرس از نوار بالا',
      'desc'    => 'wp-logo در admin bar حذف می‌شود.',
      'default' => true,
    ),
    array(
      'id'      => 'wl_add_logo',
      'type'    => 'switcher',
      'title'   => 'اضافه کردن لوگو شما به نوار بالا',
      'desc'    => 'میتوانید لوگو خودتون به همراه لینک دلخواه به نوار ادمین اضافه کنید',
      'default' => false,
    ),
    array(
      'id'      => 'wl_brand_16',
      'type'    => 'image',
      'title'   => 'آیکن کوچک نوار بالا (16x16)',
      'desc'    => 'در admin bar نمایش داده می‌شود.',
      'default' => '',
      'require' => array(
            array('wl_add_logo', '=', true),
        ),
    ),
    array(
      'id'      => 'wl_brand_url',
      'type'    => 'text',
      'title'   => 'لینک آیکون شما در نوار بالا',
      'desc'    => 'لطفا لینک کامل با https وارد کنید',
      'default' => site_url(),
      'require' => array(
            array('wl_add_logo', '=', true),
        ),
    ),
    array(
      'id'      => 'wl_hide_help',
      'type'    => 'switcher',
      'title'   => 'مخفی‌کردن کلید "راهنما"',
      'desc'    => 'دکمه‌ی Help در بالای صفحات ادمین (گوشه سمت چپ) مخفی می‌شود.',
      'default' => true,
    ),
    array(
      'id'      => 'wl_hide_screen_options',
      'type'    => 'switcher',
      'title'   => 'مخفی‌کردن کلید "تنظیمات صفحه"',
      'desc'    => 'دکمه‌ی Screen Options  در بالای صفحات ادمین (گوشه بالا سمت چپ) مخفی می‌شود.',
      'default' => false,
    ),
  ),
));


function admnl_add_admin_notice() {
    if (isset($_GET['page']) && $_GET['page'] === 'admnl_option_settings') {
        ?>
        <div class="notice notice-info">
            <h2>آپدیت ادمینو</h2>
            <p>پیشنهادات خودتون برای ارتقا ادمینو را در پیج اعلام کنید تا در آپدیت ها اضافه شود</p>
            <p>برای دریافت نسخه های جدیدتر این افزونه پیج اینستاگرام شکرینو را فالو داشته باشید (@shokrino.wp)</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'admnl_add_admin_notice');

function admnl_add_admin_notice2() {
    if (isset($_GET['page']) && $_GET['page'] === 'admnl_option_settings') {
        ?>
        <div class="notice notice-info is-dismissible">
            <h2>آموزش رایگان طراحی سایت و برنامه نویسی قالب و افزونه وردپرسی </h2>
            <a href="https://shokrino.com">در شکرینو آکادمی</a>
        </div>
        <?php
    }
}
add_action('admin_notices', 'admnl_add_admin_notice2');
