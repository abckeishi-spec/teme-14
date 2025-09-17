<?php
/**
 * Grant Insight Perfect - Header Template (Unified Search System Edition)
 * 統合検索システム完全対応ヘッダー
 * 
 * @version 24.0-unified
 * @package Grant_Insight_Perfect
 */

if (!defined('ABSPATH')) {
    exit;
}

// 現在のページ情報
$current_page = array(
    'is_home' => is_front_page() || is_home(),
    'is_grants' => is_page('grants') || is_post_type_archive('grant'),
    'is_single_grant' => is_singular('grant'),
    'is_search' => is_search(),
    'page_type' => get_post_type()
);

// 統計データ取得（キャッシュ対応）
$stats_cache_key = 'gi_header_stats_' . date('YmdH');
$stats = get_transient($stats_cache_key);

if (false === $stats) {
    $stats = array(
        'total_grants' => wp_count_posts('grant')->publish ?: 0,
        'total_tools' => wp_count_posts('tool')->publish ?: 0,
        'total_guides' => wp_count_posts('guide')->publish ?: 0,
        'total_cases' => wp_count_posts('case_study')->publish ?: 0
    );
    set_transient($stats_cache_key, $stats, HOUR_IN_SECONDS);
}

// nonceの生成
$search_nonce = wp_create_nonce('gi_ajax_nonce');

// カテゴリと都道府県の取得（キャッシュ対応）
$categories_cache_key = 'gi_grant_categories_' . date('Ymd');
$grant_categories = get_transient($categories_cache_key);

if (false === $grant_categories) {
    $grant_categories = get_terms(array(
        'taxonomy' => 'grant_category',
        'hide_empty' => false,
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 20
    ));
    
    if (!is_wp_error($grant_categories)) {
        set_transient($categories_cache_key, $grant_categories, DAY_IN_SECONDS);
    } else {
        $grant_categories = array();
    }
}

// 都道府県リスト
$prefectures = array(
    '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
    '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
    '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
    '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
    '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
    '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
    '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
);

// 人気検索キーワード
$popular_keywords = array(
    'IT導入補助金', 'ものづくり補助金', '事業再構築補助金', 
    '小規模事業者持続化補助金', '雇用調整助成金', 'DX推進'
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Noto Sans JP', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+JP:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
    
    <!-- 統合検索システム設定 -->
    <script>
        window.giSearchConfig = {
            ajaxUrl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
            nonce: '<?php echo esc_js($search_nonce); ?>',
            grantsUrl: '<?php echo esc_js(home_url('/grants/')); ?>',
            homeUrl: '<?php echo esc_js(home_url('/')); ?>',
            debug: <?php echo WP_DEBUG ? 'true' : 'false'; ?>
        };
    </script>
</head>

<body <?php body_class('unified-search-enabled'); ?>>
<?php wp_body_open(); ?>

<!-- スキップリンク -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-lg z-50">
    メインコンテンツへスキップ
</a>

<!-- 🎯 統合検索対応ヘッダー -->
<header id="site-header" class="site-header sticky top-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 transition-all duration-300">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            <!-- ロゴエリア -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center group">
                    <img 
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" 
                        alt="<?php bloginfo('name'); ?>" 
                        class="h-10 lg:h-12 w-auto transition-transform group-hover:scale-105"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="hidden items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl">
                        <span class="text-white font-bold text-xl">GI</span>
                    </div>
                    <div class="ml-3 hidden lg:block">
                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                            <?php bloginfo('name'); ?>
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">
                            助成金・補助金の総合プラットフォーム
                        </div>
                    </div>
                </a>
            </div>

            <!-- デスクトップナビゲーション -->
            <nav class="hidden lg:flex items-center gap-6">
                <a href="<?php echo esc_url(home_url('/')); ?>" 
                   class="nav-link <?php echo $current_page['is_home'] ? 'active' : ''; ?>">
                    <i class="fas fa-home mr-2"></i>
                    <span>ホーム</span>
                </a>
                
                <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
                   class="nav-link <?php echo $current_page['is_grants'] ? 'active' : ''; ?>">
                    <i class="fas fa-coins mr-2"></i>
                    <span>助成金一覧</span>
                </a>
                
                <button type="button" 
                        id="header-search-btn" 
                        class="nav-link-primary">
                    <i class="fas fa-search mr-2"></i>
                    <span>検索</span>
                </button>
                
                <a href="<?php echo esc_url(home_url('/guides/')); ?>" 
                   class="nav-link">
                    <i class="fas fa-book mr-2"></i>
                    <span>ガイド</span>
                </a>
                
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                   class="nav-link">
                    <i class="fas fa-envelope mr-2"></i>
                    <span>お問い合わせ</span>
                </a>
            </nav>

            <!-- モバイルコントロール -->
            <div class="flex lg:hidden items-center gap-2">
                <button type="button" 
                        id="mobile-search-btn" 
                        class="mobile-control-btn">
                    <i class="fas fa-search"></i>
                </button>
                
                <button type="button" 
                        id="mobile-menu-btn" 
                        class="mobile-control-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- 🔍 統合検索モーダル -->
<div id="unified-search-modal" class="search-modal" aria-hidden="true">
    <div class="search-modal-overlay"></div>
    <div class="search-modal-content">
        
        <!-- モーダルヘッダー -->
        <div class="modal-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-search text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            助成金・補助金を検索
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <?php echo number_format($stats['total_grants']); ?>件から最適な助成金を見つけましょう
                        </p>
                    </div>
                </div>
                <button type="button" 
                        id="close-search-modal" 
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- 統計情報 -->
        <div class="modal-stats">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['total_grants']); ?></div>
                    <div class="stat-label">助成金</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['total_tools']); ?></div>
                    <div class="stat-label">ツール</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['total_guides']); ?></div>
                    <div class="stat-label">ガイド</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['total_cases']); ?></div>
                    <div class="stat-label">事例</div>
                </div>
            </div>
        </div>

        <!-- 検索フォーム -->
        <form id="unified-search-form" class="modal-search-form">
            <!-- メイン検索 -->
            <div class="search-input-group">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        id="gi-search-input-unified" data-source="header" data-legacy-id="search-keyword" 
                        name="keyword"
                        class="search-input"
                        placeholder="キーワードで検索（例：IT導入補助金、ものづくり補助金）"
                        autocomplete="off"
                    >
                    <button type="button" 
                            id="gi-clear-btn" data-source="header" data-legacy-id="clear-search" 
                            class="clear-search-btn hidden">
                        <i class="fas fa-times"></i>
                    </button>
                    <button type="button" 
                            id="gi-voice-btn" data-source="header" data-legacy-id="voice-search" 
                            class="gi-voice-btn" 
                            title="音声検索">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>
                <button type="submit" 
                        id="gi-search-btn-unified" data-source="header" data-legacy-id="execute-search" 
                        class="search-submit-btn">
                    <span class="btn-text">検索</span>
                    <span class="btn-loading hidden">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                </button>
            </div>

            <!-- 人気キーワード -->
            <div class="popular-keywords">
                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    <i class="fas fa-fire text-orange-500 mr-2"></i>
                    人気の検索キーワード
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($popular_keywords as $keyword): ?>
                        <button type="button" 
                                class="keyword-tag" 
                                data-keyword="<?php echo esc_attr($keyword); ?>">
                            <?php echo esc_html($keyword); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 詳細フィルター -->
            <div class="advanced-filters">
                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    詳細条件
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- カテゴリ -->
                    <div>
                        <label class="filter-label">
                            <i class="fas fa-folder mr-1"></i>
                            カテゴリ
                        </label>
                        <select id="filter-category" name="category" class="filter-select">
                            <option value="">すべてのカテゴリ</option>
                            <?php foreach ($grant_categories as $category): ?>
                                <option value="<?php echo esc_attr($category->slug); ?>">
                                    <?php echo esc_html($category->name); ?>
                                    (<?php echo $category->count; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 地域 -->
                    <div>
                        <label class="filter-label">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            地域
                        </label>
                        <select id="filter-prefecture" name="prefecture" class="filter-select">
                            <option value="">全国</option>
                            <?php foreach ($prefectures as $prefecture): ?>
                                <option value="<?php echo esc_attr($prefecture); ?>">
                                    <?php echo esc_html($prefecture); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 金額 -->
                    <div>
                        <label class="filter-label">
                            <i class="fas fa-yen-sign mr-1"></i>
                            助成金額
                        </label>
                        <select id="filter-amount" name="amount" class="filter-select">
                            <option value="">指定なし</option>
                            <option value="0-100">〜100万円</option>
                            <option value="100-500">100〜500万円</option>
                            <option value="500-1000">500〜1000万円</option>
                            <option value="1000-3000">1000〜3000万円</option>
                            <option value="3000+">3000万円〜</option>
                        </select>
                    </div>

                    <!-- ステータス -->
                    <div>
                        <label class="filter-label">
                            <i class="fas fa-circle mr-1"></i>
                            募集状況
                        </label>
                        <select id="filter-status" name="status" class="filter-select">
                            <option value="">すべて</option>
                            <option value="active">募集中</option>
                            <option value="upcoming">募集予定</option>
                            <option value="closed">募集終了</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- アクションボタン -->
            <div class="modal-actions">
                <button type="button" 
                        id="reset-filters" 
                        class="reset-btn">
                    <i class="fas fa-undo mr-2"></i>
                    リセット
                </button>
                <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
                   class="view-all-link">
                    すべての助成金を見る
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- 📱 モバイルメニュー -->
<div id="mobile-menu" class="mobile-menu">
    <div class="mobile-menu-overlay"></div>
    <div class="mobile-menu-panel">
        <div class="mobile-menu-header">
            <div class="text-lg font-bold text-gray-900 dark:text-white">メニュー</div>
            <button type="button" 
                    id="close-mobile-menu" 
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <nav class="mobile-menu-nav">
            <a href="<?php echo esc_url(home_url('/')); ?>" 
               class="mobile-menu-item <?php echo $current_page['is_home'] ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>ホーム</span>
            </a>
            
            <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
               class="mobile-menu-item <?php echo $current_page['is_grants'] ? 'active' : ''; ?>">
                <i class="fas fa-coins"></i>
                <span>助成金一覧</span>
            </a>
            
            <button type="button" 
                    id="mobile-menu-search" 
                    class="mobile-menu-item">
                <i class="fas fa-search"></i>
                <span>検索</span>
            </button>
            
            <a href="<?php echo esc_url(home_url('/guides/')); ?>" 
               class="mobile-menu-item">
                <i class="fas fa-book"></i>
                <span>ガイド</span>
            </a>
            
            <a href="<?php echo esc_url(home_url('/tools/')); ?>" 
               class="mobile-menu-item">
                <i class="fas fa-tools"></i>
                <span>ツール</span>
            </a>
            
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
               class="mobile-menu-item">
                <i class="fas fa-envelope"></i>
                <span>お問い合わせ</span>
            </a>
        </nav>
        
        <div class="mobile-menu-footer">
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
            </div>
        </div>
    </div>
</div>

<!-- メインコンテンツ開始 -->
<main id="main-content" class="main-content">

<!-- 🎨 ヘッダー専用スタイル -->
<style>
/* ナビゲーションリンク */
.nav-link {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    color: #4b5563;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.nav-link:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.nav-link.active {
    background: #eff6ff;
    color: #2563eb;
}

.nav-link-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1.5rem;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    font-weight: 600;
    border-radius: 9999px;
    transition: all 0.3s;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.nav-link-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* モバイルコントロール */
.mobile-control-btn {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.mobile-control-btn:hover {
    background: #e5e7eb;
    color: #1f2937;
}

/* 検索モーダル */
.search-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    animation: fadeIn 0.3s ease;
}

.search-modal[aria-hidden="false"] {
    display: block;
}

.search-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.search-modal-content {
    position: relative;
    max-width: 48rem;
    margin: 2rem auto;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    max-height: calc(100vh - 4rem);
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-stats {
    padding: 1.5rem;
    background: #f9fafb;
}

.stat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.modal-search-form {
    padding: 1.5rem;
}

.search-input-group {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem;
    padding-right: 2.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.clear-search-btn {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    transition: color 0.2s;
}

.clear-search-btn:hover {
    color: #4b5563;
}

/* 統合検索システムのボタンスタイルはsearch-config.jsで管理されるため削除 */

.popular-keywords {
    margin-bottom: 1.5rem;
}

.keyword-tag {
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 9999px;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.keyword-tag:hover {
    background: #3b82f6;
    color: white;
}

.advanced-filters {
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.filter-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    margin-bottom: 0.5rem;
}

.filter-select {
    width: 100%;
    padding: 0.625rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modal-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    margin-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.reset-btn {
    padding: 0.625rem 1.25rem;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s;
}

.reset-btn:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    color: #3b82f6;
    font-weight: 500;
    transition: color 0.2s;
}

.view-all-link:hover {
    color: #2563eb;
}

/* モバイルメニュー */
.mobile-menu {
    position: fixed;
    inset: 0;
    z-index: 9998;
    display: none;
}

.mobile-menu.active {
    display: block;
}

.mobile-menu-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.mobile-menu-panel {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 20rem;
    max-width: 100%;
    background: white;
    box-shadow: -4px 0 6px -1px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    animation: slideInRight 0.3s ease;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.mobile-menu-nav {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.mobile-menu-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    color: #4b5563;
    border-radius: 0.5rem;
    transition: all 0.2s;
    width: 100%;
    text-align: left;
}

.mobile-menu-item:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.mobile-menu-item.active {
    background: #eff6ff;
    color: #2563eb;
}

.mobile-menu-footer {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

/* アニメーション */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

/* ダークモード対応 */
@media (prefers-color-scheme: dark) {
    .dark\:bg-gray-900\/95 {
        background: rgba(17, 24, 39, 0.95);
    }
    
    .dark\:border-gray-800 {
        border-color: #1f2937;
    }
    
    .dark\:text-white {
        color: white;
    }
    
    .dark\:text-gray-400 {
        color: #9ca3af;
    }
    
    .dark\:text-gray-300 {
        color: #d1d5db;
    }
    
    .dark\:hover\:text-gray-200:hover {
        color: #e5e7eb;
    }
    
    .search-modal-content {
        background: #1f2937;
    }
    
    .modal-header,
    .modal-stats {
        background: #111827;
        border-color: #374151;
    }
    
    .stat-card {
        background: #1f2937;
    }
    
    .search-input,
    .filter-select {
        background: #111827;
        border-color: #374151;
        color: white;
    }
    
    .keyword-tag {
        background: #374151;
        color: #d1d5db;
    }
    
    .mobile-menu-panel {
        background: #1f2937;
    }
    
    .mobile-menu-header,
    .mobile-menu-footer {
        border-color: #374151;
    }
}

/* レスポンシブ */
@media (max-width: 1024px) {
    .search-modal-content {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
}

@media (max-width: 640px) {
    .modal-stats .grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .advanced-filters .grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- 🚀 ヘッダー専用JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    console.log('🎯 統合検索対応ヘッダー初期化開始');
    
    // DOM要素の取得
    const elements = {
        // 検索モーダル
        searchModal: document.getElementById('unified-search-modal'),
        searchForm: document.getElementById('unified-search-form'),
        searchKeyword: document.getElementById('gi-search-input-unified') || document.getElementById('search-keyword'),
        clearSearch: document.getElementById('gi-clear-btn') || document.getElementById('clear-search'),
        executeSearch: document.getElementById('gi-search-btn-unified') || document.getElementById('execute-search'),
        resetFilters: document.getElementById('reset-filters'),
        closeSearchModal: document.getElementById('close-search-modal'),
        
        // トリガーボタン
        headerSearchBtn: document.getElementById('header-search-btn'),
        mobileSearchBtn: document.getElementById('mobile-search-btn'),
        mobileMenuSearch: document.getElementById('mobile-menu-search'),
        
        // モバイルメニュー
        mobileMenu: document.getElementById('mobile-menu'),
        mobileMenuBtn: document.getElementById('mobile-menu-btn'),
        closeMobileMenu: document.getElementById('close-mobile-menu'),
        
        // フィルター
        filterCategory: document.getElementById('filter-category'),
        filterPrefecture: document.getElementById('filter-prefecture'),
        filterAmount: document.getElementById('filter-amount'),
        filterStatus: document.getElementById('filter-status')
    };
    
    // 検索モーダルを開く
    function openSearchModal() {
        if (elements.searchModal) {
            elements.searchModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            
            // フォーカス管理
            setTimeout(() => {
                elements.searchKeyword?.focus();
            }, 100);
        }
    }
    
    // 検索モーダルを閉じる
    function closeSearchModal() {
        if (elements.searchModal) {
            elements.searchModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }
    
    // モバイルメニューを開く
    function openMobileMenu() {
        if (elements.mobileMenu) {
            elements.mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // モバイルメニューを閉じる
    function closeMobileMenu() {
        if (elements.mobileMenu) {
            elements.mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // 検索実行（改良版：一覧ページへの確実な遷移）
    function executeSearch(e) {
        if (e) e.preventDefault();
        
        // フォームデータを収集
        const params = new URLSearchParams();
        
        const keyword = elements.searchKeyword?.value;
        const category = elements.filterCategory?.value;
        const prefecture = elements.filterPrefecture?.value;
        const amount = elements.filterAmount?.value;
        const status = elements.filterStatus?.value;
        
        if (keyword) params.set('search', keyword);
        if (category) params.set('category', category);
        if (prefecture) params.set('prefecture', prefecture);
        if (amount) params.set('amount', amount);
        if (status) params.set('status', status);
        
        // 一覧ページへ遷移（archive-grant.phpページ）
        const searchUrl = '<?php echo home_url('/grants/'); ?>' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = searchUrl;
    }
    
    // イベントリスナーの設定
    function bindEvents() {
        // 検索モーダルを開く
        [elements.headerSearchBtn, elements.mobileSearchBtn, elements.mobileMenuSearch].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openSearchModal();
                    closeMobileMenu();
                });
            }
        });
        
        // 検索モーダルを閉じる
        if (elements.closeSearchModal) {
            elements.closeSearchModal.addEventListener('click', closeSearchModal);
        }
        
        // オーバーレイクリックで閉じる
        if (elements.searchModal) {
            elements.searchModal.addEventListener('click', (e) => {
                if (e.target === elements.searchModal || e.target.classList.contains('search-modal-overlay')) {
                    closeSearchModal();
                }
            });
        }
        
        // 検索フォーム送信
        if (elements.searchForm) {
            elements.searchForm.addEventListener('submit', executeSearch);
        }
        
        // 検索実行ボタン
        if (elements.executeSearch) {
            elements.executeSearch.addEventListener('click', executeSearch);
        }
        
        // 検索クリア
        if (elements.searchKeyword && elements.clearSearch) {
            elements.searchKeyword.addEventListener('input', (e) => {
                elements.clearSearch.classList.toggle('hidden', !e.target.value);
            });
            
            elements.clearSearch.addEventListener('click', () => {
                elements.searchKeyword.value = '';
                elements.clearSearch.classList.add('hidden');
                elements.searchKeyword.focus();
            });
        }
        
        // フィルターリセット
        if (elements.resetFilters) {
            elements.resetFilters.addEventListener('click', () => {
                elements.searchForm?.reset();
                elements.clearSearch?.classList.add('hidden');
            });
        }
        
        // キーワードタグ
        document.querySelectorAll('.keyword-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                const keyword = this.dataset.keyword;
                if (elements.searchKeyword && keyword) {
                    elements.searchKeyword.value = keyword;
                    elements.clearSearch?.classList.remove('hidden');
                }
            });
        });
        
        // モバイルメニュー
        if (elements.mobileMenuBtn) {
            elements.mobileMenuBtn.addEventListener('click', openMobileMenu);
        }
        
        if (elements.closeMobileMenu) {
            elements.closeMobileMenu.addEventListener('click', closeMobileMenu);
        }
        
        // モバイルメニューオーバーレイ
        if (elements.mobileMenu) {
            elements.mobileMenu.addEventListener('click', (e) => {
                if (e.target.classList.contains('mobile-menu-overlay')) {
                    closeMobileMenu();
                }
            });
        }
        
        // ESCキーで閉じる
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSearchModal();
                closeMobileMenu();
            }
        });
    }
    
    // 初期化
    bindEvents();
    
    console.log('✅ 統合検索対応ヘッダー初期化完了');
});
</script>

<?php
// functions.phpで統合検索システムを読み込むようにする
add_action('wp_footer', function() {
    ?>
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/unified-search.js"></script>
    <?php
}, 100);
?>
