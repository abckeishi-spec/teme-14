<?php
/**
 * Grant Insight - Complete Functions File with All Features
 * 
 * 助成金・補助金テーマの完全な機能ファイル（省略なし）
 * 
 * @package Grant_Insight_Complete
 * @version 7.0.0-complete
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// テーマバージョン定数
define('GI_THEME_VERSION', '7.0.0-complete');
define('GI_THEME_PREFIX', 'gi_');
define('GI_THEME_DIR', get_template_directory());
define('GI_THEME_URI', get_template_directory_uri());
define('GI_AJAX_TIMEOUT', 30000);
define('GI_CACHE_DURATION', 3600);

// =============================================================================
// 1. THEME SETUP AND CONFIGURATION
// =============================================================================

/**
 * テーマセットアップ
 */
function gi_setup() {
    // テーマサポート機能
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    add_theme_support('custom-background');
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    add_theme_support('menus');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('automatic-feed-links');
    add_theme_support('editor-styles');
    add_theme_support('dark-editor-style');
    
    // カスタム画像サイズ
    add_image_size('gi-card-thumb', 400, 300, true);
    add_image_size('gi-hero-thumb', 800, 600, true);
    add_image_size('gi-banner', 1200, 400, true);
    add_image_size('gi-logo-sm', 80, 80, true);
    add_image_size('gi-featured', 1920, 600, true);
    add_image_size('gi-square', 600, 600, true);
    
    // 言語ファイル
    load_theme_textdomain('grant-insight', get_template_directory() . '/languages');
    
    // メニュー登録
    register_nav_menus(array(
        'primary' => 'メインメニュー',
        'footer' => 'フッターメニュー',
        'mobile' => 'モバイルメニュー',
        'social' => 'ソーシャルメニュー',
        'utility' => 'ユーティリティメニュー'
    ));
    
    // エディタースタイル
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'gi_setup');

/**
 * コンテンツ幅設定
 */
function gi_content_width() {
    $GLOBALS['content_width'] = apply_filters('gi_content_width', 1200);
}
add_action('after_setup_theme', 'gi_content_width', 0);

// =============================================================================
// 2. SCRIPTS AND STYLES
// =============================================================================

/**
 * スクリプト・スタイルの読み込み
 */
function gi_enqueue_scripts() {
    // jQuery（最優先）
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://code.jquery.com/jquery-3.7.1.min.js', array(), '3.7.1', true);
    wp_enqueue_script('jquery');
    
    // スタイルシート
    wp_enqueue_style('gi-google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('gi-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    wp_enqueue_style('gi-swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
    wp_enqueue_style('gi-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), GI_THEME_VERSION);
    wp_enqueue_style('gi-style', get_stylesheet_uri(), array('gi-main-style'), GI_THEME_VERSION);
    
    // インラインCSS
    $custom_css = gi_get_custom_css();
    wp_add_inline_style('gi-style', $custom_css);
    
    // JavaScript読み込み順序（Phase 4統合システム）
    // 1. 基本ライブラリ
    wp_enqueue_script('gi-swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
    
    // 2. 統合検索システムの設定（最重要）
    wp_enqueue_script('gi-search-config', get_template_directory_uri() . '/assets/js/search-config.js', array('jquery'), GI_THEME_VERSION, true);
    
    // 3. 統合検索マネージャー（コア機能）
    wp_enqueue_script('gi-unified-search-manager', get_template_directory_uri() . '/assets/js/unified-search-manager.js', array('jquery', 'gi-search-config'), GI_THEME_VERSION, true);
    
    // 4. レガシー互換性ブリッジ
    wp_enqueue_script('gi-legacy-bridge', get_template_directory_uri() . '/assets/js/legacy-bridge.js', array('jquery', 'gi-search-config', 'gi-unified-search-manager'), GI_THEME_VERSION, true);
    
    // 5. その他のメインJavaScript（統合システム対応版）
    wp_enqueue_script('gi-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'gi-legacy-bridge'), GI_THEME_VERSION, true);
    wp_enqueue_script('gi-mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', array('jquery'), GI_THEME_VERSION, true);
    
    // 注意：従来のsearch.js、filters.js、unified-search.jsは統合システムにより不要となったためコメントアウト
    // wp_enqueue_script('gi-search-js', get_template_directory_uri() . '/assets/js/search.js', array('gi-main-js'), GI_THEME_VERSION, true);
    // wp_enqueue_script('gi-filters-js', get_template_directory_uri() . '/assets/js/filters.js', array('gi-main-js'), GI_THEME_VERSION, true);
    // wp_enqueue_script('gi-unified-search', get_template_directory_uri() . '/assets/js/unified-search.js', array('jquery', 'gi-main-js'), GI_THEME_VERSION, true);
    
    // 統合検索システム用ローカライズ（Phase 4最適化版）
    $gi_ajax_data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('gi_ajax_nonce'),
        'homeUrl' => home_url('/'),
        'themeUrl' => get_template_directory_uri(),
        'uploadsUrl' => wp_upload_dir()['baseurl'],
        'isAdmin' => current_user_can('administrator'),
        'userId' => get_current_user_id(),
        'version' => GI_THEME_VERSION,
        'debug' => WP_DEBUG,
        'timeout' => GI_AJAX_TIMEOUT,
        'unified_system' => true,  // 統合システムフラグ
        'phase' => 'Phase4-ScriptOrder',  // 現在のフェーズ
        'strings' => array(
            'loading' => '読み込み中...',
            'error' => 'エラーが発生しました',
            'noResults' => '結果が見つかりませんでした',
            'confirm' => '実行してもよろしいですか？',
            'success' => '正常に処理されました',
            'failed' => '処理に失敗しました',
            'retry' => '再試行',
            'close' => '閉じる',
            'search' => '検索',
            'filter' => 'フィルター',
            'reset' => 'リセット',
            'apply' => '適用',
            'cancel' => 'キャンセル',
            'save' => '保存',
            'delete' => '削除',
            'edit' => '編集',
            'view' => '表示',
            'share' => '共有',
            'download' => 'ダウンロード',
            'print' => '印刷',
            'favorite' => 'お気に入り',
            'unfavorite' => 'お気に入り解除',
            'legacyMode' => 'レガシーモード',
            'bridgeActive' => 'ブリッジ有効',
            'systemReady' => 'システム準備完了'
        )
    );
    
    // 各スクリプトに必要なローカライズを追加
    wp_localize_script('gi-search-config', 'gi_ajax', $gi_ajax_data);
    wp_localize_script('gi-unified-search-manager', 'gi_ajax', $gi_ajax_data);
    wp_localize_script('gi-legacy-bridge', 'gi_ajax', $gi_ajax_data);
    wp_localize_script('gi-main-js', 'gi_ajax', $gi_ajax_data);
            'share' => '共有',
            'download' => 'ダウンロード',
            'print' => '印刷',
            'favorite' => 'お気に入り',
            'unfavorite' => 'お気に入り解除'
        )
    ));
    
    // 条件付きスクリプト
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    if (is_front_page()) {
        wp_enqueue_script('gi-homepage-js', get_template_directory_uri() . '/assets/js/homepage.js', array('gi-main-js', 'gi-swiper'), GI_THEME_VERSION, true);
    }
    
    if (is_post_type_archive('grant') || is_tax('grant_category') || is_tax('grant_prefecture')) {
        wp_enqueue_script('gi-archive-js', get_template_directory_uri() . '/assets/js/archive.js', array('gi-main-js', 'gi-unified-search'), GI_THEME_VERSION, true);
    }
    
    if (is_singular('grant')) {
        wp_enqueue_script('gi-single-grant-js', get_template_directory_uri() . '/assets/js/single-grant.js', array('gi-main-js'), GI_THEME_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'gi_enqueue_scripts');

/**
 * カスタムCSS生成
 */
function gi_get_custom_css() {
    $primary_color = get_theme_mod('gi_primary_color', '#059669');
    $secondary_color = get_theme_mod('gi_secondary_color', '#3b82f6');
    
    $css = "
        :root {
            --gi-primary: {$primary_color};
            --gi-secondary: {$secondary_color};
        }
        .gi-logo-container { 
            width: 80px; 
            height: 80px; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }
        .gi-logo-container img { 
            max-width: 100%; 
            height: auto; 
            width: auto;
        }
        .mobile-menu-overlay { 
            pointer-events: auto !important; 
        }
        .mobile-menu-toggle { 
            pointer-events: auto !important; 
            z-index: 9999; 
        }
        .grant-card-modern {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .grant-card-modern:hover {
            transform: translateY(-4px);
        }
    ";
    
    return $css;
}

/**
 * defer/async属性の追加
 */
function gi_script_attributes($tag, $handle, $src) {
    $defer_scripts = array(
        'gi-main-js',
        'gi-search-js',
        'gi-filters-js',
        'gi-archive-js',
        'gi-homepage-js',
        'gi-single-grant-js',
        'gi-unified-search'
    );
    
    $async_scripts = array(
        'gi-swiper',
        'gi-fontawesome'
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }
    
    if (in_array($handle, $async_scripts)) {
        return str_replace('<script ', '<script async ', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'gi_script_attributes', 10, 3);

/**
 * プリロード設定
 */
function gi_add_preload_links() {
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    echo '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap"></noscript>';
    
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'gi-logo-sm');
        if ($logo_url) {
            echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">';
        }
    }
    
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">';
    echo '<link rel="dns-prefetch" href="//cdn.jsdelivr.net">';
}
add_action('wp_head', 'gi_add_preload_links', 1);

// =============================================================================
// 3. POST TYPES & TAXONOMIES
// =============================================================================

/**
 * カスタム投稿タイプ登録
 */
function gi_register_post_types() {
    // 助成金投稿タイプ
    register_post_type('grant', array(
        'labels' => array(
            'name' => '助成金・補助金',
            'singular_name' => '助成金・補助金',
            'add_new' => '新規追加',
            'add_new_item' => '新しい助成金・補助金を追加',
            'edit_item' => '助成金・補助金を編集',
            'new_item' => '新しい助成金・補助金',
            'view_item' => '助成金・補助金を表示',
            'view_items' => '助成金・補助金一覧を表示',
            'search_items' => '助成金・補助金を検索',
            'not_found' => '助成金・補助金が見つかりませんでした',
            'not_found_in_trash' => 'ゴミ箱に助成金・補助金はありません',
            'all_items' => 'すべての助成金・補助金',
            'archives' => '助成金・補助金アーカイブ',
            'attributes' => '助成金・補助金の属性',
            'menu_name' => '助成金・補助金',
            'name_admin_bar' => '助成金・補助金'
        ),
        'description' => '助成金・補助金情報を管理します',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'grants',
            'with_front' => false,
            'feeds' => true,
            'pages' => true
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-money-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'grants',
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    ));
    
    // ニュース投稿タイプ
    register_post_type('grant_news', array(
        'labels' => array(
            'name' => '助成金ニュース',
            'singular_name' => 'ニュース',
            'add_new' => '新規追加',
            'add_new_item' => '新しいニュースを追加',
            'edit_item' => 'ニュースを編集',
            'new_item' => '新しいニュース',
            'view_item' => 'ニュースを表示',
            'search_items' => 'ニュースを検索',
            'not_found' => 'ニュースが見つかりませんでした',
            'not_found_in_trash' => 'ゴミ箱にニュースはありません',
            'all_items' => 'すべてのニュース',
            'menu_name' => '助成金ニュース'
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'grant-news', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true
    ));
    
    // FAQ投稿タイプ
    register_post_type('grant_faq', array(
        'labels' => array(
            'name' => 'よくある質問',
            'singular_name' => 'FAQ',
            'add_new' => '新規追加',
            'add_new_item' => '新しいFAQを追加',
            'edit_item' => 'FAQを編集',
            'new_item' => '新しいFAQ',
            'view_item' => 'FAQを表示',
            'search_items' => 'FAQを検索',
            'not_found' => 'FAQが見つかりませんでした',
            'not_found_in_trash' => 'ゴミ箱にFAQはありません',
            'all_items' => 'すべてのFAQ',
            'menu_name' => 'FAQ'
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'grant-faq', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => array('title', 'editor', 'page-attributes'),
        'show_in_rest' => true
    ));
}
add_action('init', 'gi_register_post_types');

/**
 * カスタムタクソノミー登録
 */
function gi_register_taxonomies() {
    // 助成金カテゴリー
    register_taxonomy('grant_category', 'grant', array(
        'labels' => array(
            'name' => '助成金カテゴリー',
            'singular_name' => '助成金カテゴリー',
            'search_items' => 'カテゴリーを検索',
            'all_items' => 'すべてのカテゴリー',
            'parent_item' => '親カテゴリー',
            'parent_item_colon' => '親カテゴリー:',
            'edit_item' => 'カテゴリーを編集',
            'update_item' => 'カテゴリーを更新',
            'add_new_item' => '新しいカテゴリーを追加',
            'new_item_name' => '新しいカテゴリー名',
            'menu_name' => 'カテゴリー'
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'grant-category', 'with_front' => false, 'hierarchical' => true),
        'show_in_rest' => true
    ));
    
    // 都道府県タクソノミー
    register_taxonomy('grant_prefecture', 'grant', array(
        'labels' => array(
            'name' => '対象都道府県',
            'singular_name' => '都道府県',
            'search_items' => '都道府県を検索',
            'all_items' => 'すべての都道府県',
            'edit_item' => '都道府県を編集',
            'update_item' => '都道府県を更新',
            'add_new_item' => '新しい都道府県を追加',
            'new_item_name' => '新しい都道府県名',
            'menu_name' => '都道府県'
        ),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'prefecture', 'with_front' => false),
        'show_in_rest' => true
    ));
    
    // 助成金タグ
    register_taxonomy('grant_tag', 'grant', array(
        'labels' => array(
            'name' => '助成金タグ',
            'singular_name' => '助成金タグ',
            'search_items' => 'タグを検索',
            'all_items' => 'すべてのタグ',
            'edit_item' => 'タグを編集',
            'update_item' => 'タグを更新',
            'add_new_item' => '新しいタグを追加',
            'new_item_name' => '新しいタグ名',
            'menu_name' => 'タグ'
        ),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'grant-tag', 'with_front' => false),
        'show_in_rest' => true
    ));
    
    // 業種タクソノミー
    register_taxonomy('grant_industry', 'grant', array(
        'labels' => array(
            'name' => '対象業種',
            'singular_name' => '業種',
            'search_items' => '業種を検索',
            'all_items' => 'すべての業種',
            'edit_item' => '業種を編集',
            'update_item' => '業種を更新',
            'add_new_item' => '新しい業種を追加',
            'new_item_name' => '新しい業種名',
            'menu_name' => '業種'
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'industry', 'with_front' => false),
        'show_in_rest' => true
    ));
    
    // ニュースカテゴリー
    register_taxonomy('news_category', 'grant_news', array(
        'labels' => array(
            'name' => 'ニュースカテゴリー',
            'singular_name' => 'ニュースカテゴリー',
            'menu_name' => 'カテゴリー'
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news-category', 'with_front' => false),
        'show_in_rest' => true
    ));
    
    // FAQカテゴリー
    register_taxonomy('faq_category', 'grant_faq', array(
        'labels' => array(
            'name' => 'FAQカテゴリー',
            'singular_name' => 'FAQカテゴリー',
            'menu_name' => 'カテゴリー'
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'faq-category', 'with_front' => false),
        'show_in_rest' => true
    ));
}
add_action('init', 'gi_register_taxonomies');

// =============================================================================
// 4. WIDGETS
// =============================================================================

/**
 * ウィジェットエリア登録
 */
function gi_widgets_init() {
    // メインサイドバー
    register_sidebar(array(
        'name'          => 'メインサイドバー',
        'id'            => 'sidebar-main',
        'description'   => 'メインサイドバーエリア',
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-semibold mb-4 pb-2 border-b-2 border-emerald-500">',
        'after_title'   => '</h3>',
    ));
    
    // 助成金サイドバー
    register_sidebar(array(
        'name'          => '助成金サイドバー',
        'id'            => 'sidebar-grant',
        'description'   => '助成金ページ用サイドバー',
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-semibold mb-4 pb-2 border-b-2 border-blue-500">',
        'after_title'   => '</h3>',
    ));
    
    // フッターエリア1
    register_sidebar(array(
        'name'          => 'フッターエリア1',
        'id'            => 'footer-1',
        'description'   => 'フッター左側エリア',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-white font-semibold mb-4">',
        'after_title'   => '</h4>',
    ));
    
    // フッターエリア2
    register_sidebar(array(
        'name'          => 'フッターエリア2',
        'id'            => 'footer-2',
        'description'   => 'フッター中央エリア',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-white font-semibold mb-4">',
        'after_title'   => '</h4>',
    ));
    
    // フッターエリア3
    register_sidebar(array(
        'name'          => 'フッターエリア3',
        'id'            => 'footer-3',
        'description'   => 'フッター右側エリア',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-white font-semibold mb-4">',
        'after_title'   => '</h4>',
    ));
    
    // フッターエリア4
    register_sidebar(array(
        'name'          => 'フッターエリア4',
        'id'            => 'footer-4',
        'description'   => 'フッター追加エリア',
        'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-white font-semibold mb-4">',
        'after_title'   => '</h4>',
    ));
    
    // ホームページウィジェット
    register_sidebar(array(
        'name'          => 'ホームページ上部',
        'id'            => 'home-top',
        'description'   => 'トップページ上部エリア',
        'before_widget' => '<div id="%1$s" class="widget home-widget %2$s mb-8">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title text-2xl font-bold mb-6">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => 'ホームページ下部',
        'id'            => 'home-bottom',
        'description'   => 'トップページ下部エリア',
        'before_widget' => '<div id="%1$s" class="widget home-widget %2$s mb-8">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title text-2xl font-bold mb-6">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'gi_widgets_init');

// =============================================================================
// 5. HELPER FUNCTIONS
// =============================================================================

/**
 * 安全なメタデータ取得
 */
function gi_safe_get_meta($post_id, $key, $default = '') {
    if (!$post_id) return $default;
    $value = get_post_meta($post_id, $key, true);
    return $value !== '' ? $value : $default;
}

/**
 * ステータスをUI用に変換
 */
function gi_map_application_status_ui($status) {
    $map = array(
        'open' => '募集中',
        'active' => '募集中',
        'upcoming' => '準備中',
        'closed' => '終了',
        'preparing' => '準備中',
        'ended' => '終了'
    );
    return isset($map[$status]) ? $map[$status] : $status;
}

/**
 * 金額フォーマット
 */
function gi_format_amount_with_unit($amount) {
    if (empty($amount) || !is_numeric($amount)) {
        return '-';
    }
    
    $amount = intval($amount);
    
    if ($amount >= 100000000) {
        return number_format($amount / 100000000, 1) . '億円';
    } elseif ($amount >= 10000000) {
        return number_format($amount / 10000000, 1) . '千万円';
    } elseif ($amount >= 10000) {
        return number_format($amount / 10000) . '万円';
    } else {
        return number_format($amount) . '円';
    }
}

/**
 * 締切日フォーマット
 */
function gi_get_formatted_deadline($post_id) {
    $deadline = get_post_meta($post_id, 'deadline_date', true);
    
    if (empty($deadline)) {
        return '随時';
    }
    
    // タイムスタンプの場合
    if (is_numeric($deadline)) {
        $current_time = current_time('timestamp');
        if ($deadline < $current_time) {
            return '終了';
        }
        return date('Y年n月j日', $deadline);
    }
    
    // YYYYMMDDフォーマットの場合
    if (strlen($deadline) === 8 && is_numeric($deadline)) {
        $year = substr($deadline, 0, 4);
        $month = substr($deadline, 4, 2);
        $day = substr($deadline, 6, 2);
        
        $current_date = date('Ymd');
        if ($deadline < $current_date) {
            return '終了';
        }
        
        return $year . '年' . intval($month) . '月' . intval($day) . '日';
    }
    
    // その他のフォーマットの場合
    if (strtotime($deadline)) {
        return date('Y年n月j日', strtotime($deadline));
    }
    
    return $deadline;
}

/**
 * ユーザーのお気に入りを取得
 */
function gi_get_user_favorites($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if ($user_id) {
        $favorites = get_user_meta($user_id, 'gi_favorites', true);
        return is_array($favorites) ? $favorites : array();
    }
    
    // 非ログインユーザーの場合はCookieから取得
    if (isset($_COOKIE['gi_favorites'])) {
        return array_filter(array_map('intval', explode(',', $_COOKIE['gi_favorites'])));
    }
    
    return array();
}

/**
 * 安全なURLエスケープ
 */
function gi_safe_url($url) {
    return esc_url($url);
}

/**
 * 安全なHTMLエスケープ
 */
function gi_safe_escape($text) {
    return esc_html($text);
}

/**
 * 安全な抜粋取得
 */
function gi_safe_excerpt($text, $length = 100) {
    $text = strip_tags($text);
    $text = mb_substr($text, 0, $length);
    if (mb_strlen($text) === $length) {
        $text .= '...';
    }
    return $text;
}

/**
 * 安全な日付フォーマット
 */
function gi_safe_date_format($date, $format = 'Y年n月j日') {
    if (empty($date)) {
        return '-';
    }
    
    if (is_numeric($date) && strlen($date) === 8) {
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6, 2);
        $date = $year . '-' . $month . '-' . $day;
    }
    
    $timestamp = strtotime($date);
    if ($timestamp) {
        return date($format, $timestamp);
    }
    
    return $date;
}

/**
 * ページビュー数を取得
 */
function gi_get_post_views($post_id) {
    $views = get_post_meta($post_id, 'views_count', true);
    return $views ? intval($views) : 0;
}

/**
 * ページビュー数を更新
 */
function gi_update_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    $views = gi_get_post_views($post_id);
    update_post_meta($post_id, 'views_count', $views + 1);
}
add_action('wp_head', 'gi_update_post_views');

// =============================================================================
// 6. AJAX FUNCTIONS - 修正版
// =============================================================================

/**
 * AJAX - 助成金読み込み処理（シンプル版）
 */
<?php
/**
 * 統合AJAX検索処理 - 完全版
 * Phase 3: バックエンドAJAX統一対応
 */
function gi_unified_search_handler() {
    // セキュリティチェック
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error(array(
            'message' => 'セキュリティチェックに失敗しました',
            'code' => 'SECURITY_ERROR'
        ));
    }

    try {
        // パラメータ正規化と検証
        $params = gi_normalize_search_params_v2($_POST);
        
        // バリデーション
        $validation_errors = gi_validate_search_params($params);
        if (!empty($validation_errors)) {
            wp_send_json_error(array(
                'message' => 'パラメータエラー: ' . implode(', ', $validation_errors),
                'code' => 'VALIDATION_ERROR',
                'errors' => $validation_errors
            ));
        }
        
        // クエリ構築と実行
        $query_args = gi_build_search_query_v2($params);
        $search_query = new WP_Query($query_args);
        
        // 結果データ構築
        $grants = array();
        $total_found = $search_query->found_posts;
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                $grant_data = gi_format_grant_data_unified(get_the_ID());
                $grants[] = $grant_data;
            }
            wp_reset_postdata();
        }

        // ページネーション生成
        $pagination = gi_generate_pagination_unified($search_query, $params);
        
        // 統計情報
        $stats = gi_calculate_search_stats($grants, $total_found);
        
        // 成功レスポンス
        wp_send_json_success(array(
            'grants' => $grants,
            'found_posts' => $total_found,
            'current_page' => $params['page'],
            'total_pages' => $search_query->max_num_pages,
            'posts_per_page' => $params['posts_per_page'],
            'pagination' => $pagination,
            'stats' => $stats,
            'query' => $params,
            'debug_info' => WP_DEBUG ? array(
                'sql' => $search_query->request,
                'query_vars' => $search_query->query_vars
            ) : null
        ));

    } catch (Exception $e) {
        error_log('統合検索エラー: ' . $e->getMessage());
        
        wp_send_json_error(array(
            'message' => WP_DEBUG ? $e->getMessage() : '検索処理中にエラーが発生しました',
            'code' => 'SEARCH_ERROR',
            'debug' => WP_DEBUG ? $e->getTraceAsString() : null
        ));
    }
}

/**
 * 検索パラメータ正規化 v2
 */
function gi_normalize_search_params_v2($raw_params) {
    $defaults = array(
        'search' => '',
        'categories' => array(),
        'prefectures' => array(), 
        'industries' => array(),
        'amount' => '',
        'status' => array(),
        'difficulty' => array(),
        'success_rate' => array(),
        'page' => 1,
        'posts_per_page' => 12,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query_relation' => 'AND',
        'tax_query_relation' => 'AND'
    );

    $normalized = array();
    
    foreach ($defaults as $key => $default) {
        $raw_value = $raw_params[$key] ?? $default;
        
        // JSON文字列のデコード
        if (is_string($raw_value) && in_array($key, array('categories', 'prefectures', 'industries', 'status', 'difficulty', 'success_rate'))) {
            $decoded = json_decode(stripslashes($raw_value), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $raw_value = $decoded;
            } else if (!empty($raw_value)) {
                $raw_value = array($raw_value);
            } else {
                $raw_value = array();
            }
        }
        
        // データ型に応じたサニタイズ
        if (is_array($raw_value)) {
            $normalized[$key] = array_map('sanitize_text_field', array_filter($raw_value));
        } else if (in_array($key, array('page', 'posts_per_page'))) {
            $normalized[$key] = max(1, intval($raw_value));
        } else {
            $normalized[$key] = sanitize_text_field($raw_value);
        }
    }

    // posts_per_page の上限制御
    $normalized['posts_per_page'] = min($normalized['posts_per_page'], 100);
    
    return $normalized;
}

/**
 * 検索パラメータバリデーション
 */
function gi_validate_search_params($params) {
    $errors = array();
    
    // ページ番号チェック
    if ($params['page'] < 1) {
        $errors[] = 'ページ番号は1以上である必要があります';
    }
    
    // 表示件数チェック
    if ($params['posts_per_page'] < 1 || $params['posts_per_page'] > 100) {
        $errors[] = '表示件数は1〜100の範囲で指定してください';
    }
    
    // ソート順チェック
    $valid_orderby = array('date', 'title', 'meta_value_num', 'menu_order');
    if (!in_array($params['orderby'], $valid_orderby)) {
        $errors[] = '無効なソート順が指定されています';
    }
    
    $valid_order = array('ASC', 'DESC');
    if (!in_array(strtoupper($params['order']), $valid_order)) {
        $errors[] = '無効なソート方向が指定されています';
    }
    
    return $errors;
}

/**
 * 統合検索クエリ構築 v2
 */
function gi_build_search_query_v2($params) {
    $args = array(
        'post_type' => 'grant',
        'posts_per_page' => $params['posts_per_page'],
        'paged' => $params['page'],
        'post_status' => 'publish'
    );

    // 検索キーワード
    if (!empty($params['search'])) {
        $args['s'] = $params['search'];
    }

    // タクソノミークエリ構築
    $tax_query = array('relation' => $params['tax_query_relation']);
    
    if (!empty($params['categories'])) {
        $tax_query[] = array(
            'taxonomy' => 'grant_category',
            'field' => 'slug',
            'terms' => $params['categories'],
            'operator' => 'IN'
        );
    }
    
    if (!empty($params['prefectures'])) {
        $tax_query[] = array(
            'taxonomy' => 'grant_prefecture',
            'field' => 'slug',
            'terms' => $params['prefectures'],
            'operator' => 'IN'
        );
    }
    
    if (!empty($params['industries'])) {
        $tax_query[] = array(
            'taxonomy' => 'grant_industry',
            'field' => 'slug',
            'terms' => $params['industries'],
            'operator' => 'IN'
        );
    }
    
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }

    // メタクエリ構築
    $meta_query = array('relation' => $params['meta_query_relation']);

    // ステータスフィルター
    if (!empty($params['status'])) {
        $status_values = array();
        foreach ($params['status'] as $s) {
            switch ($s) {
                case 'active':
                case 'open':
                    $status_values[] = 'open';
                    break;
                case 'upcoming':
                    $status_values[] = 'upcoming';
                    $status_values[] = 'preparing';
                    break;
                case 'closed':
                case 'ended':
                    $status_values[] = 'closed';
                    $status_values[] = 'ended';
                    break;
            }
        }
        if (!empty($status_values)) {
            $meta_query[] = array(
                'key' => 'application_status',
                'value' => $status_values,
                'compare' => 'IN'
            );
        }
    }

    // 金額フィルター
    if (!empty($params['amount'])) {
        switch ($params['amount']) {
            case '0-100':
                $meta_query[] = array(
                    'key' => 'max_amount_numeric',
                    'value' => 1000000,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                );
                break;
            case '100-500':
                $meta_query[] = array(
                    'key' => 'max_amount_numeric',
                    'value' => array(1000001, 5000000),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                );
                break;
            case '500-1000':
                $meta_query[] = array(
                    'key' => 'max_amount_numeric',
                    'value' => array(5000001, 10000000),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                );
                break;
            case '1000+':
                $meta_query[] = array(
                    'key' => 'max_amount_numeric',
                    'value' => 10000001,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );
                break;
        }
    }

    // 難易度フィルター
    if (!empty($params['difficulty'])) {
        $meta_query[] = array(
            'key' => 'grant_difficulty',
            'value' => $params['difficulty'],
            'compare' => 'IN'
        );
    }

    // 成功率フィルター
    if (!empty($params['success_rate'])) {
        $success_rate_ranges = array();
        foreach ($params['success_rate'] as $rate) {
            switch ($rate) {
                case 'high':
                    $success_rate_ranges[] = array(
                        'key' => 'grant_success_rate',
                        'value' => 70,
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    );
                    break;
                case 'medium':
                    $success_rate_ranges[] = array(
                        'key' => 'grant_success_rate',
                        'value' => array(30, 69),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    );
                    break;
                case 'low':
                    $success_rate_ranges[] = array(
                        'key' => 'grant_success_rate',
                        'value' => 30,
                        'compare' => '<',
                        'type' => 'NUMERIC'
                    );
                    break;
            }
        }
        if (!empty($success_rate_ranges)) {
            $meta_query[] = array(
                'relation' => 'OR',
                ...$success_rate_ranges
            );
        }
    }

    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }

    // ソート設定
    switch ($params['orderby']) {
        case 'title':
            $args['orderby'] = 'title';
            $args['order'] = $params['order'];
            break;
        case 'meta_value_num':
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'max_amount_numeric';
            $args['order'] = $params['order'];
            break;
        case 'menu_order':
            $args['orderby'] = 'menu_order';
            $args['order'] = $params['order'];
            break;
        default:
            $args['orderby'] = 'date';
            $args['order'] = $params['order'];
    }

    return apply_filters('gi_search_query_args', $args, $params);
}

/**
 * 統合助成金データフォーマット
 */
function gi_format_grant_data_unified($post_id) {
    $grant_terms = get_the_terms($post_id, 'grant_category');
    $prefecture_terms = get_the_terms($post_id, 'grant_prefecture');
    $industry_terms = get_the_terms($post_id, 'grant_industry');
    
    $grant_data = array(
        'id' => $post_id,
        'title' => get_the_title(),
        'permalink' => get_permalink(),
        'excerpt' => get_the_excerpt(),
        'description' => get_the_content(),
        'thumbnail' => get_the_post_thumbnail_url($post_id, 'gi-card-thumb'),
        'featured_image' => get_the_post_thumbnail_url($post_id, 'full'),
        'main_category' => (!is_wp_error($grant_terms) && !empty($grant_terms)) ? $grant_terms[0]->name : '',
        'categories' => (!is_wp_error($grant_terms) && !empty($grant_terms)) ? wp_list_pluck($grant_terms, 'name') : array(),
        'prefecture' => (!is_wp_error($prefecture_terms) && !empty($prefecture_terms)) ? $prefecture_terms[0]->name : '',
        'prefectures' => (!is_wp_error($prefecture_terms) && !empty($prefecture_terms)) ? wp_list_pluck($prefecture_terms, 'name') : array(),
        'industries' => (!is_wp_error($industry_terms) && !empty($industry_terms)) ? wp_list_pluck($industry_terms, 'name') : array(),
        'organization' => gi_safe_get_meta($post_id, 'organization', ''),
        'deadline' => gi_get_formatted_deadline($post_id),
        'deadline_raw' => gi_safe_get_meta($post_id, 'application_deadline', ''),
        'amount' => gi_safe_get_meta($post_id, 'max_amount', '-'),
        'amount_numeric' => gi_safe_get_meta($post_id, 'max_amount_numeric', 0),
        'amount_formatted' => gi_format_amount_with_unit(gi_safe_get_meta($post_id, 'max_amount_numeric', 0)),
        'status' => gi_map_application_status_ui(gi_safe_get_meta($post_id, 'application_status', 'open')),
        'status_raw' => gi_safe_get_meta($post_id, 'application_status', 'open'),
        'difficulty' => gi_safe_get_meta($post_id, 'grant_difficulty', ''),
        'success_rate' => gi_safe_get_meta($post_id, 'grant_success_rate', 0),
        'requirements' => gi_safe_get_meta($post_id, 'application_requirements', ''),
        'contact_info' => gi_safe_get_meta($post_id, 'contact_information', ''),
        'created_date' => get_the_date('Y-m-d H:i:s'),
        'modified_date' => get_the_modified_date('Y-m-d H:i:s'),
        'data' => null // HTMLと区別するためのデータフィールド
    );
    
    // HTMLを生成（統一カードフォーマット）
    ob_start();
    ?>
    <div class="gi-grant-card-unified w-full" data-grant-id="<?php echo $post_id; ?>">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col">
            <div class="px-4 pt-4 pb-3">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?php echo gi_get_status_badge_class($grant_data['status_raw']); ?>">
                        <span class="w-1.5 h-1.5 bg-current rounded-full mr-1.5"></span>
                        <?php echo esc_html($grant_data['status']); ?>
                    </span>
                    <button class="gi-favorite-btn text-gray-400 hover:text-red-500 transition-colors p-1" data-post-id="<?php echo $post_id; ?>" title="お気に入りに追加">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
                
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-tight line-clamp-2 mb-2">
                    <a href="<?php echo esc_url($grant_data['permalink']); ?>" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                        <?php echo esc_html($grant_data['title']); ?>
                    </a>
                </h3>
            </div>
            
            <div class="px-4 pb-3 flex-grow">
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <?php if ($grant_data['main_category']): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                        <?php echo esc_html($grant_data['main_category']); ?>
                    </span>
                    <?php endif; ?>
                    <?php if ($grant_data['prefecture']): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                        📍 <?php echo esc_html($grant_data['prefecture']); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <?php if ($grant_data['amount_numeric'] > 0): ?>
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg p-3 mb-3 border border-emerald-100 dark:border-emerald-700">
                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">最大助成額</div>
                    <div class="text-xl font-bold text-emerald-700 dark:text-emerald-300">
                        <?php echo esc_html($grant_data['amount_formatted']); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400 mb-2">
                    <?php if ($grant_data['deadline']): ?>
                    <div>締切: <?php echo esc_html($grant_data['deadline']); ?></div>
                    <?php endif; ?>
                    <?php if ($grant_data['difficulty']): ?>
                    <div>難易度: <?php echo esc_html($grant_data['difficulty']); ?></div>
                    <?php endif; ?>
                    <?php if ($grant_data['organization']): ?>
                    <div>団体: <?php echo esc_html($grant_data['organization']); ?></div>
                    <?php endif; ?>
                </div>
                
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                    <?php echo esc_html($grant_data['excerpt']); ?>
                </p>
            </div>
            
            <div class="px-4 pb-4 pt-3 border-t border-gray-100 dark:border-gray-700 mt-auto">
                <a href="<?php echo esc_url($grant_data['permalink']); ?>" 
                   class="block w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-center py-2 px-4 rounded-lg transition-all duration-200 text-sm font-medium">
                    詳細を見る
                </a>
            </div>
        </div>
    </div>
    <?php
    $grant_data['html'] = ob_get_clean();
    
    return apply_filters('gi_format_grant_data', $grant_data, $post_id);
}

/**
 * 統合ページネーション生成
 */
function gi_generate_pagination_unified($query, $params) {
    if ($query->max_num_pages <= 1) {
        return '';
    }

    $pagination_args = array(
        'base' => add_query_arg('page', '%#%'),
        'format' => '',
        'current' => $params['page'],
        'total' => $query->max_num_pages,
        'type' => 'array',
        'prev_text' => '<i class="fas fa-chevron-left"></i>',
        'next_text' => '<i class="fas fa-chevron-right"></i>',
        'show_all' => false,
        'end_size' => 1,
        'mid_size' => 2
    );

    $pagination_links = paginate_links($pagination_args);
    
    if (is_array($pagination_links)) {
        $pagination_html = '<div class="gi-pagination flex flex-wrap gap-2 justify-center items-center">';
        foreach ($pagination_links as $link) {
            $pagination_html .= '<div class="pagination-item">' . $link . '</div>';
        }
        $pagination_html .= '</div>';
        return $pagination_html;
    }

    return '';
}

/**
 * 検索統計計算
 */
function gi_calculate_search_stats($grants, $total_found) {
    $stats = array(
        'total_found' => $total_found,
        'grants_count' => count($grants),
        'categories' => array(),
        'prefectures' => array(),
        'status_distribution' => array(),
        'average_amount' => 0
    );

    if (!empty($grants)) {
        $amounts = array();
        
        foreach ($grants as $grant) {
            // カテゴリ統計
            if (!empty($grant['main_category'])) {
                $stats['categories'][$grant['main_category']] = ($stats['categories'][$grant['main_category']] ?? 0) + 1;
            }
            
            // 都道府県統計
            if (!empty($grant['prefecture'])) {
                $stats['prefectures'][$grant['prefecture']] = ($stats['prefectures'][$grant['prefecture']] ?? 0) + 1;
            }
            
            // ステータス統計
            if (!empty($grant['status'])) {
                $stats['status_distribution'][$grant['status']] = ($stats['status_distribution'][$grant['status']] ?? 0) + 1;
            }
            
            // 金額統計
            if (!empty($grant['amount_numeric']) && is_numeric($grant['amount_numeric'])) {
                $amounts[] = intval($grant['amount_numeric']);
            }
        }
        
        // 平均金額計算
        if (!empty($amounts)) {
            $stats['average_amount'] = array_sum($amounts) / count($amounts);
        }
    }

    return $stats;
}

/**
 * ステータスバッジクラス取得
 */
function gi_get_status_badge_class($status) {
    $classes = array(
        'open' => 'bg-green-100 text-green-700',
        'recruiting' => 'bg-green-100 text-green-700',
        'closed' => 'bg-red-100 text-red-700',
        'ended' => 'bg-red-100 text-red-700',
        'upcoming' => 'bg-yellow-100 text-yellow-700',
        'preparing' => 'bg-blue-100 text-blue-700',
        'draft' => 'bg-gray-100 text-gray-700'
    );
    
    return $classes[$status] ?? $classes['open'];
}

// 統合検索システム用アクション登録
add_action('wp_ajax_gi_unified_search_handler', 'gi_unified_search_handler');
add_action('wp_ajax_nopriv_gi_unified_search_handler', 'gi_unified_search_handler');
?>
/**
 * AJAX - お気に入り機能
 */
function gi_ajax_toggle_favorite() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('セキュリティチェックに失敗しました');
    }
    
    $post_id = intval($_POST['post_id']);
    $user_id = get_current_user_id();
    
    if (!$post_id || !get_post($post_id)) {
        wp_send_json_error('無効な投稿IDです');
    }
    
    if (!$user_id) {
        // 非ログインユーザーの場合はCookie使用
        $cookie_name = 'gi_favorites';
        $favorites = isset($_COOKIE[$cookie_name]) ? array_filter(array_map('intval', explode(',', $_COOKIE[$cookie_name]))) : array();
        
        if (in_array($post_id, $favorites)) {
            $favorites = array_diff($favorites, array($post_id));
            $action = 'removed';
            $icon_class = 'far';
        } else {
            $favorites[] = $post_id;
            $action = 'added';
            $icon_class = 'fas';
        }
        
        setcookie($cookie_name, implode(',', $favorites), time() + (86400 * 30), '/', '', is_ssl(), true);
    } else {
        // ログインユーザーの場合
        $favorites = gi_get_user_favorites($user_id);
        
        if (in_array($post_id, $favorites)) {
            $favorites = array_diff($favorites, array($post_id));
            $action = 'removed';
            $icon_class = 'far';
        } else {
            $favorites[] = $post_id;
            $action = 'added';
            $icon_class = 'fas';
        }
        
        update_user_meta($user_id, 'gi_favorites', $favorites);
    }
    
    wp_send_json_success(array(
        'action' => $action,
        'post_id' => $post_id,
        'post_title' => gi_safe_escape(get_the_title($post_id)),
        'count' => count($favorites),
        'is_favorite' => $action === 'added',
        'icon_class' => $icon_class,
        'message' => $action === 'added' ? 'お気に入りに追加しました' : 'お気に入りから削除しました'
    ));
}
// 統一されたお気に入りアクション名
add_action('wp_ajax_gi_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_nopriv_gi_toggle_favorite', 'gi_ajax_toggle_favorite');
// 互換性維持用
add_action('wp_ajax_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_nopriv_toggle_favorite', 'gi_ajax_toggle_favorite');

/**
 * 検索サジェスト取得 - Phase 5完全強化版
 */
function gi_get_search_suggestions() {
    // セキュリティ検証
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error(array(
            'message' => 'セキュリティエラー',
            'code' => 'security_error'
        ));
    }

    $query = sanitize_text_field($_POST['query'] ?? $_POST['keyword'] ?? '');
    $limit = intval($_POST['limit'] ?? 8);
    $limit = max(1, min(20, $limit)); // 1-20の範囲で制限
    
    if (strlen($query) < 2) {
        wp_send_json_success(array());
    }

    $suggestions = array();
    $query_lower = mb_strtolower($query, 'UTF-8');
    
    try {
        // 1. 助成金タイトルから高精度検索
        $grant_query = new WP_Query(array(
            'post_type' => 'grant',
            'posts_per_page' => 6,
            's' => $query,
            'post_status' => 'publish',
            'fields' => 'ids',
            'orderby' => 'relevance',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'grant_amount',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'grant_status',
                    'value' => 'active',
                    'compare' => '='
                )
            )
        ));
        
        foreach ($grant_query->posts as $post_id) {
            $title = get_the_title($post_id);
            $amount = get_post_meta($post_id, 'grant_amount', true);
            $status = get_post_meta($post_id, 'grant_status', true);
            
            $suggestions[] = array(
                'type' => 'grant',
                'text' => $title,
                'icon' => 'fa-coins',
                'label' => '助成金',
                'meta' => array(
                    'post_id' => $post_id,
                    'amount' => $amount,
                    'status' => $status
                ),
                'priority' => 10
            );
        }
        
        // 2. カテゴリからの部分一致検索
        $categories = get_terms(array(
            'taxonomy' => 'grant_category',
            'search' => $query,
            'number' => 4,
            'orderby' => 'count',
            'order' => 'DESC',
            'hide_empty' => true
        ));
        
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $count = $category->count;
                $suggestions[] = array(
                    'type' => 'category',
                    'text' => $category->name,
                    'icon' => 'fa-folder',
                    'label' => 'カテゴリ',
                    'count' => $count,
                    'meta' => array(
                        'term_id' => $category->term_id,
                        'taxonomy' => 'grant_category'
                    ),
                    'priority' => 8
                );
            }
        }
        
        // 3. 都道府県からの検索
        $prefectures = get_terms(array(
            'taxonomy' => 'grant_prefecture',
            'search' => $query,
            'number' => 3,
            'orderby' => 'count',
            'order' => 'DESC',
            'hide_empty' => true
        ));
        
        if (!is_wp_error($prefectures)) {
            foreach ($prefectures as $prefecture) {
                $count = $prefecture->count;
                $suggestions[] = array(
                    'type' => 'prefecture',
                    'text' => $prefecture->name,
                    'icon' => 'fa-map-marker-alt',
                    'label' => '地域',
                    'count' => $count,
                    'meta' => array(
                        'term_id' => $prefecture->term_id,
                        'taxonomy' => 'grant_prefecture'
                    ),
                    'priority' => 7
                );
            }
        }
        
        // 4. 業界・分野からの検索
        $industries = get_terms(array(
            'taxonomy' => 'grant_industry',
            'search' => $query,
            'number' => 2,
            'orderby' => 'count',
            'order' => 'DESC',
            'hide_empty' => true
        ));
        
        if (!is_wp_error($industries)) {
            foreach ($industries as $industry) {
                $count = $industry->count;
                $suggestions[] = array(
                    'type' => 'industry',
                    'text' => $industry->name,
                    'icon' => 'fa-industry',
                    'label' => '業界',
                    'count' => $count,
                    'meta' => array(
                        'term_id' => $industry->term_id,
                        'taxonomy' => 'grant_industry'
                    ),
                    'priority' => 6
                );
            }
        }
        
        // 5. カスタムキーワード辞書からの検索（高頻度検索語）
        $popular_keywords = array(
            'スタートアップ', 'IT', 'DX', 'デジタル', '環境', 'エネルギー', 
            '補助金', '創業', '新規事業', '設備投資', '研究開発', 'R&D',
            '中小企業', '地方創生', 'SDGs', 'AI', 'IoT', '観光'
        );
        
        foreach ($popular_keywords as $keyword) {
            if (mb_strpos(mb_strtolower($keyword, 'UTF-8'), $query_lower) !== false || 
                mb_strpos($query_lower, mb_strtolower($keyword, 'UTF-8')) !== false) {
                $suggestions[] = array(
                    'type' => 'keyword',
                    'text' => $keyword,
                    'icon' => 'fa-tag',
                    'label' => 'キーワード',
                    'priority' => 5
                );
            }
        }
        
        // 6. 金額範囲の提案
        if (preg_match('/(\d+)万?円?/', $query, $matches)) {
            $amount = intval($matches[1]);
            if ($amount > 0) {
                $suggestions[] = array(
                    'type' => 'amount',
                    'text' => $amount . '万円以下',
                    'icon' => 'fa-yen-sign',
                    'label' => '金額',
                    'meta' => array('amount' => $amount),
                    'priority' => 4
                );
            }
        }
        
        // 優先度順でソート
        usort($suggestions, function($a, $b) {
            return ($b['priority'] ?? 0) - ($a['priority'] ?? 0);
        });
        
        // 重複除去（テキストベース）
        $unique_suggestions = array();
        $seen_texts = array();
        
        foreach ($suggestions as $suggestion) {
            $text_key = mb_strtolower($suggestion['text'], 'UTF-8');
            if (!in_array($text_key, $seen_texts)) {
                $seen_texts[] = $text_key;
                $unique_suggestions[] = $suggestion;
            }
        }
        
        // 制限数まで切り詰め
        $final_suggestions = array_slice($unique_suggestions, 0, $limit);
        
        // 統計情報の追加
        $response_data = $final_suggestions;
        
        // デバッグ情報（WP_DEBUGが有効な場合のみ）
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[GI Suggestions] Query: ' . $query . ' | Results: ' . count($final_suggestions));
        }
        
        wp_send_json_success($response_data);
        
    } catch (Exception $e) {
        error_log('[GI Suggestions Error] ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => 'サジェスト取得中にエラーが発生しました',
            'code' => 'suggestion_error'
        ));
    }
}
add_action('wp_ajax_gi_get_search_suggestions', 'gi_get_search_suggestions');
add_action('wp_ajax_nopriv_gi_get_search_suggestions', 'gi_get_search_suggestions');

// =============================================================================
// 7. ADMIN FUNCTIONS
// =============================================================================

/**
 * 管理画面用スクリプト
 */
function gi_admin_enqueue_scripts($hook) {
    wp_enqueue_style('gi-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), GI_THEME_VERSION);
    wp_enqueue_script('gi-admin-js', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), GI_THEME_VERSION, true);
    
    wp_localize_script('gi-admin-js', 'giAdmin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('gi_admin_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'gi_admin_enqueue_scripts');

/**
 * 管理画面カスタマイズ
 */
function gi_admin_init() {
    add_filter('manage_grant_posts_columns', 'gi_add_grant_columns');
    add_action('manage_grant_posts_custom_column', 'gi_grant_column_content', 10, 2);
    add_filter('manage_edit-grant_sortable_columns', 'gi_grant_sortable_columns');
}
add_action('admin_init', 'gi_admin_init');

/**
 * 助成金一覧にカスタムカラムを追加
 */
function gi_add_grant_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['gi_prefecture'] = '都道府県';
            $new_columns['gi_amount'] = '金額';
            $new_columns['gi_organization'] = '実施組織';
            $new_columns['gi_status'] = 'ステータス';
            $new_columns['gi_views'] = '閲覧数';
        }
    }
    return $new_columns;
}

/**
 * カスタムカラムに内容を表示
 */
function gi_grant_column_content($column, $post_id) {
    switch ($column) {
        case 'gi_prefecture':
            $prefecture_terms = get_the_terms($post_id, 'grant_prefecture');
            if ($prefecture_terms && !is_wp_error($prefecture_terms)) {
                echo gi_safe_escape($prefecture_terms[0]->name);
            } else {
                echo '－';
            }
            break;
            
        case 'gi_amount':
            $amount = gi_safe_get_meta($post_id, 'max_amount_numeric', 0);
            echo gi_format_amount_with_unit($amount);
            break;
            
        case 'gi_organization':
            echo gi_safe_escape(gi_safe_get_meta($post_id, 'organization', '－'));
            break;
            
        case 'gi_status':
            $status = gi_map_application_status_ui(gi_safe_get_meta($post_id, 'application_status', 'open'));
            $status_labels = array(
                '募集中' => '<span style="color: #059669;">募集中</span>',
                '準備中' => '<span style="color: #d97706;">募集予定</span>',
                '終了' => '<span style="color: #dc2626;">募集終了</span>'
            );
            echo $status_labels[$status] ?? $status;
            break;
            
        case 'gi_views':
            echo number_format(gi_get_post_views($post_id));
            break;
    }
}

/**
 * ソート可能カラム設定
 */
function gi_grant_sortable_columns($columns) {
    $columns['gi_amount'] = 'max_amount_numeric';
    $columns['gi_status'] = 'application_status';
    $columns['gi_views'] = 'views_count';
    return $columns;
}

// =============================================================================
// 8. INITIAL SETUP
// =============================================================================

/**
 * テーマ有効化時に実行されるメインのセットアップ関数
 */
function gi_theme_activation_setup() {
    gi_insert_default_prefectures();
    gi_insert_default_categories();
    gi_insert_default_industries();
    gi_insert_sample_grants_with_prefectures();
    flush_rewrite_rules();
    update_option('gi_initial_setup_completed', current_time('timestamp'));
}
add_action('after_switch_theme', 'gi_theme_activation_setup');

/**
 * デフォルト都道府県データの挿入
 */
function gi_insert_default_prefectures() {
    $prefectures = array(
        '全国対応', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
        '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
        '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
        '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
        '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
        '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
        '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
    );

    foreach ($prefectures as $prefecture) {
        if (!term_exists($prefecture, 'grant_prefecture')) {
            wp_insert_term($prefecture, 'grant_prefecture');
        }
    }
}

/**
 * デフォルトカテゴリーデータの挿入
 */
function gi_insert_default_categories() {
    $grant_categories = array(
        'IT・デジタル化支援',
        '設備投資・機械導入',
        '人材育成・教育訓練',
        '研究開発・技術革新',
        '省エネ・環境対策',
        '事業承継・M&A',
        '海外展開・輸出促進',
        '創業・起業支援',
        '販路開拓・マーケティング',
        '働き方改革・労働環境',
        '観光・地域振興',
        '農業・林業・水産業',
        '製造業・ものづくり',
        'サービス業・小売業',
        'コロナ対策・事業継続',
        '女性・若者・シニア支援',
        '障がい者雇用支援',
        '知的財産・特許',
        'BCP・リスク管理',
        'その他・汎用'
    );

    foreach ($grant_categories as $category) {
        if (!term_exists($category, 'grant_category')) {
            wp_insert_term($category, 'grant_category');
        }
    }
}

/**
 * デフォルト業種データの挿入
 */
function gi_insert_default_industries() {
    $industries = array(
        '製造業',
        '建設業',
        '情報通信業',
        '運輸業',
        '卸売・小売業',
        '金融・保険業',
        '不動産業',
        '飲食サービス業',
        '宿泊業',
        '医療・福祉',
        '教育・学習支援業',
        'サービス業',
        '農林漁業',
        'その他'
    );

    foreach ($industries as $industry) {
        if (!term_exists($industry, 'grant_industry')) {
            wp_insert_term($industry, 'grant_industry');
        }
    }
}

/**
 * サンプル助成金データの投入
 */
function gi_insert_sample_grants_with_prefectures() {
    $sample_grants = [
        [
            'title' => '【サンプル】IT導入補助金2025',
            'content' => 'ITツールの導入により生産性向上を図る中小企業・小規模事業者等を支援する補助金制度です。業務効率化・売上向上に資するITツール導入費用の一部を補助します。',
            'prefecture' => '全国対応',
            'amount' => 4500000,
            'category' => 'IT・デジタル化支援',
            'industry' => '全業種',
            'difficulty' => 'normal',
            'success_rate' => 75,
            'subsidy_rate' => '1/2以内',
            'target' => '中小企業・小規模事業者',
            'organization' => '独立行政法人中小企業基盤整備機構',
            'deadline_days' => 90,
            'is_featured' => true
        ],
        [
            'title' => '【サンプル】東京都中小企業DX推進補助金',
            'content' => '都内中小企業のデジタルトランスフォーメーション（DX）推進を支援する東京都独自の補助金制度です。AI・IoT・クラウド導入等を幅広く対象としています。',
            'prefecture' => '東京都',
            'amount' => 3000000,
            'category' => 'IT・デジタル化支援',
            'industry' => '情報通信業',
            'difficulty' => 'easy',
            'success_rate' => 85,
            'subsidy_rate' => '2/3以内',
            'target' => '都内に事業所を持つ中小企業',
            'organization' => '東京都産業労働局',
            'deadline_days' => 60,
            'is_featured' => false
        ],
        [
            'title' => '【サンプル】大阪府ものづくり補助金',
            'content' => '大阪府内の製造業者が行う新製品・サービス開発や生産プロセスの改善等に要する設備投資等を支援する補助金制度です。',
            'prefecture' => '大阪府',
            'amount' => 10000000,
            'category' => '製造業・ものづくり',
            'industry' => '製造業',
            'difficulty' => 'hard',
            'success_rate' => 60,
            'subsidy_rate' => '1/2、2/3',
            'target' => '大阪府内の製造業者',
            'organization' => '大阪府商工労働部',
            'deadline_days' => 120,
            'is_featured' => true
        ]
    ];
    
    foreach ($sample_grants as $grant_data) {
        if (!get_page_by_title($grant_data['title'], OBJECT, 'grant')) {
            
            $deadline_timestamp = strtotime('+' . $grant_data['deadline_days'] . ' days');
            
            $post_id = wp_insert_post([
                'post_title'   => $grant_data['title'],
                'post_content' => $grant_data['content'],
                'post_excerpt' => wp_trim_words($grant_data['content'], 20),
                'post_type'    => 'grant',
                'post_status'  => 'publish',
                'meta_input'   => [
                    'max_amount'         => number_format($grant_data['amount'] / 10000) . '万円',
                    'max_amount_numeric' => $grant_data['amount'],
                    'deadline_date'      => $deadline_timestamp,
                    'organization'       => $grant_data['organization'],
                    'application_status' => 'open',
                    'grant_difficulty'   => $grant_data['difficulty'],
                    'grant_success_rate' => $grant_data['success_rate'],
                    'subsidy_rate'       => $grant_data['subsidy_rate'],
                    'grant_target'       => $grant_data['target'],
                    'is_featured'        => $grant_data['is_featured'],
                    'views_count'        => rand(150, 800),
                    'application_period' => date('Y年n月j日', $deadline_timestamp - (86400 * $grant_data['deadline_days'])) . ' ～ ' . date('Y年n月j日', $deadline_timestamp),
                    'eligible_expenses'  => '設備費、システム導入費、コンサルティング費等',
                    'application_method' => 'オンライン申請',
                    'contact_info'       => $grant_data['organization'] . ' 補助金担当窓口',
                    'required_documents' => '申請書、事業計画書、見積書、会社概要等'
                ]
            ]);
            
            if ($post_id && !is_wp_error($post_id)) {
                wp_set_object_terms($post_id, $grant_data['prefecture'], 'grant_prefecture');
                wp_set_object_terms($post_id, $grant_data['category'], 'grant_category');
                if (isset($grant_data['industry'])) {
                    wp_set_object_terms($post_id, $grant_data['industry'], 'grant_industry');
                }
            }
        }
    }
}

// =============================================================================
// 9. CUSTOMIZER
// =============================================================================

/**
 * カスタマイザー設定
 */
function gi_customize_register($wp_customize) {
    // サイトカラー設定
    $wp_customize->add_section('gi_colors', array(
        'title' => 'サイトカラー',
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('gi_primary_color', array(
        'default' => '#059669',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'gi_primary_color', array(
        'label' => 'プライマリカラー',
        'section' => 'gi_colors',
    )));
    
    $wp_customize->add_setting('gi_secondary_color', array(
        'default' => '#3b82f6',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'gi_secondary_color', array(
        'label' => 'セカンダリカラー',
        'section' => 'gi_colors',
    )));
}
add_action('customize_register', 'gi_customize_register');

// =============================================================================
// 10. SECURITY AND OPTIMIZATION
// =============================================================================

/**
 * セキュリティ強化
 */
function gi_security_enhancements() {
    // 不要な情報の削除
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // XML-RPC無効化
    add_filter('xmlrpc_enabled', '__return_false');
}
add_action('init', 'gi_security_enhancements');

/**
 * パフォーマンス最適化
 */
function gi_performance_optimizations() {
    // クエリストリング削除
    add_filter('script_loader_src', 'gi_remove_query_strings', 15, 1);
    add_filter('style_loader_src', 'gi_remove_query_strings', 15, 1);
    
    // アバターにLazy Loading追加
    add_filter('get_avatar', function($avatar) {
        return str_replace('src=', 'loading="lazy" src=', $avatar);
    });
}
add_action('init', 'gi_performance_optimizations');

/**
 * クエリストリング削除関数
 */
function gi_remove_query_strings($src) {
    if (strpos($src, '?ver=') || strpos($src, '&ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

// =============================================================================
// ADDITIONAL HELPER FUNCTIONS FOR UNIFIED SEARCH SYSTEM
// =============================================================================

/**
 * 投稿ビュー数取得
 */
if (!function_exists('gi_get_post_views')) {
    function gi_get_post_views($post_id) {
        $views = get_post_meta($post_id, 'grant_views', true);
        return intval($views);
    }
}

/**
 * 投稿ビュー数カウント
 */
if (!function_exists('gi_set_post_views')) {
    function gi_set_post_views($post_id) {
        $key = 'grant_views';
        $count = get_post_meta($post_id, $key, true);
        $count = empty($count) ? 1 : ($count + 1);
        update_post_meta($post_id, $key, $count);
    }
}

/**
 * 助成金の詳細表示でビューカウント
 */
if (!function_exists('gi_track_grant_views')) {
    function gi_track_grant_views() {
        if (is_singular('grant')) {
            gi_set_post_views(get_the_ID());
        }
    }
    add_action('wp_head', 'gi_track_grant_views');
}

// デバッグ用
if (WP_DEBUG) {
    error_log('Grant Insight Complete functions.php loaded successfully - Version ' . GI_THEME_VERSION);
}

/* 助成金・補助金テーマ完全版 - 全機能実装済み */
?>