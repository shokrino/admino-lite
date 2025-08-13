<?php defined( 'OPTNNO_PATH' ) || exit;
/**
 * Config OPTNNO framework
 * آموزش برنامه نویسی مشابه همین پلاگین در سایت شکرینو
 */
function admnl_options($field) {
    return optionino_get('admnl_option',$field);
}

OPTNNO::set_config('admnl_option',array(
    'dev_title' => 'تنظیمات ادمینو لایت',
    'dev_version' => ADMNL_VERSION,
    'logo_url' => ADMNL_ASSETS.'/img/admino-type.png',
    'dev_textdomain' => ADMNL_TEXTDOMAIN,
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
            'id' => 'admin_style_type',
            'type'    => 'buttonset',
            'title' => 'نوع استایل پیشخوان',
            'desc'    => 'کدام استایل برای پیشخوان شما لود شود',
            'options' => array(
                'shokrino' => 'استایل شکرینو',
                'dark' => 'استایل دارک',
            ),
            'default' => 'shokrino',
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


// --- امنیت ادمینو (همه‌ی تنظیمات امنیتی در یک تب) ---
OPTNNO::set_tab('admnl_option', array(
  'id'    => 'security_hub',
  'title' => 'امنیت ادمینو',
  'desc'  => 'تنظیمات امنیتی ورود، حملات حدس رمز، کپچا و سخت‌سازی هسته.',
  'fields'=> array(
    // === Login URL ===
    array('id'=>'login_url_section_note','type'=>'textarea','title'=>'یادداشتِ بخش مسیر ورود','desc'=>'راهنما برای تغییر اسلاگ ورود','default'=>"پس از تغییر اسلاگ ورود، حتماً یکبار «Flush Rewrite Rules» بزنید یا افزونه را خاموش/روشن کنید."),
    array('id'=>'login_url_slug','type'=>'text','title'=>'اسلاگ جدید ورود','desc'=>'مثال: my-login (فقط حروف/عدد/خط‌تیره)','default'=>'my-login'),
    array('id'=>'login_url_block','type'=>'switcher','title'=>'مسدودکردن wp-login.php مستقیم','desc'=>'درخواست مستقیم به wp-login.php را 404 کن (به‌جز logout/AJAX/lostpassword/secret).','default'=>true),
    array('id'=>'login_url_lost','type'=>'switcher','title'=>'اجازه بازیابی رمز','desc'=>'اگر فعال باشد، مسیر lostpassword قابل‌دسترس است.','default'=>true),
    array('id'=>'login_url_secret','type'=>'text','title'=>'رمز اضطراری (?secret=...)','desc'=>'رشته‌ای برای دسترسی اضطراری به wp-login.php. خالی بماند تا غیرفعال شود.','default'=>''),

    // === Brute-force ===
    array('id'=>'bruteforce_section_note','type'=>'textarea','title'=>'یادداشتِ بخش محدودیت تلاش','desc'=>'راهنمای کوتاه تنظیمات','default'=>"پس از رسیدن به سقف تلاش در بازه زمانی، IP به‌مدت مشخص قفل می‌شود."),
    array('id'=>'lp_enabled','type'=>'switcher','title'=>'فعال‌سازی محدودیت تلاش','desc'=>'فعال/غیرفعال کردن محافظت بروت‌فورس.','default'=>true),
    array('id'=>'lp_max','type'=>'number','title'=>'حداکثر تلاش مجاز','desc'=>'تعداد تلاش ناموفق پیش از قفل.','default'=>5),
    array('id'=>'lp_window','type'=>'number','title'=>'بازه زمانی (دقیقه)','desc'=>'مدت زمان شمارش تلاش‌ها.','default'=>15),
    array('id'=>'lp_lock','type'=>'number','title'=>'مدت قفل (دقیقه)','desc'=>'مدت زمان بلاک پس از سقف تلاش.','default'=>30),
    array('id'=>'lp_notify','type'=>'switcher','title'=>'اعلان به مدیر هنگام قفل','desc'=>'در صورت نیاز برای توسعه بعدی اعلان ایمیلی تنظیم کنید.','default'=>false),

    // === CAPTCHA ===
    array('id'=>'captcha_section_note','type'=>'textarea','title'=>'یادداشتِ بخش CAPTCHA','desc'=>'راهنمای انتخاب سرویس','default'=>"Cloudflare Turnstile رایگان و بدون ردیابی است؛ reCAPTCHA گوگل نیاز به کلید دارد."),
    array('id'=>'captcha_provider','type'=>'select','title'=>'سرویس کپچا','desc'=>'سرویس موردنظر را انتخاب کنید.','options'=>array(
      'none'=>'بدون کپچا','turnstile'=>'Cloudflare Turnstile','recaptcha'=>'Google reCAPTCHA'
    ), 'default'=>'none'),
    array('id'=>'captcha_site','type'=>'text','title'=>'Site Key','desc'=>'Site Key ارائه‌شده توسط سرویس انتخابی.','default'=>''),
    array('id'=>'captcha_secret','type'=>'text','title'=>'Secret Key','desc'=>'Secret Key ارائه‌شده توسط سرویس انتخابی.','default'=>''),
    array('id'=>'captcha_on_login','type'=>'switcher','title'=>'فعال در ورود','desc'=>'نمایش کپچا در فرم ورود.','default'=>true),
    array('id'=>'captcha_on_register','type'=>'switcher','title'=>'فعال در ثبت‌نام','desc'=>'نمایش کپچا در فرم ثبت‌نام.','default'=>false),
    array('id'=>'captcha_on_lost','type'=>'switcher','title'=>'فعال در فراموشی رمز','desc'=>'نمایش کپچا در فرم بازیابی رمز.','default'=>false),

    // === Hardening ===
    array('id'=>'hardening_section_note','type'=>'textarea','title'=>'یادداشتِ بخش سخت‌سازی','desc'=>'راهنمای کوتاه سخت‌سازی','default'=>"غیرفعالسازی و خروجی‌های غیرضروری امنیت پنل را بهبود می‌دهد."),
    array('id'=>'hd_file_edit','type'=>'switcher','title'=>'غیرفعال‌سازی ویرایشگر فایل','desc'=>'ممانعت از ویرایش افزونه/قالب از پیشخوان.','default'=>true),
    array('id'=>'hd_xmlrpc','type'=>'switcher','title'=>'غیرفعال‌سازی XML-RPC','desc'=>'بستن سرویس XML-RPC برای کاهش برد حمله.','default'=>true),
    array('id'=>'hd_rest_users','type'=>'switcher','title'=>'بستن کاربران REST برای مهمان','desc'=>'مسیر /wp/v2/users برای کاربران غیرلاگین بسته شود.','default'=>true),
    array('id'=>'hd_hide_version','type'=>'switcher','title'=>'حذف نسخه وردپرس','desc'=>'مخفی‌کردن خروجی meta generator.','default'=>true),
    array('id'=>'hd_disallow_mods','type'=>'switcher','title'=>'ممانعت نصب/بروزرسانی افزونه/قالب','desc'=>'تعریف DISALLOW_FILE_MODS (با احتیاط استفاده شود).','default'=>false),
  ),
));

// --- سفارشی‌سازی (وایت‌لیبل + ادیت منو + ستون‌ها) ---
OPTNNO::set_tab('admnl_option', array(
  'id'    => 'customization_hub',
  'title' => 'سفارشی‌سازی',
  'desc'  => 'برندسازی پنل، ویرایش منو و تعریف ستون‌های لیست‌ها.',
  'fields'=> array(
    // === White Label ===
    array('id'=>'wl_section_note','type'=>'textarea','title'=>'یادداشتِ بخش برندسازی','desc'=>'راهنمای کوتاه','default'=>"لوگوها و نوشته فوتر را تنظیم کنید. گزینه‌های Help/Screen Options و ویجت‌های اضافی داشبورد را مخفی کنید."),
    array('id'=>'wl_login_logo','type'=>'image','title'=>'لوگوی صفحه ورود','desc'=>'تصویر لوگو برای فرم ورود (پیشنهاد: سبک و با ابعاد مناسب).','default'=>ADMNL_ASSETS.'/img/admino-type.png'),
    array('id'=>'wl_brand_16','type'=>'image','title'=>'آیکون نوار بالا (16×16)','desc'=>'لوگوی کوچک برای Admin Bar.','default'=>''),
    array('id'=>'wl_footer','type'=>'textarea','title'=>'متن فوتر پیشخوان','desc'=>'متن دلخواه برای فوتر پیشخوان (HTML ساده مجاز).','default'=>''),
    array('id'=>'wl_hide_help','type'=>'switcher','title'=>'مخفی‌کردن Help','desc'=>'دکمه Help در بالای صفحات مدیریت مخفی شود.','default'=>true),
    array('id'=>'wl_hide_screen','type'=>'switcher','title'=>'مخفی‌کردن Screen Options','desc'=>'دکمه Screen Options در بالای صفحات مدیریت مخفی شود.','default'=>true),
    array('id'=>'wl_clean_dash','type'=>'switcher','title'=>'تمیزکردن داشبورد','desc'=>'ویجت‌های پیش‌فرض اضافی داشبورد حذف شوند.','default'=>true),
    array('id'=>'wl_hide_wp','type'=>'switcher','title'=>'مخفی‌کردن برند وردپرس','desc'=>'آیکون و برند WP از Admin Bar حذف شود.','default'=>true),

    // === Menu Editor (JSON) ===
    array('id'=>'menu_section_note','type'=>'textarea','title'=>'یادداشتِ بخش ویرایش منو','desc'=>'راهنمای قالب JSON','default'=>"ساختار JSON شامل «order, items, subs» است. اگر آیتم/اسلاگ وجود نداشت، نادیده گرفته می‌شود."),
    array('id'=>'menu_profile_json','type'=>'textarea','title'=>'پروفایل منو (JSON)','desc'=>'ساختار: {"order":[..],"items":{..},"subs":{..}}. نمونهٔ ساده پیش‌فرض قرار داده شده.','default'=>wp_json_encode(array(
      'order'=>array('index.php','edit.php','upload.php'),
      'items'=>new stdClass(),
      'subs'=>new stdClass()
    ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)),

    // === Columns (JSON) ===
    array('id'=>'columns_section_note','type'=>'textarea','title'=>'یادداشتِ بخش ستون‌ها','desc'=>'راهنمای قالب JSON','default'=>"برای هر پست‌تایپ/تاکسونومی ستون‌ها را تعریف کنید. نوع‌هایی مثل meta/acf/taxonomy/author/date/title پشتیبانی می‌شوند."),
    array('id'=>'columns_json','type'=>'textarea','title'=>'ستون‌ها (JSON)','desc'=>'ساختار: {"posts":{"post":[...]}, "tax":{"product_cat":[...]}}','default'=>wp_json_encode(array(
      'posts'=>new stdClass(),
      'tax'=>new stdClass(),
    ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)),
  ),
));


add_action('admin_init', function () {
    // Login URL -> admino_login_url
    $slug   = admnl_options('login_url_slug');
    $block  = admnl_options('login_url_block') ? 1 : 0;
    $lost   = admnl_options('login_url_lost') ? 1 : 0;
    $secret = (string) admnl_options('login_url_secret');
    if ($slug !== null) {
        update_option('admino_login_url', wp_json_encode([
            'slug' => sanitize_title($slug ?: 'my-login'),
            'block_direct' => $block,
            'allow_lostpassword' => $lost,
            'secret_fallback' => $secret,
        ]));
    }

    // Brute-force -> admino_login_protect
    $lp_enabled = admnl_options('lp_enabled');
    if ($lp_enabled !== null) {
        update_option('admino_login_protect', wp_json_encode([
            'enabled' => $lp_enabled ? 1 : 0,
            'max_attempts' => max(1, intval(admnl_options('lp_max') ?: 5)),
            'window_minutes' => max(1, intval(admnl_options('lp_window') ?: 15)),
            'lock_minutes' => max(1, intval(admnl_options('lp_lock') ?: 30)),
            'notify_admin' => admnl_options('lp_notify') ? 1 : 0,
        ]));
    }

    // CAPTCHA -> admino_captcha
    $prov = admnl_options('captcha_provider');
    if ($prov !== null) {
        $on = array();
        if (admnl_options('captcha_on_login'))    $on[] = 'login';
        if (admnl_options('captcha_on_register')) $on[] = 'register';
        if (admnl_options('captcha_on_lost'))     $on[] = 'lostpassword';
        update_option('admino_captcha', wp_json_encode([
            'provider' => in_array($prov, ['turnstile','recaptcha'], true) ? $prov : 'none',
            'site_key' => (string) admnl_options('captcha_site'),
            'secret_key' => (string) admnl_options('captcha_secret'),
            'on' => $on,
        ]));
    }

    // Hardening -> admino_hardening
    $hd_file = admnl_options('hd_file_edit');
    if ($hd_file !== null) {
        update_option('admino_hardening', wp_json_encode([
            'disable_file_edit' => $hd_file ? 1 : 0,
            'disable_xmlrpc' => admnl_options('hd_xmlrpc') ? 1 : 0,
            'rest_users_block_anon' => admnl_options('hd_rest_users') ? 1 : 0,
            'remove_wp_version' => admnl_options('hd_hide_version') ? 1 : 0,
            'disallow_plugin_theme_install' => admnl_options('hd_disallow_mods') ? 1 : 0,
        ]));
    }

    // White Label -> admino_whitelabel
    $wl_login = admnl_options('wl_login_logo');
    if ($wl_login !== null) {
        update_option('admino_whitelabel', wp_json_encode([
            'login_logo' => (string) $wl_login,
            'brand_logo_small' => (string) admnl_options('wl_brand_16'),
            'footer_text' => (string) admnl_options('wl_footer'),
            'hide_help' => admnl_options('wl_hide_help') ? 1 : 0,
            'hide_screen_options' => admnl_options('wl_hide_screen') ? 1 : 0,
            'clean_dashboard' => admnl_options('wl_clean_dash') ? 1 : 0,
            'hide_wp_branding' => admnl_options('wl_hide_wp') ? 1 : 0,
        ]));
    }

    // Menu Editor JSON -> admino_menu_profile_default
    $menu_json = admnl_options('menu_profile_json');
    if ($menu_json !== null) {
        $arr = json_decode((string) $menu_json, true);
        if (is_array($arr)) update_option('admino_menu_profile_default', wp_json_encode($arr));
    }

    // Columns JSON -> admino_columns
    $cols_json = admnl_options('columns_json');
    if ($cols_json !== null) {
        $arr = json_decode((string) $cols_json, true);
        if (is_array($arr)) update_option('admino_columns', wp_json_encode($arr));
    }
});


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
