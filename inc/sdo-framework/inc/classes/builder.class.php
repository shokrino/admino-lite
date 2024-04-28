<?php defined( 'SHKOFPATH' ) || exit;
/**
 * Builder Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('SHKOF_Builder')) {
    class SHKOF_Builder {
        public function __construct() {}
        public static function logo($dev_name) {
            $settingsArray = SHKOF::$settings;
            foreach ($settingsArray as $dev_name => $settings) {
                $option = get_option($dev_name,NULL);
                if (is_null($option) or empty($option) or $option == []) {
                    SHKOF_Ajax_Handler::defaults($dev_name);
                }
            }
            $settings = $settingsArray[$dev_name];
            if (isset($settings)) {
                $logo_url = $settings["logo_url"];
                if (!empty($logo_url)) {
                    return $logo_url;
                }
            }
            return SHKOF_ASSETS . 'img/logo.png';
        }
        public static function title($title) {
            echo '<h1 class="shkof-header-title wp-heading-inline">'.$title.'</h1>';
        }
        public static function container_start() {
            echo '<div id="shkof" class="shkof-container wrap">';
        }
        public static function container_end() {
            echo '</div>';
        }
        public static function loading() {
            echo '<div class="loading-spinner-shkof"><svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="currentColor" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform
                            attributeName="transform"
                            attributeType="XML"
                            type="rotate"
                            dur="1s"
                            from="0 50 50"
                            to="360 50 50"
                            repeatCount="indefinite" />
                </path>
            </svg></div>';
        }
        public static function form_start() {
            echo '<form action="" method="POST" id="save-options-shkof" class="shkof-tab-content-outer">';
            echo '<div class="success-text"></div><div class="error-text"></div>';
        }
        public static function form_end($dev_name) {
            echo '<input type="hidden" name="dev_name" value="'.$dev_name.'">
                   <div class="shkof-options-save-box">
                <button type="submit" class="submit-shkof" name="save-shkof">'.__('save changes', SHKOF_TEXTDOMAIN).'</button>
            </div>
            </form>';
        }
        public static function section_start($class) {
            if (is_rtl()) {
                $class = $class.' rtl';
            }
            echo '<div class="shkof-section flex section-options-'.$class.'">';
        }
        public static function section_end() {
            echo '</div>';
        }
        public static function tab_start($dev_name,$version) {
            echo '<div class="tab">
            <div class="tab-information flex-center">
                <img src="'.self::logo($dev_name).'">
                <span>'.__('Version: ', SHKOF_TEXTDOMAIN).'<strong>'.$version.'</strong></span>
            </div>';
        }
        public static function tab_end() {
            echo '</div>';
        }
        public static function tab_buttons($dev_name) {
            $tabsArray = SHKOF::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) {
                    ?>
                    <button class="tablinks flex" onclick="openTabSHKOF(event, '<?php echo $tab['id']; ?>')">
                        <div class="shkof-tab-titles-box">
                            <div class="shkof-title-tab-options">
                                <?php echo $tab['title']; ?>
                            </div>
                            <div class="shkof-desc-tab-options">
                                <?php echo $tab['desc']; ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($tab['svg_logo'])) {
                            echo $tab['svg_logo'];
                        } else {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments-horizontal" width="30"
                                 height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#e2e2e2" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="14" cy="6" r="2"/>
                                <line x1="4" y1="6" x2="12" y2="6"/>
                                <line x1="16" y1="6" x2="20" y2="6"/>
                                <circle cx="8" cy="12" r="2"/>
                                <line x1="4" y1="12" x2="6" y2="12"/>
                                <line x1="10" y1="12" x2="20" y2="12"/>
                                <circle cx="17" cy="18" r="2"/>
                                <line x1="4" y1="18" x2="15" y2="18"/>
                                <line x1="19" y1="18" x2="20" y2="18"/>
                            </svg>';
                        } ?>
                    </button>
                    <?php
                }
            }
        }
        public static function form_fields($dev_name) {
            $tabsArray = SHKOF::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) { ?>
                    <div id="<?php echo $tab['id']; ?>" class="tabcontent">
                        <?php
                        $fields = $tab['fields'];
                        if (isset($fields) && is_array($fields)) {
                            foreach ($fields as $field) {
                                SHKOF_Builder::field_option($dev_name,$field);
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            }
        }
        public static function field_option($dev_name, $field, $repeater = false, $index = 0 ,$currentValue = "") {
            ?>
            <div id="container-<?php echo $field['id']."-".$index; ?>" class="shkof-box-option shkof-conditional-option"
                 display="true" <?php if ($repeater) { echo ' repeater-name="'.$field["id"].'"'; } ?>
                <?php if (is_array($field['require'])) { ?>
                    <?php foreach ($field['require'] as $index => $require) { ?>
                        data-require-<?php echo $index; ?>='<?php echo json_encode($require, JSON_UNESCAPED_UNICODE); ?>'
                    <?php } ?>
                <?php } ?>>
                <?php
                $type = $field['type'];
                $currentValue = !empty($currentValue) && $repeater ? $currentValue : shkof_option($dev_name, $field['id']);
                if (method_exists(__CLASS__, $type)) {
                    $index_repeater = $repeater ? "_".$index : "";
                    self::$type($dev_name, $field, $currentValue, $index_repeater);
                }
                ?>
            </div>
            <?php
        }
        public static function text($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="shkof-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <input type="text" class="shkof-input" id="<?php echo $name; ?>" name="<?php echo $name; ?>"
                   value="<?php echo $value; ?>">
            <p><?php echo $desc; ?></p>
            <?php
        }
        public static function textarea($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="shkof-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <textarea class="shkof-input" id="<?php echo $name; ?>" name="<?php echo $name; ?>">
                <?php echo $value; ?>
            </textarea>
            <p><?php echo $desc; ?></p>
            <?php
        }
        public static function tinymce($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="shkof-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <textarea id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="shkof-input"><?php echo $value; ?></textarea>
            <p><?php echo $desc; ?></p>
            <?php wp_enqueue_editor(); ?>
            <script>
                jQuery(document).ready(function($){
                    wp.editor.initialize('<?php echo $name; ?>', {
                        tinymce: {
                            wpautop: true,
                            toolbar: 'formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | charmap | pastetext | removeformat | undo redo | wp_adv',
                            wpautop: true,
                            wrap_lines: true,
                            tabfocus_elements: ':prev,:next',
                            toolbar1: 'styleselect formatselect fontselect fontsizeselect',
                            toolbar2: 'cut copy paste | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | charmap | pastetext | removeformat | undo redo | wp_adv',
                            toolbar3: 'table | hr | wp_more | wp_page | wp_pagebreak | visualchars | visualblocks | code',
                            toolbar4: 'fullscreen | wp_adv | wp_fullscreen',
                        },
                        quicktags: {
                            buttons: "b,i,ul,ol,li,link,close"
                        }
                    });
                });
            </script>
            <?php
        }
        public static function image($dev_name, $field, $currentValue, $index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] . $index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="shkof-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <div class="shkof-box-image-field">
                <div class="inner-image-box-shkof">
                    <input type="text" class="shkof-input image-url" id="<?php echo $name; ?>" name="<?php echo $name; ?>"
                           value="<?php echo $value; ?>">
                    <input type="button" class="shkof-button upload-image-button" data-image-field="<?php echo $name; ?>" value="آپلود تصویر">
                    <p><?php echo $desc; ?></p>
                </div>
                <img id="<?php echo $name; ?>-preview" class="uploaded-image shkof-image-preview" src="<?php echo esc_url($value); ?>" style="max-width: 100%;">
            </div>
            <?php
        }
        public static function color($dev_name, $field, $current_value, $index = '') {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] . $index : '';
            $value = !empty($current_value) ? $current_value : '';
            ?>
            <label class="shkof-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <input type="color" class="shkof-color-selector" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
            <p><?php echo $desc; ?></p>
            <?php
        }
        public static function buttonset($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
            $value = !empty($currentValue) ? $currentValue : '';
            echo '<label class="shkof-form-label">' . $title . '</label>';
            echo '<div class="shkof-button-set-box flex">';

            foreach ($options as $key => $label) {
                $id = $name . '_' . $key;
                $checked = ($key == $value) ? 'checked' : '';
                echo '<input type="radio" class="shkof-radio button-set" id="' . $id . '" name="' . $name . '" value="' . esc_attr($key) . '" ' . $checked . '>';
                echo '<label class="shkof-button-label flex" for="'.$id.'">' . esc_html($label) . '</label>';
            }

            echo '</div>';
            echo '<p>' . $desc . '</p>';
        }

        public static function switcher($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = ($currentValue === "on") ? true : filter_var($currentValue, FILTER_VALIDATE_BOOLEAN);
            echo '<label class="shkof-form-label">' . $title . '</label>';
            echo '<div class="shkof-switch-box flex">';
            $id = $name;
            $checked = ($value == "on") ? 'checked' : '';
            echo '<input type="checkbox" class="shkof-switch-checkbox shkof-radio" id="' . $id . '" name="' . $name . '" ' . $checked . '>';
            echo '<label class="shkof-switch-label" for="' . $id . '"></label>';
            echo '</div>';
            echo '<p>' . $desc . '</p>';
        }
        public static function select($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
            echo '<label class="shkof-form-label" for="' . $name . '">' . $title . '</label>';
            echo '<select class="shkof-select" id="' . $name . '" name="' . $name . '">';

            foreach ($options as $key => $label) {
                $selected = ($currentValue == $key) ? 'selected' : '';
                echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
            }

            echo '</select>';
            echo '<p>' . $desc . '</p>';
        }
        public static function repeater($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $fields = !empty($field['fields']) && is_array($field['fields']) ? $field['fields'] : array();
            echo '<div class="shkof-repeater-field" data-repeater-name="' . $name . '">';
            echo '<label class="shkof-form-label">' . $title . '</label>';
            echo '<div class="shkof-repeater-container">';
            if (empty($currentValue)) {
                $currentValue = [array_fill_keys(array_column($fields, 'id'), '')];
            }
            foreach ($currentValue as $index => $item) {
                echo '<div class="shkof-repeater-item" data-item-index="' . $index . '">';
                echo '<div class="shkof-repeater-seperate-subfields">';
                foreach ($fields as $subfield) {
                    $fieldValue = $item[$subfield['id']];
                    self::field_option($dev_name, $subfield, true, $index ,$fieldValue);
                }
                echo '</div>';
                echo '<div class="shkof-repeater-seperate-subbuttons">';
                echo '<button class="shkof-remove-repeater-item"><svg width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 7h16" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                  <path d="M10 12l4 4m0 -4l-4 4" />
                </svg>حذف </button>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '<button class="shkof-add-repeater-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M12 5l0 14" />
                  <path d="M5 12l14 0" />
                </svg>
                </button>';
            echo '</div>';
            echo '<p>' . $desc . '</p>';
        }

        public static function checkCondition($value, $operator, $requiredValue) {
            switch ($operator) {
                case '=':
                    return $value == $requiredValue;
                case '!=':
                    return $value != $requiredValue;
                case '>':
                    return $value > $requiredValue;
                case '<':
                    return $value < $requiredValue;
                default:
                    return false;
            }
        }
    }
}
