<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style','hello-elementor-header-footer' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

//     تغییر فونت پیشخوان وردپرس     //
function change_admin_font() {
    echo '<style type="text/css">@font-face{font-family:"esfont";src:url("' . get_stylesheet_directory_uri() . '/font/esfont.ttf")}#wpwrap,#wpadminbar .ab-empty-item,#wpadminbar a.ab-item,#wpadminbar>#wp-toolbar span.ab-label,#wpadminbar>#wp-toolbar span.noticon,.rtl h1,.rtl h2,.rtl h3,.rtl h4,.rtl h5,.rtl h6,.media-toolbar-secondary,.media-frame.mode-grid .media-toolbar select,.details,.media-modal label{font-family:"esfont"}</style>';
}
add_action('admin_head', 'change_admin_font');

///// حذف پیشخوان برای کاربران غیر رمدیر /////
// هدایت کاربران غیر مدیر به صفحه اصلی سایت
function redirect_non_admin_users() {
    if (!current_user_can('administrator') && is_admin()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'redirect_non_admin_users');

// مخفی کردن نوار مدیریتی برای کاربران غیر مدیر
function hide_admin_bar_for_non_admin_users() {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_non_admin_users');
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
//
//
//
//
//
//
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********  ارسال پیام ارسال پیام ارسال پیام ارسال پیام ارسال پیام ارسال پیام ****************///////////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////
///////***************/////////////***********///////////////****************///////////////////*****************///////////////

// بخش فرم ثبت درخواست

// ایجاد شورت کد برای فرم
function display_custom_message_form_unique() {
    if (!is_user_logged_in()) {
        return 'لطفا برای دسترسی به فرم وارد شوید.';
    }

    // دریافت همه گروه‌های کاربری
    $roles = get_terms(array(
        'taxonomy' => 'user_groups',
        'hide_empty' => false,
    ));

    // دریافت همه کاربران
    $users = get_users(array('orderby' => 'display_name'));

    // دریافت دسته‌بندی‌های پیام
    $categories = get_terms(array(
        'taxonomy' => 'message_categories',
        'hide_empty' => false,
    ));

    $output = '
    <form id="myform" class="custom-message-form" method="post" enctype="multipart/form-data">
        <div class="form-group user-group-section">
            <h3>گروه کاربران</h3>
            <div class="role-options horizontal-radio">';

    foreach ($roles as $role) {
        $output .= sprintf(
            '<div class="radio-item">
                <input type="radio" name="user_role" id="role_%s" value="%s" class="role-radio">
                <label for="role_%s">%s</label>
            </div>',
            esc_attr($role->slug),
            esc_attr($role->slug),
            esc_attr($role->slug),
            esc_html($role->name)
        );
    }

    $output .= '</div>
        </div>

        <div class="form-group user-select-section">
            <h3>انتخاب کاربر</h3>
            <input type="text" id="user-search" class="user-search" placeholder="جستجوی کاربر...">
            <select name="selected_user" id="selected_user" class="user-select" required>
                <option value="">لطفا یک کاربر را انتخاب کنید</option>';

    foreach ($users as $user) {
        $user_groups = wp_get_object_terms($user->ID, 'user_groups', array('fields' => 'slugs'));
        $output .= sprintf(
            '<option value="%d" data-role="%s">%s (%s)</option>',
            $user->ID,
            esc_attr(implode(' ', $user_groups)),
            esc_html($user->display_name),
            esc_html($user->user_email)
        );
    }

    $output .= '
            </select>
        </div>

        <div class="form-group">
            <h3>عنوان پیام</h3>
            <input type="text" name="message_title" id="message_title" required>
        </div>

        <div class="form-group">
            <h3>دسته‌بندی پیام</h</select>
        </div>

        <div class="form-group">
            <h3>پیام</h3>';

    // اضافه کردن ویرایشگر Wysiwyg با قابلیت تغییر رنگ
    ob_start();
    wp_editor('', 'message', array(
        'media_buttons' => false,
        'textarea_rows' => 10,
        'teeny' => true,
        'quicktags' => true,
        'tinymce' => array(
            'toolbar1' => 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,forecolor,backcolor,undo,redo'
        )
    ));
    $output .= ob_get_clean();

    $output .= '
        </div>

        <div class="form-group attachments-group">
            <h3>اسناد پیوستی</h3>
            <div class="attachment-input">
                <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.pdf" class="attachment-files">
                <label for="attachments" class="file-select-button">انتخاب اسناد پیوستی</label>
                <div class="selected-files-list"></div>
            </div>
            <small class="file-hint">فرمت‌های مجاز: JPG, PNG, PDF - حداکثر حجم هر فایل: 20MB - حداکثر 3 فایل</small>
        </div>

        <div class="form-group">
            <input type="submit" value="ارسال درخواست من" class="submit-button">
            ' . wp_nonce_field('send_message_nonce', 'message_nonce', true, false) . '
        </div>
    </form>';

    $output .= '<style>
        .custom-message-form {
            width: 1300px;
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group h3 {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .horizontal-radio {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .radio-item {
            display: inline-flex;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .radio-item label {
            font-size: 18px;
            font-weight: bold;
            margin-right: 8px;
        }

        .user-group-section {
            background: #f0f8ff; /* رنگ متمایز */
            padding: 10px;
            border-radius: 8px;
        }

        .user-select-section {
            background: #f0f8ff; /* رنگ متمایز */
            padding: 10px;
            border-radius: 8px;
        }

        .user-search {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .user-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            height: 70px;
            overflow-y: auto;
        }

        .user-select option {
            padding: 8px;
            font-size: 16px;
        }

        .attachment-input {
            margin: 20px 0;
            text-align: center;
        }

        .attachment-files {
            display: none;
        }

        .file-select-button {
            display: inline-block;
            padding: 12px 25px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .file-select-button:hover {
            background: #e9ecef;
            border-color: #bbb;
        }

        .selected-files-list {
            margin-top: 15px;
            text-align: right;
        }

        .selected-files-list .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .file-item .remove-file {
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
            padding: 0 5px;
        }

        .file-hint {
            display: block;
            margin-top: 8px;
            color: #666;
            font-size: 0.9em;
            text-align: center.
        }

        .submit-button {
            background: #0073aa;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .submit-button:hover {
            background: #005177;
        }

        .submit-button:disabled {
            background: #ccc;
            cursor: not-allowed.
        }

        .loading-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 8px;
            z-index: 1000;
            text-align: center;
            font-size: 16px.
        }

        .wp-editor-container {
            border: 1px solid #ddd;
            border-radius: 4px.
        }

        .wp-editor-area {
            min-height: 200px.
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // جستجوی کاربران
            $("#user-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#selected_user option").filter(function() {
                    var matches = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(matches);
                });
            });

            $("#attachments").change(function(e) {
                var files = e.target.files;
                var fileList = $(".selected-files-list");
                fileList.empty();

                // محدود کردن به 3 فایل
                if (files.length > 3) {
                    alert("شما فقط می‌توانید 3 فایل انتخاب کنید.");
                    $(this).val("");
                    return;
                }

                // نمایش فایل‌های انتخاب شده
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var fileSize = (file.size / (1024 * 1024)).toFixed(2);

                    if (file.size > 20 * 1024 * 1024) {
                        alert("فایل " + file.name + " بزرگتر از 20 مگابایت است.");
                        $(this).val("");
                        fileList.empty();
                        return;
                    }

                    fileList.append(
                        `<div class="file-item">
                            <span>${file.name} (${fileSize} MB)</span>
                            <span class="remove-file" data-index="${i}">×</span>
                        </div>`
                    );
                }
            });

            // حذف فایل از لیست
            $(document).on("click", ".remove-file", function() {
                var fileInput = $("#attachments")[0];
                var index = $(this).data("index");
                var files = Array.from(fileInput.files);
                files.splice(index, 1);

                fileInput.value = "";
                $(".selected-files-list").empty();
                if (files.length > 0) {
                    const dt = new DataTransfer();
                    files.forEach(file => dt.items.add(file));
                    fileInput.files = dt.files;
                    $(fileInput).trigger("change");
                }
            });

            // فیلتر کاربران بر اساس گروه انتخابی
            $("input[name=\'user_role\']").change(function() {
                var selectedRole = $(this).val();
                $("#selected_user option").hide();
                $("#selected_user option[data-role*=\"" + selectedRole + "\"]").show();
                $("#selected_user").val("");
                $("#user-search").val("");
                $("#user-search, #selected_user, #message_title, #message_category, #attachments, .submit-button").prop("disabled", false);
            });

            $("#myform").on("submit", function(e) {
                e.preventDefault();

                // غیرفعال کردن دکمه ارسال
                var submitButton = $(this).find("input[type=submit]");
                submitButton.prop("disabled", true);

                // نمایش پیام در حال ارسال
                var loadingMessage = $("<div>").addClass("loading-message")
                    .text("پیام درحال ارسال، لطفا صبر کنید و صفحه را ترک نکنید...");
                $("body").append(loadingMessage);

                var formData = new FormData(this);
                formData.append("action", "handle_message_submission_unique");
                formData.append("message", tinymce.get("message").getContent());

                $.ajax({
                    url: ajaxurl, // WordPress global variable for admin-ajax.php
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        loadingMessage.remove();
                        if (response.success) {
                            alert("پیام با موفقیت ارسال شد");
                            $("#myform")[0].reset();
                            if (tinymce.get("message")) {
                                tinymce.get("message").setContent("");
                            }
                            $(".selected-files-list").empty();
                        } else {
                            alert("خطا: " + (response.data || "خطای نامشخص"));
                        }
                        submitButton.prop("disabled", false);
                    },
                    error: function(xhr, status, error) {
                        loadingMessage.remove();
                        alert("خطا در ارسال پیام: " + error);
                        submitButton.prop("disabled", false);
                    }
                });
            });
        });
    </script>';

    return $output;
}
add_shortcode('myform', 'display_custom_message_form_unique');


//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2
//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2
//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2
//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2
//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2//PART2
// ایجاد پست تایپ سفارشی برای پیام‌ها
function create_custom_post_type_messages() {
    $labels = array(
        'name' => 'سامانه پیام ها',
        'singular_name' => 'پیام',
        'menu_name' => 'سامانه پیام ها',
        'name_admin_bar' => 'پیام',
        'add_new' => 'افزودن جدید',
        'add_new_item' => 'افزودن پیام جدید',
        'new_item' => 'پیام جدید',
        'edit_item' => 'ویرایش پیام',
        'view_item' => 'مشاهده پیام',
        'all_items' => 'همه پیام‌ها',
        'search_items' => 'جستجوی پیام‌ها',
        'parent_item_colon' => 'پیام والد:',
        'not_found' => 'پیامی یافت نشد.',
        'not_found_in_trash' => 'پیامی در زباله‌دان یافت نشد.'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'messages'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
    );

    register_post_type('messages', $args);
}
add_action('init', 'create_custom_post_type_messages');

// ایجاد دسته‌بندی برای پیام‌ها
function create_message_categories_taxonomy() {
    $labels = array(
        'name' => 'دسته‌بندی پیام‌ها',
        'singular_name' => 'دسته‌بندی پیام',
        'search_items' => 'جستجوی دسته‌بندی‌ها',
        'all_items' => 'همه دسته‌بندی‌ها',
        'parent_item' => 'دسته‌بندی والد',
        'parent_item_colon' => 'دسته‌بندی والد:',
        'edit_item' => 'ویرایش دسته‌بندی',
        'update_item' => 'به‌روزرسانی دسته‌بندی',
        'add_new_item' => 'افزودن دسته‌بندی جدید',
        'new_item_name' => 'نام دسته‌بندی جدید',
        'menu_name' => 'دسته‌بندی‌ها',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'message-category'),
    );

    register_taxonomy('message_categories', array('messages'), $args);
}
add_action('init', 'create_message_categories_taxonomy', 0);

// تغییر سطح دسترسی برای ارسال پیام برای همه کاربران
function customize_user_capabilities() {
    $roles = array('administrator', 'editor', 'author', 'contributor', 'subscriber');
    
    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            $role->add_cap('publish_messages');
            $role->add_cap('edit_messages');
            $role->add_cap('delete_messages');
            $role->add_cap('read_messages');
        }
    }
}
add_action('init', 'customize_user_capabilities');

// ایجاد شورت‌کد برای نمایش پیام‌های ارسال‌شده
add_shortcode('sent_messages', 'display_sent_messages_shortcode');

function display_sent_messages_shortcode($user_id = null) {
    if (!is_user_logged_in()) {
        return 'لطفا برای مشاهده پیام‌های ارسال‌شده وارد شوید.';
    }

    $is_admin = current_user_can('administrator');
    $current_user_id = get_current_user_id();

    if ($is_admin && $user_id !== null) {
        $user_id = intval($user_id);
    } else {
        $user_id = $current_user_id;
    }

    $output = '<div id="message-list" class="sent-messages-container">';

    if ($is_admin) {
        $users = get_users(array('orderby' => 'display_name'));
        $output .= '<select id="user-select" class="user-select">
            <option value="">انتخاب کاربر برای مشاهده پیام‌ها</option>';
        foreach ($users as $user) {
            $output .= sprintf(
                '<option value="%d">%s (%s)</option>',
                $user->ID,
                esc_html($user->display_name),
                esc_html($user->user_email)
            );
        }
        $output .= '</select>';
    }

    $output .= '<input type="text" id="message-search" class="message-search" placeholder="جستجو بر اساس شناسه پیام...">
    <table class="sent-messages-table">
        <thead>
            <tr>
                <th class="column-recipient" style="width: 15%;">نام گیرنده</th>
                <th class="column-message" style="width: 50%;">شرح پیام</th>
                <th class="column-attachments" style="width: 15%;">اسناد پیوستی</th>
                <th class="column-date" style="width: 15%;">زمان ارسال</th>
                <th class="column-id" style="width: 5%;">شناسه</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="loading-message">در حال بارگذاری...</div>
</div>';

    // اضافه کردن استایل‌ها و اسکریپت‌ها
    $output .= '<style>
        .sent-messages-container {
            width: 100%;
            margin: 20px 0;
            overflow-x: auto;
        }

        .user-select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .message-search {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .sent-messages-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sent-messages-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }

        .sent-messages-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .recipient-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .group-name {
            font-size: 14px;
            color: #777;
        }

        .message-content {
            font-size: 15px;
            line-height: 1.5;
            color: #444;
        }

        .show-more-btn {
            display: inline-block;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #0073aa;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .show-more-btn:hover {
            background: #e9ecef;
            border-color: #0073aa;
            color: #005177;
        }

        .show-attachments-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #0073aa;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .show-attachments-btn:hover {
            background: #e9ecef;
            border-color: #0073aa;
            color: #005177;
        }

        .show-attachments-btn .fas {
            font-size: 20px;
        }

        .no-attachment {
            color: #666;
            font-style: italic;
            font-size: 15px;
        }

        .date-cell {
            font-size: 15px;
            color: #666;
        }

        .message-id {
            font-size: 15px;
            color: #666;
        }

        .no-messages {
            font-size: 16px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }

        .loading-message {
            display: none;
            text-align: center;
            font-size: 16px;
            color: #0073aa;
            padding: 20px;
        }
    </style>';

    $output .= '<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>';
    $output .= '<script>
        jQuery(document).ready(function($) {
            var page = 1;
            var loading = false;
            var hasMore = true;

            function loadMessages() {
                if (loading || !hasMore) return;
                loading = true;
                $(".loading-message").show();

                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "load_sent_messages",
                        page: page,
                        user_id: $("#user-select").val() || ' . $user_id . '
                    },
                    success: function(response) {
                        if (response.success) {
                            $(".sent-messages-table tbody").append(response.data);
                            if (response.data.trim() === "") {
                                hasMore = false;
                            }
                            page++;
                        } else {
                            hasMore = false;
                        }
                        $(".loading-message").hide();
                        loading = false;
                    }
                });
            }

            // بارگذاری پیام‌ها در ابتدا
            loadMessages();

            // بارگذاری پیام‌ها با اسکرول
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    loadMessages();
                }
            });

            // عملکرد دکمه ادامه پیام
            $(document).on("click", ".show-more-btn", function() {
                var $row = $(this).closest("tr");
                $(this).siblings(".continue-reading").toggle();
                $(this).text($(this).text() === "ادامه پیام" ? "بستن" : "ادامه پیام");
            });

            // عملکرد دکمه نمایش پیوست
            $(document).on("click", ".show-attachments-btn", function() {
                var url = $(this).data("url");
                window.open(url, "_blank");
            });

            // جستجو بر اساس شناسه پیام
            $("#message-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".sent-messages-table tbody tr").filter(function() {
                    $(this).toggle($(this).find(".message-id").text().toLowerCase().indexOf(value) > -1);
                });
            });

            // تغییر کاربر انتخاب شده برای مشاهده پیام‌ها
            $("#user-select").on("change", function() {
                page = 1;
                hasMore = true;
                $(".sent-messages-table tbody").empty();
                loadMessages();
            });
        });
    </script>';

    return $output;
}

// هندل کردن بارگذاری پیام‌های ارسال‌شده
add_action('wp_ajax_load_sent_messages', 'load_sent_messages');
add_action('wp_ajax_nopriv_load_sent_messages', 'load_sent_messages');

function load_sent_messages() {
    if (!is_user_logged_in()) {
        wp_send_json_error('دسترسی غیرمجاز');
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    global $wpdb;
    $table_name = $wpdb->prefix . 'sent_messages';
    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE sender_id = %d ORDER BY created_at DESC LIMIT %d, 10",
        $user_id,
        ($page - 1) * 10
    ));

    if ($messages) {
        ob_start();
        foreach ($messages as $message) {
            $recipient = get_userdata($message->receiver_id);
            $attachments = maybe_unserialize($message->attachments);
            $post_date = human_time_diff(strtotime($message->created_at), current_time('timestamp')) . ' قبل';
            $post_time = date('H:i', strtotime($message->created_at));
            $group_name = $recipient ? implode(', ', $recipient->roles) : 'بدون گروه';

            echo '<tr>
                <td class="recipient-name">
                    ' . get_avatar($message->receiver_id, 32) . '<br>
                    ' . esc_html($recipient->display_name) . '<br>
                    <span class="group-name">' . esc_html($group_name) . '</span>
                </td>
                <td class="message-content">
                    ' . wp_trim_words($message->message, 20) . '
                    <div class="continue-reading" style="display: none;">
                        ' . wp_kses_post($message->message) . '
                    </div>
                    <button class="show-more-btn">ادامه پیام</button>
                </td>
                <td class="attachments-cell">';

            if (!empty($attachments)) {
                foreach ($attachments as $attachment_id) {
                    $attachment_url = wp_get_attachment_url($attachment_id);
                    $file_type = wp_check_filetype($attachment_url);
                    $icon_class = 'fas fa-file';
                    if ($file_type['ext'] === 'pdf') {
                        $icon_class = 'fas fa-file-pdf';
                    } elseif (in_array($file_type['ext'], array('jpg', 'jpeg', 'png', 'gif'))) {
                        $icon_class = 'fas fa-file-image';
                    }

                    echo '<button class="show-attachments-btn" data-url="' . esc_url($attachment_url) . '">
                        <i class="' . $icon_class . '"></i>
                        نمایش پیوست
                    </button>';
                }
            } else {
                echo '<div class="no-attachment">این پیام پیوست ندارد</div>';
            }

            echo '</td>
                <td class="date-cell">ساعت: ' . $post_time . '<br>زمان: ' . $post_date . '</td>
                <td class="message-id">' . $message->id . '</td>
            </tr>';
        }
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_success('');
    }
}
//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3
//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3
//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3
//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3//PART3
// ایجاد شورت‌کد برای نمایش پیام‌های دریافتی
add_shortcode('received_messages', 'display_received_messages_shortcode');

function display_received_messages_shortcode($user_id = null) {
    if (!is_user_logged_in()) {
        return 'لطفا برای مشاهده پیام‌های دریافتی وارد شوید.';
    }

    $is_admin = current_user_can('administrator');
    $current_user_id = get_current_user_id();

    if ($is_admin && $user_id !== null) {
        $user_id = intval($user_id);
    } else {
        $user_id = $current_user_id;
    }

    $output = '<div id="message-list" class="received-messages-container">';

    if ($is_admin) {
        $users = get_users(array('orderby' => 'display_name'));
        $output .= '<select id="user-select" class="user-select">
            <option value="">انتخاب کاربر برای مشاهده پیام‌ها</option>';
        foreach ($users as $user) {
            $output .= sprintf(
                '<option value="%d">%s (%s)</option>',
                $user->ID,
                esc_html($user->display_name),
                esc_html($user->user_email)
            );
        }
        $output .= '</select>';
    }

    $output .= '<input type="text" id="message-search" class="message-search" placeholder="جستجو بر اساس شناسه پیام...">
    <table class="received-messages-table">
        <thead>
            <tr>
                <th class="column-sender" style="width: 15%;">نام فرستنده</th>
                <th class="column-message" style="width: 50%;">شرح پیام</th>
                <th class="column-attachments" style="width: 15%;">اسناد پیوستی</th>
                <th class="column-date" style="width: 15%;">زمان دریافت</th>
                <th class="column-id" style="width: 5%;">شناسه</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="loading-message">در حال بارگذاری...</div>
</div>';

    // اضافه کردن استایل‌ها و اسکریپت‌ها
    $output .= '<style>
        .received-messages-container {
            width: 100%;
            margin: 20px 0;
            overflow-x: auto;
        }

        .user-select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .message-search {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .received-messages-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .received-messages-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }

        .received-messages-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .sender-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .group-name {
            font-size: 14px;
            color: #777;
        }

        .message-content {
            font-size: 15px;
            line-height: 1.5;
            color: #444;
        }

        .show-more-btn {
            display: inline-block;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #0073aa;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .show-more-btn:hover {
            background: #e9ecef;
            border-color: #0073aa;
            color: #005177;
        }

        .show-attachments-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #0073aa;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .show-attachments-btn:hover {
            background: #e9ecef;
            border-color: #0073aa;
            color: #005177;
        }

        .show-attachments-btn .fas {
            font-size: 20px;
        }

        .no-attachment {
            color: #666;
            font-style: italic;
            font-size: 15px;
        }

        .date-cell {
            font-size: 15px;
            color: #666;
        }

        .message-id {
            font-size: 15px;
            color: #666;
        }

        .no-messages {
            font-size: 16px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }

        .loading-message {
            display: none;
            text-align: center;
            font-size: 16px;
            color: #0073aa;
            padding: 20px;
        }

        .read-message {
            background: #e9ecef;
        }
    </style>';

    $output .= '<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>';
    $output .= '<script>
        jQuery(document).ready(function($) {
            var page = 1;
            var loading = false;
            var hasMore = true;

            function loadMessages() {
                if (loading || !hasMore) return;
                loading = true;
                $(".loading-message").show();

                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "load_received_messages",
                        page: page,
                        user_id: $("#user-select").val() || ' . $user_id . '
                    },
                    success: function(response) {
                        if (response.success) {
                            $(".received-messages-table tbody").append(response.data);
                            if (response.data.trim() === "") {
                                hasMore = false;
                            }
                            page++;
                        } else {
                            hasMore = false;
                        }
                        $(".loading-message").hide();
                        loading = false;
                    }
                });
            }

            // بارگذاری پیام‌ها در ابتدا
            loadMessages();

            // بارگذاری پیام‌ها با اسکرول
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    loadMessages();
                }
            });

            // عملکرد دکمه ادامه پیام
            $(document).on("click", ".show-more-btn", function() {
                var $row = $(this).closest("tr");
                $(this).siblings(".continue-reading").toggle();
                $(this).text($(this).text() === "ادامه پیام" ? "بستن" : "ادامه پیام");
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "mark_message_as_read",
                        message_id: $row.find(".message-id").text()
                    },
                    success: function(response) {
                        if (response.success) {
                            $row.addClass("read-message");
                        }
                    }
                });
            });

            // عملکرد دکمه نمایش پیوست
            $(document).on("click", ".show-attachments-btn", function() {
                var url = $(this).data("url");
                window.open(url, "_blank");
            });

            // جستجو بر اساس شناسه پیام
            $("#message-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".received-messages-table tbody tr").filter(function() {
                    $(this).toggle($(this).find(".message-id").text().toLowerCase().indexOf(value) > -1);
                });
            });

            // تغییر کاربر انتخاب شده برای مشاهده پیام‌ها
            $("#user-select").on("change", function() {
                page = 1;
                hasMore = true;
                $(".received-messages-table tbody").empty();
                loadMessages();
            });
        });
    </script>';

    return $output;
}

// هندل کردن بارگذاری پیام‌های دریافتی
add_action('wp_ajax_load_received_messages', 'load_received_messages');
add_action('wp_ajax_nopriv_load_received_messages', 'load_received_messages');

function load_received_messages() {
    if (!is_user_logged_in()) {
        wp_send_json_error('دسترسی غیرمجاز');
    }
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $args = array(
        'post_type' => 'messages',
        'meta_query' => array(
            array(
                'key' => 'recipient_id',
                'value' => $user_id,
                'compare' => '='
            )
        ),
        'posts_per_page' => 10,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $messages = new WP_Query($args);

    if ($messages->have_posts()) {
        ob_start();
        while ($messages->have_posts()) {
            $messages->the_post();
            $sender_id = get_the_author_meta('ID');
            $sender = get_userdata($sender_id);
            $attachments = get_post_meta(get_the_ID(), 'attachments', true);
            $post_date = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' قبل';
            $post_time = get_the_time('H:i');
            $group_name = implode(', ', $sender->roles);
            $read_class = get_post_meta(get_the_ID(), 'message_read', true) ? 'read-message' : '';

            echo '<tr class="' . $read_class . '">
                <td class="sender-name">
                    ' . get_avatar($sender_id, 32) . '<br>
                    ' . esc_html($sender->display_name) . '<br>
                    <span class="group-name">' . esc_html($group_name) . '</span>
                </td>
                <td class="message-content">
                    ' . wp_trim_words(get_the_content(), 20) . '
                    <div class="continue-reading" style="display: none;">
                        ' . wp_kses_post(get_the_content()) . '
                    </div>
                    <button class="show-more-btn">ادامه پیام</button>
                </td>
                <td class="attachments-cell">';

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $file_type = wp_check_filetype($attachment['url']);
                    $icon_class = 'fas fa-file';
                    if ($file_type['ext'] === 'pdf') {
                        $icon_class = 'fas fa-file-pdf';
                    } elseif (in_array($file_type['ext'], array('jpg', 'jpeg', 'png', 'gif'))) {
                        $icon_class = 'fas fa-file-image';
                    }

                    echo '<button class="show-attachments-btn" data-url="' . esc_url($attachment['url']) . '">
                        <i class="' . $icon_class . '"></i>
                        نمایش پیوست
                    </button>';
                }
            } else {
                echo '<div class="no-attachment">این پیام پیوست ندارد</div>';
            }

            echo '</td>
                <td class="date-cell">ساعت: ' . $post_time . '<br>زمان: ' . $post_date . '</td>
                <td class="message-id">' . get_the_ID() . '</td>
            </tr>';
        }
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_success('');
    }
}

// هندل کردن علامت‌گذاری پیام به عنوان خوانده شده
add_action('wp_ajax_mark_message_as_read', 'mark_message_as_read');

function mark_message_as_read() {
    if (!is_user_logged_in()) {
        wp_send_json_error('دسترسی غیرمجاز');
    }

    $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
    if ($message_id) {
        update_post_meta($message_id, 'message_read', true);
        wp_send_json_success();
    } else {
        wp_send_json_error('شناسه پیام نامعتبر است');
    }
}
//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4
//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4
//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4
//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4//PART4
<?php
    $user_groups = get_terms(array('taxonomy' => 'user_groups', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1>مدیریت گروه‌های کاربری</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="group_name">نام گروه:</label></th>
                    <td><input type="text" name="group_name" id="group_name" required></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="add_group" class="button button-primary" value="افزودن گروه"></p>
        </form>
        <hr>
        <h2>گروه‌های موجود</h2>
        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th scope="col">نام گروه</th>
                    <th scope="col">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_groups as $group) : ?>
                    <tr>
                        <td><?php echo esc_html($group->name); ?></td>
                        <td>
                            <a href="<?php echo add_query_arg(array('delete' => $group->term_id)); ?>" class="button button-danger">حذف</a>
                            <button class="button edit-button" data-term-id="<?php echo esc_attr($group->term_id); ?>" data-term-name="<?php echo esc_attr($group->name); ?>">ویرایش</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- فرم ویرایش گروه -->
        <div id="edit-group-form" style="display:none;">
            <h2>ویرایش گروه</h2>
            <form method="post">
                <input type="hidden" name="term_id" id="edit_term_id">
                <table class="form-table">
                    <tr>
                        <th><label for="edit_group_name">نام گروه:</label></th>
                        <td><input type="text" name="group_name" id="edit_group_name" required></td>
                    </tr>
                </table>
                <p class="submit"><input type="submit" name="edit_group" class="button button-primary" value="ذخیره تغییرات"></p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.edit-button');
            var editForm = document.getElementById('edit-group-form');
            var editTermId = document.getElementById('edit_term_id');
            var editGroupName = document.getElementById('edit_group_name');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var termId = button.getAttribute('data-term-id');
                    var termName = button.getAttribute('data-term-name');
                    editTermId.value = termId;
                    editGroupName.value = termName;
                    editForm.style.display = 'block';
                });
            });
        });
    </script>
    <?php
}

// تابع نمایش صفحه مدیریت عضویت کاربران در گروه‌ها
function user_group_membership_page() {
    if (isset($_POST['assign_user_group'])) {
        $user_id = intval($_POST['user_id']);
        $group_id = intval($_POST['group_id']);
        if ($user_id && $group_id) {
            // حذف عضویت‌های قبلی کاربر
            wp_remove_object_terms($user_id, get_terms(array('taxonomy' => 'user_groups', 'hide_empty' => false)), 'user_groups');
            // افزودن کاربر به گروه جدید
            wp_set_object_terms($user_id, $group_id, 'user_groups', false);
        }
    }

    $users = get_users(array('orderby' => 'display_name'));
    $groups = get_terms(array('taxonomy' => 'user_groups', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1>مدیریت عضویت کاربران در گروه‌ها</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="user_id">انتخاب کاربر:</label></th>
                    <td>
                        <select name="user_id" id="user_id" required>
                            <option value="">انتخاب کاربر</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?php echo esc_attr($user->ID); ?>"><?php echo esc_html($user->display_name . ' (' . $user->user_email . ')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="group_id">انتخاب گروه:</label></th>
                    <td>
                        <select name="group_id" id="group_id" required>
                            <option value="">انتخاب گروه</option>
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?php echo esc_attr($group->term_id); ?>"><?php echo esc_html($group->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="assign_user_group" class="button button-primary" value="ثبت عضویت"></p>
        </form>
    </div>
    <?php
}

// تابع نمایش کاربران به تفکیک گروه
function user_group_view_page() {
    $groups = get_terms(array('taxonomy' => 'user_groups', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1>نمایش کاربران به تفکیک گروه</h1>
        <?php
        foreach ($groups as $group) {
            $users_in_group = get_users(array(
                'meta_query' => array(
                    array(
                        'key' => 'user_groups',
                        'value' => '"' . $group->term_id . '"',
                        'compare' => 'LIKE'
                    )
                )
            ));
            ?>
            <h2><?php echo esc_html($group->name); ?></h2>
            <ul>
                <?php
                if (!empty($users_in_group)) {
                    foreach ($users_in_group as $user) {
                        echo '<li>' . esc_html($user->display_name . ' (' . $user->user_email . ')') . '</li>';
                    }
                } else {
                    echo '<li>هیچ کاربری در این گروه عضو نیست.</li>';
                }
                ?>
            </ul>
            <?php
        }
        ?>
    </div>
    <?php
}
//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5
//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5
//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5
//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5//PART5
// سایر کدهای موجود در فایل functions.php

// ایجاد جداول سفارشی برای ذخیره پیام‌ها
function create_custom_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // جدول پیام‌های ارسال شده
    $table_name_sent = $wpdb->prefix . 'sent_messages';
    $sql_sent = "CREATE TABLE $table_name_sent (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        sender_id bigint(20) NOT NULL,
        receiver_id bigint(20) NOT NULL,
        message text NOT NULL,
        attachments longtext,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // جدول پیام‌های دریافت شده
    $table_name_received = $wpdb->prefix . 'received_messages';
    $sql_received = "CREATE TABLE $table_name_received (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        sender_id bigint(20) NOT NULL,
        receiver_id bigint(20) NOT NULL,
        message text NOT NULL,
        attachments longtext,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_sent);
    dbDelta($sql_received);
}
add_action('after_setup_theme', 'create_custom_tables');

if (!function_exists('display_custom_message_form_unique')) {
    // ایجاد شورت کد برای فرم
    function display_custom_message_form_unique() {
        if (!is_user_logged_in()) {
            return 'لطفا برای دسترسی به فرم وارد شوید.';
        }

        // دریافت همه گروه‌های کاربری
        $roles = get_terms(array(
            'taxonomy' => 'user_groups',
            'hide_empty' => false,
        ));

        // دریافت همه کاربران
        $users = get_users(array('orderby' => 'display_name'));

        $output = '
        <form id="myform" class="custom-message-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <h3>گروه کاربران</h3>
                <div class="role-options horizontal-radio">';

        foreach ($roles as $role) {
            $output .= sprintf(
                '<div class="radio-item">
                    <input type="radio" name="user_role" id="role_%s" value="%s" class="role-radio">
                    <label for="role_%s">%s</label>
                </div>',
                esc_attr($role->slug),
                esc_attr($role->slug),
                esc_attr($role->slug),
                esc_html($role->name)
            );
        }

        $output .= '</div>
            </div>

            <div class="form-group">
                <h3>انتخاب کاربر</h3>
                <input type="text" id="user-search" class="user-search" placeholder="جستجوی کاربر...">
                <select name="selected_user" id="selected_user" class="user-select" required>
                    <option value="">لطفا یک کاربر را انتخاب کنید</option>';

        foreach ($users as $user) {
            $user_groups = wp_get_object_terms($user->ID, 'user_groups', array('fields' => 'slugs'));
            $output .= sprintf(
                '<option value="%d" data-role="%s">%s (%s)</option>',
                $user->ID,
                esc_attr(implode(' ', $user_groups)),
                esc_html($user->display_name),
                esc_html($user->user_email)
            );
        }

        $output .= '
                </select>
            </div>

            <div class="form-group">
                <h3>پیام</h3>';

        // اضافه کردن ویرایشگر Wysiwyg با قابلیت تغییر رنگ
        ob_start();
        wp_editor('', 'message', array(
            'media_buttons' => false,
            'textarea_rows' => 10,
            'teeny' => true,
            'quicktags' => true,
            'tinymce' => array(
                'toolbar1' => 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,forecolor,backcolor,undo,redo'
            )
        ));
        $output .= ob_get_clean();

        $output .= '
            </div>

            <div class="form-group attachments-group">
                <h3>اسناد پیوستی</h3>
                <div class="attachment-input">
                    <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.pdf" class="attachment-files">
                    <label for="attachments" class="file-select-button">انتخاب اسناد پیوستی</label>
                    <div class="selected-files-list"></div>
                </div>
                <small class="file-hint">فرمت‌های مجاز: JPG, PNG, PDF - حداکثر حجم هر فایل: 20MB - حداکثر 3 فایل</small>
            </div>

            <div class="form-group">
                <input type="submit" value="ارسال درخواست من" class="submit-button">
                ' . wp_nonce_field('send_message_nonce', 'message_nonce', true, false) . '
            </div>
        </form>';

        $output .= '<style>
            .custom-message-form {
                width: 1300px;
                max-width: 100%;
                margin: 20px auto;
                padding: 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group h3 {
                font-size: 21px;
                font-weight: bold;
                margin-bottom: 15px;
                color: #333;
            }

            .horizontal-radio {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
                margin-bottom: 15px;
            }

            .radio-item {
                display: inline-flex;
                align-items: center;
                background: #f8f9fa;
                padding: 8px 15px;
                border-radius: 4px;
                border: 1px solid #ddd;
            }

            .radio-item label {
                font-size: 18px;
                font-weight: bold;
                margin-right: 8px;
            }

            .user-search {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
            }

            .user-select {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 16px;
                height: 70px;
                overflow-y: auto;
            }

            .user-select option {
                padding: 8px;
                font-size: 16px;
            }

            .attachment-input {
                margin: 20px 0;
                text-align: center;
            }

            .attachment-files {
                display: none;
            }

            .file-select-button {
                display: inline-block;
                padding: 12px 25px;
                background: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 14px;
            }

            .file-select-button:hover {
                background: #e9ecef;
                border-color: #bbb;
            }

            .selected-files-list {
                margin-top: 15px;
                text-align: right;
            }

            .selected-files-list .file-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin: 8px 0;
                padding: 8px 12px;
                background: #f8f9fa;
                border-radius: 4px;
                border: 1px solid #eee;
            }

            .file-item .remove-file {
                color: #dc3545;
                cursor: pointer;
                font-size: 18px;
                padding: 0 5px;
            }

            .file-hint {
                display: block;
                margin-top: 8px;
                color: #666;
                font-size: 0.9em;
                text-align: center.
            }

            .submit-button {
                background: #0073aa;
                color: white;
                padding: 12px 25px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background 0.3s ease;
            }

            .submit-button:hover {
                background: #005177;
            }

            .submit-button:disabled {
                background: #ccc;
                cursor: not-allowed.
            }

            .loading-message {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 20px;
                border-radius: 8px;
                z-index: 1000;
                text-align: center;
                font-size: 16px.
            }

            .wp-editor-container {
                border: 1px solid #ddd;
                border-radius: 4px.
            }

            .wp-editor-area {
                min-height: 200px.
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                // جستجوی کاربران
                $("#user-search").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#selected_user option").filter(function() {
                        var matches = $(this).text().toLowerCase().indexOf(value) > -1;
                        $(this).toggle(matches);
                    });
                });

                $("#attachments").change(function(e) {
                    var files = e.target.files;
                    var fileList = $(".selected-files-list");
                    fileList.empty();

                    // محدود کردن به 3 فایل
                    if (files.length > 3) {
                        alert("شما فقط می‌توانید 3 فایل انتخاب کنید.");
                        $(this).val("");
                        return;
                    }

                    // نمایش فایل‌های انتخاب شده
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        var fileSize = (file.size / (1024 * 1024)).toFixed(2);

                        if (file.size > 20 * 1024 * 1024) {
                            alert("فایل " + file.name + " بزرگتر از 20 مگابایت است.");
                            $(this).val("");
                            fileList.empty();
                            return;
                        }

                        fileList.append(
                            `<div class="file-item">
                                <span>${file.name} (${fileSize} MB)</span>
                                <span class="remove-file" data-index="${i}">×</span>
                            </div>`
                        );
                    }
                });

                // حذف فایل از لیست
                $(document).on("click", ".remove-file", function() {
                    var fileInput = $("#attachments")[0];
                    var index = $(this).data("index");
                    var files = Array.from(fileInput.files);
                    files.splice(index, 1);

                    fileInput.value = "";
                    $(".selected-files-list").empty();
                    if (files.length > 0) {
                        const dt = new DataTransfer();
                        files.forEach(file => dt.items.add(file));
                        fileInput.files = dt.files;
                        $(fileInput).trigger("change");
                    }
                });

                // فیلتر کاربران بر اساس گروه انتخابی
                $("input[name='user_role']").change(function() {
                    var selectedRole = $(this).val();
                    $("#selected_user option").hide();
                    $("#selected_user option[data-role*='" + selectedRole + "']").show();
                    $("#selected_user").val("");
                    $("#user-search").val("");
                });

                $("#myform").on("submit", function(e) {
                    e.preventDefault();

                    // غیرفعال کردن دکمه ارسال
                    var submitButton = $(this).find("input[type=submit]");
                    submitButton.prop("disabled", true);

                    // نمایش پیام در حال ارسال
                    var loadingMessage = $("<div>").addClass("loading-message")
                        .text("پیام درحال ارسال، لطفا صبر کنید و صفحه را ترک نکنید...");
                    $("body").append(loadingMessage);

                    var formData = new FormData(this);
                    formData.append("action", "handle_message_submission_unique");
                    formData.append("message", tinymce.get("message").getContent());

                    $.ajax({
                        url: ajaxurl, // WordPress global variable for admin-ajax.php
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            loadingMessage.remove();
                            if (response.success) {
                                alert("پیام با موفقیت ارسال شد");
                                $("#myform")[0].reset();
                                if (tinymce.get("message")) {
                                    tinymce.get("message").setContent("");
                                }
                                $(".selected-files-list").empty();
                            } else {
                                alert("خطا: " + (response.data || "خطای نامشخص"));
                            }
                            submitButton.prop("disabled", false);
                        },
                        error: function(xhr, status, error) {
                            loadingMessage.remove();
                            alert("خطا در ارسال پیام: " + error);
                            submitButton.prop("disabled", false);
                        }
                    });
                });
            });
        </script>';

        return $output;
    }
    add_shortcode('myform', 'display_custom_message_form_unique');
}

if (!function_exists('handle_message_submission_unique')) {
    // متد AJAX برای پردازش پیام
    function handle_message_submission_unique() {
        global $wpdb;

        // بررسی nonce برای امنیت
        check_ajax_referer('send_message_nonce', 'message_nonce');

        // بررسی ورودی‌ها
        $sender_id = get_current_user_id();
        $receiver_id = isset($_POST['selected_user']) ? intval($_POST['selected_user']) : 0;
        $message = isset($_POST['message']) ? wp_kses_post($_POST['message']) : '';

        if (empty($sender_id) || empty($receiver_id) || empty($message)) {
            wp_send_json_error('همه فیلدها باید پر شوند.');
        }

        // ذخیره پیام در جدول پیام‌های ارسال شده
        $wpdb->insert(
            $wpdb->prefix . 'sent_messages',
            array(
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'message' => $message,
                'attachments' => '',
                'created_at' => current_time('mysql')
            )
        );

        // ذخیره پیام در جدول پیام‌های دریافت شده
        $wpdb->insert(
            $wpdb->prefix . 'received_messages',
            array(
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'message' => $message,
                'attachments' => '',
                'created_at' => current_time('mysql')
            )
        );

        $message_id = $wpdb->insert_id;

        // پردازش پیوست‌ها
        if (!empty($_FILES['attachments']['name'][0])) {
            $files = $_FILES['attachments'];
            $attachment_ids = array();

            // محدود کردن تعداد فایل‌ها به 3
            if (count($files['name']) > 3) {
                wp_send_json_error('شما فقط می‌توانید 3 فایل انتخاب کنید.');
            }

            foreach ($files['name'] as $key => $value) {
                if ($files['size'][$key] > 20 * 1024 * 1024) {
                    wp_send_json_error('حجم فایل ' . $value . ' بزرگتر از 20 مگابایت است.');
                }

                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );

                $_FILES['attachment'] = $file;
                $attachment_id = media_handle_upload('attachment', 0);

                if (is_wp_error($attachment_id)) {
                    wp_send_json_error($attachment_id->get_error_message());
                }

                $attachment_ids[] = $attachment_id;
            }

            // به‌روزرسانی پیوست‌ها در جداول پیام‌ها
            $attachments_serialized = maybe_serialize($attachment_ids);

            $wpdb->update(
                $wpdb->prefix . 'sent_messages',
                array('attachments' => $attachments_serialized),
                array('id' => $message_id)
            );

            $wpdb->update(
                $wpdb->prefix . 'received_messages',
                array('attachments' => $attachments_serialized),
                array('id' => $message_id)
            );
        }

        // پاسخ موفقیت‌آمیز
        wp_send_json_success('پیام با موفقیت ارسال شد');
    }
    add_action('wp_ajax_handle_message_submission_unique', 'handle_message_submission_unique');
    add_action('wp_ajax_nopriv_handle_message_submission_unique', 'handle_message_submission_unique');
}
//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6
//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6
//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6
//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6//PART6
<?php
// به‌روزرسانی پیوست‌ها در جداول پیام‌ها
$attachments_serialized = maybe_serialize($attachment_ids);

$wpdb->update(
    $wpdb->prefix . 'sent_messages',
    array('attachments' => $attachments_serialized),
    array('id' => $message_id)
);

$wpdb->update(
    $wpdb->prefix . 'received_messages',
    array('attachments' => $attachments_serialized),
    array('id' => $message_id)
);

// پاسخ موفقیت‌آمیز
wp_send_json_success('پیام با موفقیت ارسال شد');
}
add_action('wp_ajax_handle_message_submission_unique', 'handle_message_submission_unique');
add_action('wp_ajax_nopriv_handle_message_submission_unique', 'handle_message_submission_unique');
//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7
//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7
//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7
//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7//PART7
// ایجاد دسته‌بندی برای پیام‌ها
function create_message_categories_taxonomy() {
    $labels = array(
        'name' => 'دسته‌بندی پیام‌ها',
        'singular_name' => 'دسته‌بندی پیام',
        'search_items' => 'جستجوی دسته‌بندی‌ها',
        'all_items' => 'همه دسته‌بندی‌ها',
        'parent_item' => 'دسته‌بندی والد',
        'parent_item_colon' => 'دسته‌بندی والد:',
        'edit_item' => 'ویرایش دسته‌بندی',
        'update_item' => 'به‌روزرسانی دسته‌بندی',
        'add_new_item' => 'افزودن دسته‌بندی جدید',
        'new_item_name' => 'نام دسته‌بندی جدید',
        'menu_name' => 'دسته‌بندی‌ها',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'message-category'),
    );

    register_taxonomy('message_categories', array('messages'), $args);
}
add_action('init', 'create_message_categories_taxonomy', 0);

// تغییر سطح دسترسی برای ارسال پیام برای همه کاربران
function customize_user_capabilities() {
    $roles = array('administrator', 'editor', 'author', 'contributor', 'subscriber');
    
    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            $role->add_cap('publish_messages');
            $role->add_cap('edit_messages');
            $role->add_cap('delete_messages');
            $role->add_cap('read_messages');
        }
    }
}
add_action('init', 'customize_user_capabilities');

// ایجاد شورت‌کد برای نمایش پیام‌های ارسال‌شده
add_shortcode('sent_messages', 'display_sent_messages_shortcode');

function display_sent_messages_shortcode($user_id = null) {
    if (!is_user_logged_in()) {
        return 'لطفا برای مشاهده پیام‌های ارسال‌شده وارد شوید.';
    }

    $is_admin = current_user_can('administrator');
    $current_user_id = get_current_user_id();

    if ($is_admin && $user_id !== null) {
        $user_id = intval($user_id);
    } else {
        $user_id = $current_user_id;
    }

    $output = '<div id="message-list" class="sent-messages-container">';

    if ($is_admin) {
        $users = get_users(array('orderby' => 'display_name'));
        $output .= '<select id="user-select" class="user-select">
            <option value="">انتخاب کاربر برای مشاهده پیام‌ها</option>';
        foreach ($users as $user) {
            $output .= sprintf(
                '<option value="%d">%s (%s)</option>',
                $user->ID,
                esc_html($user->display_name),
                esc_html($user->user_email)
            );
        }
        $output .= '</select>';
    }

    $output .= '<input type="text" id="message-search" class="message-search" placeholder="جستجو بر اساس شناسه پیام...">
    <table class="sent-messages-table">
        <thead>
            <tr>
                <th class="column-recipient" style="width: 15%;">نام گیرنده</th>
                <th class="column-message" style="width: 50%;">شرح پیام</th>
                <th class="column-attachments" style="width: 15%;">اسناد پیوستی</th>
                <th class="column-date" style="width: 15%;">زمان ارسال</th>
                <th class="column-id" style="width: 5%;">شناسه</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="loading-message">در حال بارگذاری...</div>
</div>';

    // اضافه کردن استایل‌ها و اسکریپت‌ها
    $output .= '<style>
        .sent-messages-container {
            width: 100%;
            margin: 20px 0;
            overflow-x: auto;
        }

        .user-select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .message-search {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .sent-messages-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sent-messages-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }

        .sent-messages-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .recipient-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .group-name {
            font-size: 14px;
            color: #777;
        }

        .message-content {
            font-size: 15px;
            line-height: 1.5;
            color: #444;
        }

        .show-more-btn {
            display: inline-block;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #0073aa;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .show-more-btn:hover {
            background: #e9ecef;
            border-color: #0073aa;
            color: #005177;
        }

        .show-attachments-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #0073aa;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .show-attachments-btn:hover {
            background: #e9ecef;
            border-color: #0073aa;
            color: #005177;
        }

        .show-attachments-btn .fas {
            font-size: 20px;
        }

        .no-attachment {
            color: #666;
            font-style: italic;
            font-size: 15px;
        }

        .date-cell {
            font-size: 15px;
            color: #666;
        }

        .message-id {
            font-size: 15px;
            color: #666;
        }

        .no-messages {
            font-size: 16px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }

        .loading-message {
            display: none;
            text-align: center;
            font-size: 16px;
            color: #0073aa;
            padding: 20px;
        }
    </style>';

    $output .= '<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>';
    $output .= '<script>
        jQuery(document).ready(function($) {
            var page = 1;
            var loading = false;
            var hasMore = true;

            function loadMessages() {
                if (loading || !hasMore) return;
                loading = true;
                $(".loading-message").show();

                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "load_sent_messages",
                        page: page,
                        user_id: $("#user-select").val() || ' . $user_id . '
                    },
                    success: function(response) {
                        if (response.success) {
                            $(".sent-messages-table tbody").append(response.data);
                            if (response.data.trim() === "") {
                                hasMore = false;
                            }
                            page++;
                        } else {
                            hasMore = false;
                        }
                        $(".loading-message").hide();
                        loading = false;
                    }
                });
            }

            // بارگذاری پیام‌ها در ابتدا
            loadMessages();

            // بارگذاری پیام‌ها با اسکرول
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    loadMessages();
                }
            });

            // عملکرد دکمه ادامه پیام
            $(document).on("click", ".show-more-btn", function() {
                var $row = $(this).closest("tr");
                $(this).siblings(".continue-reading").toggle();
                $(this).text($(this).text() === "ادامه پیام" ? "بستن" : "ادامه پیام");
            });

            // عملکرد دکمه نمایش پیوست
            $(document).on("click", ".show-attachments-btn", function() {
                var url = $(this).data("url");
                window.open(url, "_blank");
            });

            // جستجو بر اساس شناسه پیام
            $("#message-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".sent-messages-table tbody tr").filter(function() {
                    $(this).toggle($(this).find(".message-id").text().toLowerCase().indexOf(value) > -1);
                });
            });

            // تغییر کاربر انتخاب شده برای مشاهده پیام‌ها
            $("#user-select").on("change", function() {
                page = 1;
                hasMore = true;
                $(".sent-messages-table tbody").empty();
                loadMessages();
            });
        });
    </script>';

    return $output;
}

// هندل کردن بارگذاری پیام‌های ارسال‌شده
add_action('wp_ajax_load_sent_messages', 'load_sent_messages');
add_action('wp_ajax_nopriv_load_sent_messages', 'load_sent_messages');

function load_sent_messages() {
    if (!is_user_logged_in()) {
        wp_send_json_error('دسترسی غیرمجاز');
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    global $wpdb;
    $table_name = $wpdb->prefix . 'sent_messages';
    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE sender_id = %d ORDER BY created_at DESC LIMIT %d, 10",
        $user_id,
        ($page - 1) * 10
    ));

    if ($messages) {
        ob_start();
        foreach ($messages as $message) {
            $recipient = get_userdata($message->receiver_id);
            $attachments = maybe_unserialize($message->attachments);
            $post_date = human_time_diff(strtotime($message->created_at), current_time('timestamp')) . ' قبل';
            $post_time = date('H:i', strtotime($message->created_at));
            $group_name = $recipient ? implode(', ', $recipient->roles) : 'بدون گروه';

            echo '<tr>
                <td class="recipient-name">
                    ' . get_avatar($message->receiver_id, 32) . '<br>
                    ' . esc_html($recipient->display_name) . '<br>
                    <span class="group-name">' . esc_html($group_name) . '</span>
                </td>
                <td class="message-content">
                    ' . wp_trim_words($message->message, 20) . '
                    <div class="continue-reading" style="display: none;">
                        ' . wp_kses_post($message->message) . '
                    </div>
                    <button class="show-more-btn">ادامه پیام</button>
                </td>
                <td class="attachments-cell">';

            if (!empty($attachments)) {
                foreach ($attachments as $attachment_id) {
                    $attachment_url = wp_get_attachment_url($attachment_id);
                    $file_type = wp_check_filetype($attachment_url);
                    $icon_class = 'fas fa-file';
                    if ($file_type['ext'] === 'pdf') {
                        $icon_class = 'fas fa-file-pdf';
                    } elseif (in_array($file_type['ext'], array('jpg', 'jpeg', 'png', 'gif'))) {
                        $icon_class = 'fas fa-file-image';
                    }

                    echo '<button class="show-attachments-btn" data-url="' . esc_url($attachment_url) . '">
                        <i class="' . $icon_class . '"></i>
                        نمایش پیوست
                    </button>';
                }
            } else {
                echo '<div class="no-attachment">این پیام پیوست ندارد</div>';
            }

            echo '</td>
                <td class="date-cell">ساعت: ' . $post_time . '<br>زمان: ' . $post_date . '</td>
                <td class="message-id">' . $message->id . '</td>
            </tr>';
        }
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_success('');
    }
}
