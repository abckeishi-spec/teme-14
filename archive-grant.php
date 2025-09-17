
<?php
/**
 * Grant Archive Template - Complete Version
 * 助成金・補助金アーカイブページ完全版
 * 
 * @package Grant_Insight_Complete
 * @version 8.0.0-complete
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// URLパラメータから検索条件を取得
$search_params = array(
    'search' => sanitize_text_field($_GET['search'] ?? ''),
    'category' => sanitize_text_field($_GET['category'] ?? ''),
    'prefecture' => sanitize_text_field($_GET['prefecture'] ?? ''),
    'industry' => sanitize_text_field($_GET['industry'] ?? ''),
    'amount' => sanitize_text_field($_GET['amount'] ?? ''),
    'status' => sanitize_text_field($_GET['status'] ?? ''),
    'difficulty' => sanitize_text_field($_GET['difficulty'] ?? ''),
    'success_rate' => sanitize_text_field($_GET['success_rate'] ?? ''),
    'orderby' => sanitize_text_field($_GET['orderby'] ?? 'date_desc'),
    'view' => sanitize_text_field($_GET['view'] ?? 'grid'),
    'page' => max(1, intval($_GET['page'] ?? 1))
);

// カテゴリと都道府県の取得
$grant_categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'orderby' => 'count',
    'order' => 'DESC'
));

if (is_wp_error($grant_categories)) {
    $grant_categories = array();
}

$grant_prefectures = get_terms(array(
    'taxonomy' => 'grant_prefecture', 
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
));

if (is_wp_error($grant_prefectures)) {
    $grant_prefectures = array();
}

$grant_industries = get_terms(array(
    'taxonomy' => 'grant_industry',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
));

if (is_wp_error($grant_industries)) {
    $grant_industries = array();
}

get_header(); 
?>

<!-- 外部ライブラリ -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- 統合検索対応アーカイブページ -->
<div id="grant-archive-page" class="grant-archive-complete" data-search-params='<?php echo json_encode($search_params); ?>'>
    
    <!-- ヒーローセクション -->
    <section class="hero-section bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-indigo-900 dark:to-purple-900 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full text-sm font-bold mb-6 shadow-lg">
                    <i class="fas fa-database"></i>
                    <span>Grant Database System</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    <span class="block">助成金・補助金</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
                        完全データベース
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    <?php if (!empty($search_params['search'])): ?>
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/80 dark:bg-gray-800/80 rounded-lg shadow-md">
                            <i class="fas fa-search text-indigo-600"></i>
                            「<span class="font-bold text-gray-900 dark:text-white"><?php echo esc_html($search_params['search']); ?></span>」の検索結果
                        </span>
                    <?php else: ?>
                        全国の助成金から、あなたのビジネスに最適な支援制度をご提案
                    <?php endif; ?>
                </p>
                
                <!-- 統計情報（総支援額を削除） -->
                <div class="flex justify-center gap-8 text-sm">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600" id="total-grants-count">0</div>
                        <div class="text-gray-600 dark:text-gray-400">登録助成金</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="active-grants-count">0</div>
                        <div class="text-gray-600 dark:text-gray-400">募集中</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 検索・フィルターバー -->
    <section class="search-filter-bar sticky top-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shadow-lg border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4 py-4">
            <!-- メイン検索バー -->
            <div class="search-container">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- 検索入力 -->
                    <div class="flex-1">
                        <div class="search-input-group relative">
                            <div class="search-input-wrapper relative">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="text" 
                                    id="gi-search-input-unified" data-legacy-id="grant-search" 
                                    class="search-input w-full pl-12 pr-32 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                    placeholder="キーワード、業種、地域などで検索..."
                                    value="<?php echo esc_attr($search_params['search']); ?>"
                                    autocomplete="off"
                                >
                                <div class="search-actions absolute right-4 top-1/2 transform -translate-y-1/2 flex items-center gap-2">
                                    <button 
                                        id="gi-clear-btn" data-legacy-id="search-clear" 
                                        class="search-action-btn w-8 h-8 bg-gray-100 hover:bg-red-100 text-gray-400 hover:text-red-500 rounded-full flex items-center justify-center transition-all <?php echo empty($search_params['search']) ? 'hidden' : ''; ?>"
                                        title="クリア"
                                    >
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                    <button 
                                        id="gi-voice-btn" data-legacy-id="voice-search" 
                                        class="search-action-btn w-8 h-8 bg-gray-100 hover:bg-blue-100 text-gray-400 hover:text-blue-500 rounded-full flex items-center justify-center transition-all"
                                        title="音声検索"
                                    >
                                        <i class="fas fa-microphone text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- 検索サジェスト -->
                            <div id="gi-suggestions" data-legacy-id="search-suggestions" class="search-suggestions absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto hidden">
                                <!-- 動的に生成 -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- 検索ボタン -->
                    <button 
                        id="gi-search-btn-unified" data-legacy-id="search-btn" 
                        class="search-button px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-2xl transition-all transform hover:scale-105 shadow-lg"
                    >
                        <span class="btn-content flex items-center">
                            <i class="fas fa-search mr-2"></i>
                            検索
                        </span>
                        <span class="btn-loading hidden flex items-center">
                            <i class="fas fa-spinner animate-spin mr-2"></i>
                            検索中
                        </span>
                    </button>
                </div>

                <!-- クイックフィルター -->
                <div class="quick-filters mt-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 mr-2">
                            <i class="fas fa-filter mr-1"></i>
                            クイック:
                        </span>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-indigo-50 hover:border-indigo-300 transition-all <?php echo empty($search_params['status']) ? 'active bg-indigo-100 border-indigo-300' : ''; ?>"
                            data-filter="all"
                        >
                            <i class="fas fa-globe text-xs"></i>
                            すべて
                        </button>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-green-50 hover:border-green-300 transition-all <?php echo $search_params['status'] === 'active' ? 'active bg-green-100 border-green-300' : ''; ?>"
                            data-filter="active"
                        >
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            募集中
                        </button>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-yellow-50 hover:border-yellow-300 transition-all <?php echo $search_params['status'] === 'upcoming' ? 'active bg-yellow-100 border-yellow-300' : ''; ?>"
                            data-filter="upcoming"
                        >
                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                            募集予定
                        </button>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-yellow-50 hover:border-yellow-300 transition-all"
                            data-filter="high-amount"
                        >
                            <i class="fas fa-coins text-yellow-500 text-xs"></i>
                            高額補助
                        </button>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-blue-50 hover:border-blue-300 transition-all"
                            data-filter="easy"
                        >
                            <i class="fas fa-star text-blue-500 text-xs"></i>
                            申請簡単
                        </button>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-purple-50 hover:border-purple-300 transition-all"
                            data-filter="high-success"
                        >
                            <i class="fas fa-trophy text-purple-500 text-xs"></i>
                            高採択率
                        </button>
                        <button 
                            class="quick-filter-pill inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-full text-sm hover:bg-red-50 hover:border-red-300 transition-all"
                            data-filter="deadline-soon"
                        >
                            <i class="fas fa-clock text-red-500 text-xs"></i>
                            締切間近
                        </button>
                    </div>
                </div>

                <!-- コントロールバー -->
                <div class="control-bar mt-4">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <!-- ソート -->
                            <div class="control-group flex items-center gap-2">
                                <label class="control-label text-sm font-medium text-gray-600 flex items-center gap-1">
                                    <i class="fas fa-sort text-xs"></i>
                                    並び順:
                                </label>
                                <select 
                                    id="sort-order" 
                                    class="control-select border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none"
                                >
                                    <option value="date_desc" <?php selected($search_params['orderby'], 'date_desc'); ?>>新着順</option>
                                    <option value="date_asc" <?php selected($search_params['orderby'], 'date_asc'); ?>>古い順</option>
                                    <option value="amount_desc" <?php selected($search_params['orderby'], 'amount_desc'); ?>>金額が高い順</option>
                                    <option value="amount_asc" <?php selected($search_params['orderby'], 'amount_asc'); ?>>金額が低い順</option>
                                    <option value="deadline_asc" <?php selected($search_params['orderby'], 'deadline_asc'); ?>>締切が近い順</option>
                                    <option value="success_rate_desc" <?php selected($search_params['orderby'], 'success_rate_desc'); ?>>採択率順</option>
                                    <option value="popularity" <?php selected($search_params['orderby'], 'popularity'); ?>>人気順</option>
                                    <option value="title_asc" <?php selected($search_params['orderby'], 'title_asc'); ?>>名前順</option>
                                </select>
                            </div>

                            <!-- 表示件数 -->
                            <div class="control-group flex items-center gap-2">
                                <label class="control-label text-sm font-medium text-gray-600">
                                    表示:
                                </label>
                                <select 
                                    id="per-page" 
                                    class="control-select border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none"
                                >
                                    <option value="12">12件</option>
                                    <option value="24">24件</option>
                                    <option value="48">48件</option>
                                    <option value="96">96件</option>
                                </select>
                            </div>

                            <!-- 詳細フィルター -->
                            <button 
                                id="filter-toggle" 
                                class="filter-toggle-btn flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all"
                            >
                                <i class="fas fa-sliders-h text-xs"></i>
                                詳細フィルター
                                <span id="filter-count" class="filter-badge hidden bg-indigo-500 text-white text-xs px-2 py-1 rounded-full">0</span>
                            </button>

                            <!-- 保存した検索 -->
                            <button 
                                id="saved-search-toggle" 
                                class="saved-search-btn flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all"
                            >
                                <i class="fas fa-bookmark text-xs"></i>
                                保存した検索
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- 表示切替 -->
                            <div class="view-switcher flex bg-gray-100 rounded-lg p-1">
                                <button 
                                    id="grid-view" 
                                    class="view-btn px-3 py-2 rounded-md transition-all <?php echo $search_params['view'] === 'grid' ? 'active bg-white shadow' : 'text-gray-500 hover:text-gray-700'; ?>"
                                    data-view="grid"
                                    title="グリッド表示"
                                >
                                    <i class="fas fa-th text-sm"></i>
                                </button>
                                <button 
                                    id="list-view" 
                                    class="view-btn px-3 py-2 rounded-md transition-all <?php echo $search_params['view'] === 'list' ? 'active bg-white shadow' : 'text-gray-500 hover:text-gray-700'; ?>"
                                    data-view="list"
                                    title="リスト表示"
                                >
                                    <i class="fas fa-list text-sm"></i>
                                </button>
                                <button 
                                    id="compact-view" 
                                    class="view-btn px-3 py-2 rounded-md transition-all <?php echo $search_params['view'] === 'compact' ? 'active bg-white shadow' : 'text-gray-500 hover:text-gray-700'; ?>"
                                    data-view="compact"
                                    title="コンパクト表示"
                                >
                                    <i class="fas fa-bars text-sm"></i>
                                </button>
                            </div>

                            <!-- エクスポート -->
                            <div class="dropdown relative">
                                <button 
                                    id="export-toggle" 
                                    class="export-btn flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all"
                                >
                                    <i class="fas fa-download text-xs"></i>
                                    エクスポート
                                </button>
                                <div id="export-menu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden">
                                    <button class="export-option w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2" data-format="csv">
                                        <i class="fas fa-file-csv text-green-600"></i>
                                        CSVファイル
                                    </button>
                                    <button class="export-option w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2" data-format="excel">
                                        <i class="fas fa-file-excel text-green-700"></i>
                                        Excelファイル
                                    </button>
                                    <button class="export-option w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2" data-format="pdf">
                                        <i class="fas fa-file-pdf text-red-600"></i>
                                        PDFファイル
                                    </button>
                                    <button class="export-option w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2" data-format="json">
                                        <i class="fas fa-code text-blue-600"></i>
                                        JSONファイル
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- メインコンテンツエリア -->
    <section class="main-content bg-gray-50 dark:bg-gray-900 py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- サイドバーフィルター -->
                <aside id="filter-sidebar" class="lg:w-80 hidden lg:block">
                    <div class="filter-container bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-24">
                        <!-- フィルターヘッダー -->
                        <div class="filter-header flex justify-between items-center mb-6">
                            <h3 class="filter-title text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="fas fa-sliders-h text-indigo-600"></i>
                                詳細フィルター
                            </h3>
                            <button id="reset-all-filters" class="reset-filters-btn text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 transition-colors">
                                <i class="fas fa-undo text-xs"></i>
                                リセット
                            </button>
                        </div>

                        <!-- カテゴリフィルター -->
                        <div class="filter-section mb-6">
                            <div class="filter-section-header flex justify-between items-center cursor-pointer p-2 rounded-lg hover:bg-gray-50" data-toggle="categories">
                                <h4 class="filter-section-title text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-folder text-xs"></i>
                                    カテゴリ
                                </h4>
                                <i class="fas fa-chevron-down toggle-icon text-xs text-gray-400 transition-transform"></i>
                            </div>
                            <div id="categories-content" class="filter-section-content mt-3">
                                <div class="filter-search mb-3">
                                    <input 
                                        type="text" 
                                        placeholder="カテゴリを検索..." 
                                        class="filter-search-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none"
                                        data-filter="categories"
                                    >
                                </div>
                                <div class="filter-items max-h-48 overflow-y-auto space-y-1">
                                    <?php if (!empty($grant_categories)): ?>
                                        <?php foreach ($grant_categories as $category): ?>
                                            <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                                <input 
                                                    type="checkbox" 
                                                    name="category[]" 
                                                    value="<?php echo esc_attr($category->slug); ?>"
                                                    data-label="<?php echo esc_attr($category->name); ?>"
                                                    class="filter-checkbox category-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                    <?php checked($search_params['category'], $category->slug); ?>
                                                >
                                                <span class="filter-item-content flex justify-between items-center flex-1 ml-3">
                                                    <span class="filter-item-label text-sm text-gray-700">
                                                        <?php echo esc_html($category->name); ?>
                                                    </span>
                                                    <span class="filter-item-count text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                                                        <?php echo $category->count; ?>
                                                    </span>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- 地域フィルター -->
                        <div class="filter-section mb-6">
                            <div class="filter-section-header flex justify-between items-center cursor-pointer p-2 rounded-lg hover:bg-gray-50" data-toggle="prefectures">
                                <h4 class="filter-section-title text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-map-marked-alt text-xs"></i>
                                    対象地域
                                </h4>
                                <i class="fas fa-chevron-down toggle-icon text-xs text-gray-400 transition-transform"></i>
                            </div>
                            <div id="prefectures-content" class="filter-section-content mt-3">
                                <!-- 地域タブ -->
                                <div class="region-tabs flex flex-wrap gap-1 mb-3">
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="all">全国</button>
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="hokkaido-tohoku">北海道・東北</button>
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="kanto">関東</button>
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="chubu">中部</button>
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="kinki">近畿</button>
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="chugoku-shikoku">中国・四国</button>
                                    <button class="region-tab text-xs px-2 py-1 rounded bg-gray-100 hover:bg-indigo-100 transition-colors" data-region="kyushu">九州</button>
                                </div>
                                <div class="filter-search mb-3">
                                    <input 
                                        type="text" 
                                        placeholder="地域を検索..." 
                                        class="filter-search-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 outline-none"
                                        data-filter="prefectures"
                                    >
                                </div>
                                <div class="filter-items max-h-48 overflow-y-auto space-y-1">
                                    <?php if (!empty($grant_prefectures)): ?>
                                        <?php foreach ($grant_prefectures as $prefecture): ?>
                                            <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer" data-prefecture="<?php echo esc_attr($prefecture->slug); ?>">
                                                <input 
                                                    type="checkbox" 
                                                    name="prefecture[]" 
                                                    value="<?php echo esc_attr($prefecture->slug); ?>"
                                                    data-label="<?php echo esc_attr($prefecture->name); ?>"
                                                    class="filter-checkbox prefecture-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                    <?php checked($search_params['prefecture'], $prefecture->slug); ?>
                                                >
                                                <span class="filter-item-content flex justify-between items-center flex-1 ml-3">
                                                    <span class="filter-item-label text-sm text-gray-700">
                                                        <?php echo esc_html($prefecture->name); ?>
                                                    </span>
                                                    <span class="filter-item-count text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                                                        <?php echo $prefecture->count; ?>
                                                    </span>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- 業種フィルター -->
                        <div class="filter-section mb-6">
                            <div class="filter-section-header flex justify-between items-center cursor-pointer p-2 rounded-lg hover:bg-gray-50" data-toggle="industries">
                                <h4 class="filter-section-title text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-building text-xs"></i>
                                    対象業種
                                </h4>
                                <i class="fas fa-chevron-down toggle-icon text-xs text-gray-400 transition-transform"></i>
                            </div>
                            <div id="industries-content" class="filter-section-content mt-3 hidden">
                                <div class="filter-items max-h-48 overflow-y-auto space-y-1">
                                    <?php if (!empty($grant_industries)): ?>
                                        <?php foreach ($grant_industries as $industry): ?>
                                            <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                                <input 
                                                    type="checkbox" 
                                                    name="industry[]" 
                                                    value="<?php echo esc_attr($industry->slug); ?>"
                                                    data-label="<?php echo esc_attr($industry->name); ?>"
                                                    class="filter-checkbox industry-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                    <?php checked($search_params['industry'], $industry->slug); ?>
                                                >
                                                <span class="filter-item-content flex justify-between items-center flex-1 ml-3">
                                                    <span class="filter-item-label text-sm text-gray-700">
                                                        <?php echo esc_html($industry->name); ?>
                                                    </span>
                                                    <span class="filter-item-count text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                                                        <?php echo $industry->count; ?>
                                                    </span>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- 金額フィルター -->
                        <div class="filter-section mb-6">
                            <div class="filter-section-header flex justify-between items-center cursor-pointer p-2 rounded-lg hover:bg-gray-50" data-toggle="amount">
                                <h4 class="filter-section-title text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-yen-sign text-xs"></i>
                                    助成金額
                                </h4>
                                <i class="fas fa-chevron-down toggle-icon text-xs text-gray-400 transition-transform"></i>
                            </div>
                            <div id="amount-content" class="filter-section-content mt-3">
                                <div class="filter-items space-y-2">
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="amount" value="" class="filter-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" <?php checked($search_params['amount'], ''); ?>>
                                        <span class="filter-item-label ml-3 text-sm text-gray-700">すべて</span>
                                    </label>
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="amount" value="0-100" class="filter-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" <?php checked($search_params['amount'], '0-100'); ?>>
                                        <span class="filter-item-label ml-3 text-sm text-gray-700">〜100万円</span>
                                    </label>
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="amount" value="100-500" class="filter-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" <?php checked($search_params['amount'], '100-500'); ?>>
                                        <span class="filter-item-label ml-3 text-sm text-gray-700">100〜500万円</span>
                                    </label>
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="amount" value="500-1000" class="filter-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" <?php checked($search_params['amount'], '500-1000'); ?>>
                                        <span class="filter-item-label ml-3 text-sm text-gray-700">500〜1000万円</span>
                                    </label>
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="amount" value="1000+" class="filter-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" <?php checked($search_params['amount'], '1000+'); ?>>
                                        <span class="filter-item-label ml-3 text-sm text-gray-700">1000万円〜</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- ステータスフィルター -->
                        <div class="filter-section mb-6">
                            <div class="filter-section-header flex justify-between items-center cursor-pointer p-2 rounded-lg hover:bg-gray-50" data-toggle="status">
                                <h4 class="filter-section-title text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-xs"></i>
                                    募集状況
                                </h4>
                                <i class="fas fa-chevron-down toggle-icon text-xs text-gray-400 transition-transform"></i>
                            </div>
                            <div id="status-content" class="filter-section-content mt-3">
                                <div class="filter-items space-y-2">
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="status[]" 
                                            value="active" 
                                            class="filter-checkbox status-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            <?php checked($search_params['status'], 'active'); ?>
                                        >
                                        <span class="filter-item-content flex items-center gap-3 ml-3">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            <span class="filter-item-label text-sm text-gray-700">募集中</span>
                                        </span>
                                    </label>
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="status[]" 
                                            value="upcoming" 
                                            class="filter-checkbox status-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            <?php checked($search_params['status'], 'upcoming'); ?>
                                        >
                                        <span class="filter-item-content flex items-center gap-3 ml-3">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                            <span class="filter-item-label text-sm text-gray-700">募集予定</span>
                                        </span>
                                    </label>
                                    <label class="filter-item flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="status[]" 
                                            value="closed" 
                                            class="filter-checkbox status-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            <?php checked($search_params['status'], 'closed'); ?>
                                        >
                                        <span class="filter-item-content flex items-center gap-3 ml-3">
                                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                            <span class="filter-item-label text-sm text-gray-700">募集終了</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- フィルターアクション -->
                        <div class="filter-actions flex gap-3">
                            <button 
                                id="apply-filters" 
                                class="apply-filters-btn flex-1 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all transform hover:scale-105 shadow-lg"
                            >
                                <i class="fas fa-check mr-2"></i>
                                フィルター適用
                            </button>
                            <button 
                                id="save-filter" 
                                class="save-filter-btn px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-all"
                                title="検索条件を保存"
                            >
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- メインコンテンツ -->
                <main class="flex-1">
                    <!-- 結果ヘッダー -->
                    <div class="results-header bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h2 id="results-count" class="results-title text-2xl font-bold text-gray-900 dark:text-white">
                                    <span class="count-number text-indigo-600">0</span>件の助成金
                                </h2>
                                <p id="results-description" class="results-description text-gray-600 dark:text-gray-300 mt-1">
                                    <?php if (!empty($search_params['search'])): ?>
                                        「<?php echo esc_html($search_params['search']); ?>」の検索結果
                                    <?php else: ?>
                                        すべての助成金を表示中
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <!-- ローディングインジケーター -->
                            <div id="loading-indicator" class="loading-indicator flex items-center gap-3 text-indigo-600 hidden">
                                <div class="loading-spinner w-6 h-6 border-2 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                                <span class="text-sm font-medium">データを読み込み中...</span>
                            </div>

                            <!-- 結果アクション -->
                            <div class="result-actions flex gap-2">
                                <button class="action-btn px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm hidden" id="select-all">
                                    <i class="fas fa-check-square mr-1"></i>
                                    すべて選択
                                </button>
                                <button class="action-btn px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm" id="compare-selected">
                                    <i class="fas fa-columns mr-1"></i>
                                    比較モード
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- アクティブフィルター表示 -->
                    <div id="active-filters" class="active-filters bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6 hidden">
                        <div class="active-filters-header flex justify-between items-center mb-3">
                            <h3 class="active-filters-title text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-filter text-indigo-600"></i>
                                適用中のフィルター
                            </h3>
                            <button id="clear-all-filters" class="clear-all-btn text-sm text-red-600 hover:text-red-700 font-medium">
                                すべてクリア
                            </button>
                        </div>
                        <div id="active-filter-tags" class="active-filter-tags flex flex-wrap gap-2">
                            <!-- フィルタータグが動的に生成される -->
                        </div>
                    </div>

                    <!-- 助成金リスト -->
                    <div id="grants-container" class="grants-container">
                        <div id="gi-results-unified" data-legacy-id="grants-display" class="grants-grid grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <!-- 初期ローディング表示 -->
                            <div class="initial-loading col-span-full">
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    <?php for($i = 0; $i < 6; $i++): ?>
                                    <div class="loading-card bg-white rounded-xl shadow-lg p-6 animate-pulse">
                                        <div class="skeleton bg-gray-200 h-40 rounded-lg mb-4"></div>
                                        <div class="skeleton bg-gray-200 h-6 rounded mb-3"></div>
                                        <div class="skeleton bg-gray-200 h-4 rounded mb-2"></div>
                                        <div class="skeleton bg-gray-200 h-4 rounded w-3/4"></div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ページネーション -->
                    <div id="pagination-container" class="pagination-container mt-8">
                        <!-- ページネーションが動的に生成される -->
                    </div>

                    <!-- 結果なし表示 -->
                    <div id="no-results" class="no-results bg-white rounded-xl shadow-lg p-12 text-center hidden">
                        <div class="no-results-content max-w-md mx-auto">
                            <div class="no-results-icon mb-6">
                                <i class="fas fa-search text-6xl text-gray-300"></i>
                            </div>
                            <h3 class="no-results-title text-xl font-bold text-gray-900 mb-4">
                                該当する助成金が見つかりませんでした
                            </h3>
                            <p class="no-results-description text-gray-600 mb-6">
                                検索条件を変更して再度お試しください
                            </p>
                            <div class="no-results-suggestions mb-6">
                                <h4 class="suggestions-title text-sm font-semibold text-gray-700 mb-3">検索のヒント:</h4>
                                <ul class="suggestions-list text-sm text-gray-600 text-left space-y-1">
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-500 text-xs"></i>
                                        キーワードを変更してみる
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-500 text-xs"></i>
                                        フィルターを減らしてみる
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-500 text-xs"></i>
                                        地域を「全国」に変更してみる
                                    </li>
                                </ul>
                            </div>
                            <button 
                                id="reset-search" 
                                class="reset-search-btn px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all transform hover:scale-105"
                            >
                                <i class="fas fa-undo mr-2"></i>
                                検索条件をリセット
                            </button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- 比較モーダル -->
    <div id="compare-modal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="modal-content relative bg-white rounded-xl max-w-6xl mx-auto mt-20 p-6 max-h-[80vh] overflow-y-auto">
            <div class="modal-header flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">助成金比較</h2>
                <button id="close-compare" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="compare-table" class="overflow-x-auto">
                <!-- 比較テーブルが動的に生成される -->
            </div>
        </div>
    </div>

    <!-- トースト通知 -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 space-y-2">
        <!-- トースト通知が動的に生成される -->
    </div>
</div>

<!-- JavaScript -->
<script>
// グローバル設定
window.giSearchConfig = {
    ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('gi_ajax_nonce'); ?>',
    isUserLoggedIn: <?php echo is_user_logged_in() ? 'true' : 'false'; ?>,
    userId: <?php echo get_current_user_id(); ?>,
    currentUrl: '<?php echo esc_url(get_permalink()); ?>',
    homeUrl: '<?php echo esc_url(home_url('/')); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    console.log('📄 Grant Archive - 初期化開始');

    // 初期パラメータを取得
    const archiveElement = document.getElementById('grant-archive-page');
    const initialParams = archiveElement ? JSON.parse(archiveElement.dataset.searchParams) : {};

    // アーカイブページ管理システム
    const ArchiveManager = {
        // 状態管理
        state: {
            isLoading: false,
            currentView: initialParams.view || 'grid',
            currentPage: initialParams.page || 1,
            totalPages: 1,
            totalResults: 0,
            selectedGrants: [],
            compareMode: false,
            filters: {
                search: initialParams.search || '',
                categories: initialParams.category ? [initialParams.category] : [],
                prefectures: initialParams.prefecture ? [initialParams.prefecture] : [],
                industries: initialParams.industry ? [initialParams.industry] : [],
                amount: initialParams.amount || '',
                status: initialParams.status ? [initialParams.status] : [],
                difficulty: initialParams.difficulty ? [initialParams.difficulty] : [],
                success_rate: initialParams.success_rate ? [initialParams.success_rate] : [],
                sort: initialParams.orderby || 'date_desc'
            },
            savedSearches: [],
            perPage: 12
        },

        // 初期化
        init() {
            console.log('🔄 アーカイブマネージャー初期化');
            
            this.bindEvents();
            this.loadSavedSearches();
            this.updateUI();
            this.initializeStatistics();
            
            // 初期検索実行
            setTimeout(() => {
                this.executeSearch();
            }, 300);
        },

        // 統計情報の初期化
        initializeStatistics() {
            // 統計情報の取得と表示
            const totalGrants = document.getElementById('total-grants-count');
            const activeGrants = document.getElementById('active-grants-count');
            
            if (totalGrants) {
                totalGrants.textContent = '0';
            }
            if (activeGrants) {
                activeGrants.textContent = '0';
            }
        },

        // イベントバインディング
        bindEvents() {
            const self = this;

            // 検索ボタン
            const searchBtn = document.getElementById('search-btn');
            if (searchBtn) {
                searchBtn.addEventListener('click', () => self.handleSearch());
            }

            // 統合検索システムが処理するため、このコードは不要
            // 検索入力イベントは unified-search-manager.js で処理される

            // 検索クリア
            const searchClear = document.getElementById('search-clear');
            if (searchClear) {
                searchClear.addEventListener('click', () => self.clearSearch());
            }

            // クイックフィルター
            document.querySelectorAll('.quick-filter-pill').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.applyQuickFilter(e.currentTarget.dataset.filter);
                });
            });

            // ソート変更
            const sortOrder = document.getElementById('sort-order');
            if (sortOrder) {
                sortOrder.addEventListener('change', (e) => {
                    self.state.filters.sort = e.target.value;
                    self.state.currentPage = 1;
                    self.executeSearch();
                });
            }

            // 表示件数変更
            const perPage = document.getElementById('per-page');
            if (perPage) {
                perPage.addEventListener('change', (e) => {
                    self.state.perPage = parseInt(e.target.value);
                    self.state.currentPage = 1;
                    self.executeSearch();
                });
            }

            // ビュー切り替え
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.switchView(e.currentTarget.dataset.view);
                });
            });

            // フィルター適用
            const applyFilters = document.getElementById('apply-filters');
            if (applyFilters) {
                applyFilters.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.applyFilters();
                });
            }

            // フィルターリセット
            const resetAllFilters = document.getElementById('reset-all-filters');
            if (resetAllFilters) {
                resetAllFilters.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.resetAllFilters();
                });
            }

            // フィルターセクショントグル
            document.querySelectorAll('.filter-section-header').forEach(header => {
                header.addEventListener('click', (e) => {
                    self.toggleFilterSection(e.currentTarget.dataset.toggle);
                });
            });

            // 比較モード
            const compareSelected = document.getElementById('compare-selected');
            if (compareSelected) {
                compareSelected.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.toggleCompareMode();
                });
            }

            // 全選択
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.toggleSelectAll();
                });
            }

            // リセット検索
            const resetSearch = document.getElementById('reset-search');
            if (resetSearch) {
                resetSearch.addEventListener('click', () => self.resetAllFilters());
            }

            // 全フィルタークリア
            const clearAllFilters = document.getElementById('clear-all-filters');
            if (clearAllFilters) {
                clearAllFilters.addEventListener('click', () => self.resetAllFilters());
            }
        },

        // 検索入力処理
        handleSearchInput(e) {
            const value = e.target.value.trim();
            const clearBtn = document.getElementById('search-clear');
            
            if (clearBtn) {
                clearBtn.classList.toggle('hidden', !value);
            }
        },

        // 検索処理
        handleSearch() {
            const searchInput = document.getElementById('gi-search-input-unified') || document.getElementById('grant-search');
            if (searchInput) {
                this.state.filters.search = searchInput.value.trim();
                this.state.currentPage = 1;
                this.executeSearch();
            }
        },

        // 検索クリア（統合システム対応）
        clearSearch() {
            // 統合検索システムのリセット機能を使用
            if (window.unifiedSearch && typeof window.unifiedSearch.resetSearch === 'function') {
                window.unifiedSearch.resetSearch();
            } else {
                // フォールバック
                const searchInput = document.getElementById('gi-search-input-unified') || document.getElementById('grant-search');
                if (searchInput) {
                    searchInput.value = '';
                    this.state.filters.search = '';
                    document.getElementById('gi-clear-btn')?.classList.add('hidden');
                    this.state.currentPage = 1;
                    this.executeSearch();
            }
        },

        // フィルター適用
        applyFilters() {
            console.log('フィルター適用開始');
            
            // カテゴリー
            this.state.filters.categories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
                .map(cb => cb.value);

            // 都道府県
            this.state.filters.prefectures = Array.from(document.querySelectorAll('.prefecture-checkbox:checked'))
                .map(cb => cb.value);

            // 業種
            this.state.filters.industries = Array.from(document.querySelectorAll('.industry-checkbox:checked'))
                .map(cb => cb.value);

            // 金額
            const amountRadio = document.querySelector('input[name="amount"]:checked');
            this.state.filters.amount = amountRadio ? amountRadio.value : '';

            // ステータス
            this.state.filters.status = Array.from(document.querySelectorAll('.status-checkbox:checked'))
                .map(cb => cb.value);

            console.log('適用されるフィルター:', this.state.filters);
            
            this.state.currentPage = 1;
            this.executeSearch();
        },

        // 検索実行
        async executeSearch() {
            if (this.state.isLoading) return;

            console.log('🔍 検索実行開始', this.state.filters);

            this.state.isLoading = true;
            this.showLoading(true);

            try {
                const formData = new FormData();
                formData.append('action', 'gi_load_grants');
                formData.append('nonce', window.giSearchConfig.nonce);
                formData.append('search', this.state.filters.search || '');
                formData.append('categories', JSON.stringify(this.state.filters.categories || []));
                formData.append('prefectures', JSON.stringify(this.state.filters.prefectures || []));
                formData.append('industries', JSON.stringify(this.state.filters.industries || []));
                formData.append('amount', this.state.filters.amount || '');
                formData.append('status', JSON.stringify(this.state.filters.status || []));
                formData.append('difficulty', JSON.stringify(this.state.filters.difficulty || []));
                formData.append('success_rate', JSON.stringify(this.state.filters.success_rate || []));
                formData.append('sort', this.state.filters.sort || 'date_desc');
                formData.append('view', this.state.currentView || 'grid');
                formData.append('page', this.state.currentPage || 1);
                formData.append('posts_per_page', this.state.perPage || 12);

                const response = await fetch(window.giSearchConfig.ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('📥 検索結果:', data);

                if (data.success) {
                    this.displayResults(data.data);
                    this.updateURL();
                    this.updateActiveFilters();
                    this.updateStatistics(data.data);
                } else {
                    this.showError(data.data?.message || '検索に失敗しました');
                }
            } catch (error) {
                console.error('❌ 検索エラー:', error);
                this.showError('検索中にエラーが発生しました');
            } finally {
                this.state.isLoading = false;
                this.showLoading(false);
            }
        },

        // 結果表示
        displayResults(data) {
            const container = document.getElementById('grants-display');
            if (!container) return;

            this.state.totalResults = data.found_posts || 0;
            this.state.totalPages = data.pagination?.total_pages || 1;

            const countElement = document.querySelector('#results-count .count-number');
            if (countElement) {
                countElement.textContent = this.state.totalResults.toLocaleString();
            }

            if (data.grants && data.grants.length > 0) {
                if (this.state.currentView === 'grid') {
                    container.className = 'grants-grid grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6';
                } else if (this.state.currentView === 'list') {
                    container.className = 'grants-list space-y-4';
                }

                let html = '';
                data.grants.forEach(grant => {
                    html += grant.html || '';
                });

                container.innerHTML = html;
                this.setupCardEvents();

                if (this.compareMode) {
                    this.addCheckboxesToCards();
                }

                const noResults = document.getElementById('no-results');
                if (noResults) {
                    noResults.classList.add('hidden');
                }
            } else {
                container.innerHTML = '';
                const noResults = document.getElementById('no-results');
                if (noResults) {
                    noResults.classList.remove('hidden');
                }
            }

            this.updatePagination(data.pagination);
        },

        // カードイベント設定
        setupCardEvents() {
            // お気に入りボタン
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleFavorite(btn.dataset.postId);
                });
            });

            // 共有ボタン
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.shareGrant(btn.dataset.url, btn.dataset.title);
                });
            });
        },

        // ページネーション更新
        updatePagination(paginationData) {
            const container = document.getElementById('pagination-container');
            if (!container) return;

            if (paginationData && paginationData.html) {
                container.innerHTML = paginationData.html;
                
                container.querySelectorAll('.pagination-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const page = parseInt(btn.dataset.page);
                        if (page && page !== this.state.currentPage) {
                            this.state.currentPage = page;
                            this.executeSearch();
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    });
                });
            }
        },

        // クイックフィルター適用
        applyQuickFilter(filter) {
            document.querySelectorAll('.quick-filter-pill').forEach(btn => {
                btn.classList.remove('active', 'bg-indigo-100', 'border-indigo-300', 'bg-green-100', 'border-green-300', 'bg-yellow-100', 'border-yellow-300');
            });

            const activeBtn = document.querySelector(`.quick-filter-pill[data-filter="${filter}"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
                if (filter === 'all') {
                    activeBtn.classList.add('bg-indigo-100', 'border-indigo-300');
                } else if (filter === 'active') {
                    activeBtn.classList.add('bg-green-100', 'border-green-300');
                } else if (filter === 'upcoming') {
                    activeBtn.classList.add('bg-yellow-100', 'border-yellow-300');
                }
            }

            // フィルターリセット
            this.state.filters = {
                search: this.state.filters.search,
                categories: [],
                prefectures: [],
                industries: [],
                amount: '',
                status: [],
                difficulty: [],
                success_rate: [],
                sort: this.state.filters.sort
            };

            // 特定のフィルター適用
            switch(filter) {
                case 'active':
                    this.state.filters.status = ['active'];
                    break;
                case 'upcoming':
                    this.state.filters.status = ['upcoming'];
                    break;
                case 'high-amount':
                    this.state.filters.amount = '1000+';
                    break;
                case 'easy':
                    this.state.filters.difficulty = ['easy'];
                    break;
                case 'high-success':
                    this.state.filters.success_rate = ['high'];
                    break;
            }

            this.state.currentPage = 1;
            this.updateUI();
            this.executeSearch();
        },

        // 全フィルターリセット
        resetAllFilters() {
            this.state.filters = {
                search: '',
                categories: [],
                prefectures: [],
                industries: [],
                amount: '',
                status: [],
                difficulty: [],
                success_rate: [],
                sort: 'date_desc'
            };
            
            const searchInput = document.getElementById('gi-search-input-unified') || document.getElementById('grant-search');
            if (searchInput) searchInput.value = '';
            document.getElementById('search-clear')?.classList.add('hidden');
            document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.filter-radio').forEach(rb => rb.checked = rb.value === '');
            
            this.state.currentPage = 1;
            this.executeSearch();
        },

        // ビュー切り替え
        switchView(view) {
            this.state.currentView = view;
            document.querySelectorAll('.view-btn').forEach(btn => {
                const isActive = btn.dataset.view === view;
                btn.classList.toggle('active', isActive);
                btn.classList.toggle('bg-white', isActive);
                btn.classList.toggle('shadow', isActive);
            });
            this.executeSearch();
        },

        // フィルターセクション切り替え
        toggleFilterSection(section) {
            const content = document.getElementById(section + '-content');
            const header = document.querySelector(`[data-toggle="${section}"]`);
            const icon = header?.querySelector('.toggle-icon');
            
            if (content) {
                content.classList.toggle('hidden');
                if (icon) {
                    icon.style.transform = content.classList.contains('hidden') ? 'rotate(-90deg)' : 'rotate(0deg)';
                }
            }
        },

        // 比較モード切り替え
        toggleCompareMode() {
            this.compareMode = !this.compareMode;
            const compareBtn = document.getElementById('compare-selected');
            const selectAllBtn = document.getElementById('select-all');
            
            if (this.compareMode) {
                compareBtn.innerHTML = '<i class="fas fa-columns mr-1"></i>比較実行';
                compareBtn.classList.add('bg-indigo-600', 'text-white');
                selectAllBtn?.classList.remove('hidden');
                this.addCheckboxesToCards();
                this.showToast('比較モードを有効にしました', 'info');
            } else {
                compareBtn.innerHTML = '<i class="fas fa-columns mr-1"></i>比較モード';
                compareBtn.classList.remove('bg-indigo-600', 'text-white');
                selectAllBtn?.classList.add('hidden');
                this.removeCheckboxesFromCards();
                this.state.selectedGrants = [];
                this.showToast('比較モードを無効にしました', 'info');
            }
        },

        // カードにチェックボックス追加
        addCheckboxesToCards() {
            document.querySelectorAll('.grant-card-modern').forEach((card, index) => {
                if (!card.querySelector('.compare-checkbox')) {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.className = 'compare-checkbox absolute top-2 left-2 w-5 h-5 z-10';
                    checkbox.dataset.grantId = card.dataset.grantId || index;
                    checkbox.addEventListener('change', (e) => {
                        this.handleCompareSelection(e.target);
                    });
                    card.style.position = 'relative';
                    card.appendChild(checkbox);
                }
            });
        },

        // カードからチェックボックス削除
        removeCheckboxesFromCards() {
            document.querySelectorAll('.compare-checkbox').forEach(checkbox => {
                checkbox.remove();
            });
        },

        // 比較選択処理
        handleCompareSelection(checkbox) {
            const grantId = checkbox.dataset.grantId;
            
            if (checkbox.checked) {
                if (!this.state.selectedGrants.includes(grantId)) {
                    this.state.selectedGrants.push(grantId);
                }
            } else {
                this.state.selectedGrants = this.state.selectedGrants.filter(id => id !== grantId);
            }
            
            const compareBtn = document.getElementById('compare-selected');
            if (compareBtn && this.compareMode) {
                compareBtn.innerHTML = `<i class="fas fa-columns mr-1"></i>比較実行 (${this.state.selectedGrants.length})`;
                
                if (this.state.selectedGrants.length >= 2) {
                    compareBtn.onclick = () => this.showCompareModal();
                }
            }
        },

        // 全選択切り替え
        toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.compare-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                this.handleCompareSelection(checkbox);
            });
        },

        // 比較モーダル表示
        showCompareModal() {
            if (this.state.selectedGrants.length < 2) {
                this.showToast('2つ以上の助成金を選択してください', 'warning');
                return;
            }
            
            this.showToast(`${this.state.selectedGrants.length}件の助成金を比較します`, 'success');
        },

        // お気に入り切り替え
        async toggleFavorite(postId) {
            try {
                const formData = new FormData();
                formData.append('action', 'gi_toggle_favorite');
                formData.append('nonce', window.giSearchConfig.nonce);
                formData.append('post_id', postId);

                const response = await fetch(window.giSearchConfig.ajaxUrl, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    const btn = document.querySelector(`.favorite-btn[data-post-id="${postId}"]`);
                    if (btn) {
                        const svg = btn.querySelector('svg');
                        if (svg) {
                            if (data.data.is_favorite) {
                                svg.setAttribute('fill', 'currentColor');
                                btn.classList.add('text-red-500');
                            } else {
                                svg.setAttribute('fill', 'none');
                                btn.classList.remove('text-red-500');
                            }
                        }
                    }
                    
                    this.showToast(data.data.message, 'success');
                }
            } catch (error) {
                console.error('お気に入りエラー:', error);
                this.showToast('お気に入りの更新に失敗しました', 'error');
            }
        },

        // 助成金共有
        shareGrant(url, title) {
            if (navigator.share) {
                navigator.share({
                    title: title,
                    url: url
                }).then(() => {
                    this.showToast('共有しました', 'success');
                }).catch(() => {
                    console.log('共有がキャンセルされました');
                });
            } else {
                navigator.clipboard.writeText(url);
                this.showToast('URLをコピーしました', 'success');
            }
        },

        // UI更新
        updateUI() {
            document.querySelectorAll('.category-checkbox').forEach(cb => {
                cb.checked = this.state.filters.categories.includes(cb.value);
            });
            
            document.querySelectorAll('.prefecture-checkbox').forEach(cb => {
                cb.checked = this.state.filters.prefectures.includes(cb.value);
            });
            
            document.querySelectorAll('.industry-checkbox').forEach(cb => {
                cb.checked = this.state.filters.industries.includes(cb.value);
            });
            
            document.querySelectorAll('.status-checkbox').forEach(cb => {
                cb.checked = this.state.filters.status.includes(cb.value);
            });

            const sortSelect = document.getElementById('sort-order');
            if (sortSelect) {
                sortSelect.value = this.state.filters.sort;
            }
        },

        // アクティブフィルター更新
        updateActiveFilters() {
            const activeFiltersEl = document.getElementById('active-filters');
            const tagsContainer = document.getElementById('active-filter-tags');
            
            if (!activeFiltersEl || !tagsContainer) return;
            
            const hasFilters = 
                this.state.filters.search ||
                this.state.filters.categories.length > 0 ||
                this.state.filters.prefectures.length > 0 ||
                this.state.filters.industries.length > 0 ||
                this.state.filters.amount ||
                this.state.filters.status.length > 0;
            
            if (hasFilters) {
                activeFiltersEl.classList.remove('hidden');
                
                let tagsHTML = '';
                
                if (this.state.filters.search) {
                    tagsHTML += this.createFilterTag('search', `検索: ${this.state.filters.search}`, 'fas fa-search');
                }
                
                this.state.filters.categories.forEach(cat => {
                    const label = document.querySelector(`.category-checkbox[value="${cat}"]`)?.dataset.label || cat;
                    tagsHTML += this.createFilterTag('category', `カテゴリ: ${label}`, 'fas fa-folder', cat);
                });
                
                this.state.filters.prefectures.forEach(pref => {
                    const label = document.querySelector(`.prefecture-checkbox[value="${pref}"]`)?.dataset.label || pref;
                    tagsHTML += this.createFilterTag('prefecture', `地域: ${label}`, 'fas fa-map-marker-alt', pref);
                });

                if (this.state.filters.amount) {
                    const amountLabels = {
                        '0-100': '〜100万円',
                        '100-500': '100〜500万円',
                        '500-1000': '500〜1000万円',
                        '1000+': '1000万円〜'
                    };
                    tagsHTML += this.createFilterTag('amount', `金額: ${amountLabels[this.state.filters.amount] || this.state.filters.amount}`, 'fas fa-yen-sign');
                }
                
                tagsContainer.innerHTML = tagsHTML;
                
                tagsContainer.querySelectorAll('.filter-tag-remove').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.removeFilter(btn.dataset.filter, btn.dataset.value);
                    });
                });
            } else {
                activeFiltersEl.classList.add('hidden');
            }
        },

        // フィルタータグ作成
        createFilterTag(type, label, icon, value = '') {
            return `
                <span class="filter-tag inline-flex items-center gap-2 px-3 py-1 bg-white border border-indigo-200 rounded-full text-sm text-indigo-700">
                    <i class="${icon}"></i>
                    ${label}
                    <button class="filter-tag-remove ml-1 text-indigo-400 hover:text-red-500 transition-colors" data-filter="${type}" data-value="${value}">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </span>
            `;
        },

        // フィルター削除
        removeFilter(type, value) {
            switch(type) {
                case 'search':
                    this.state.filters.search = '';
                    const searchInput = document.getElementById('gi-search-input-unified') || document.getElementById('grant-search');
            if (searchInput) searchInput.value = '';
                    document.getElementById('search-clear')?.classList.add('hidden');
                    break;
                case 'category':
                    this.state.filters.categories = this.state.filters.categories.filter(c => c !== value);
                    break;
                case 'prefecture':
                    this.state.filters.prefectures = this.state.filters.prefectures.filter(p => p !== value);
                    break;
                case 'amount':
                    this.state.filters.amount = '';
                    break;
            }
            
            this.state.currentPage = 1;
            this.updateUI();
            this.executeSearch();
        },

        // 統計情報更新
        updateStatistics(data) {
            const totalCount = document.getElementById('total-grants-count');
            const activeCount = document.getElementById('active-grants-count');
            
            if (totalCount) {
                totalCount.textContent = (data.found_posts || 0).toLocaleString();
            }
            
            if (activeCount && data.grants) {
                const active = data.grants.filter(g => g.data && g.data.status === '募集中').length;
                activeCount.textContent = active.toLocaleString();
            }
        },

        // URL更新
        updateURL() {
            const params = new URLSearchParams();
            
            if (this.state.filters.search) params.set('search', this.state.filters.search);
            if (this.state.filters.categories.length > 0) params.set('category', this.state.filters.categories[0]);
            if (this.state.filters.prefectures.length > 0) params.set('prefecture', this.state.filters.prefectures[0]);
            if (this.state.currentPage > 1) params.set('page', this.state.currentPage);
            
            const queryString = params.toString();
            const newURL = window.location.pathname + (queryString ? '?' + queryString : '');
            
            window.history.replaceState({}, '', newURL);
        },

        // ローディング表示
        showLoading(show) {
            const loadingEl = document.getElementById('loading-indicator');
            const grantsDisplay = document.getElementById('grants-display');
            
            if (loadingEl) {
                loadingEl.classList.toggle('hidden', !show);
            }

            if (!show && grantsDisplay) {
                const initialLoading = grantsDisplay.querySelector('.initial-loading');
                if (initialLoading) {
                    initialLoading.remove();
                }
            }

            if (grantsDisplay) {
                grantsDisplay.style.opacity = show ? '0.6' : '1';
            }
        },

        // エラー表示
        showError(message) {
            const container = document.getElementById('grants-display');
            if (container) {
                container.innerHTML = `
                    <div class="col-span-full bg-red-50 border border-red-200 rounded-xl p-8 text-center">
                        <div class="text-red-500 mb-4">
                            <i class="fas fa-exclamation-triangle text-4xl"></i>
                        </div>
                        <div class="text-gray-700 text-lg font-semibold mb-2">
                            エラーが発生しました
                        </div>
                        <div class="text-gray-600 mb-4">
                            ${message}
                        </div>
                        <button onclick="window.ArchiveManager.executeSearch()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            再試行
                        </button>
                    </div>
                `;
            }
        },

        // トースト通知
        showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = `toast flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white transform translate-x-full transition-transform duration-300`;
            
            const styles = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
            };
            
            toast.classList.add(styles[type] || styles['info']);
            
            toast.innerHTML = `
                <span>${message}</span>
                <button class="ml-4 text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            toast.querySelector('button').addEventListener('click', () => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            });
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        },

        // 保存した検索読み込み
        loadSavedSearches() {
            const savedSearches = JSON.parse(localStorage.getItem('gi_saved_searches') || '[]');
            this.state.savedSearches = savedSearches;
        }
    };

    // グローバルに公開
    window.ArchiveManager = ArchiveManager;

    // 初期化実行
    ArchiveManager.init();

    console.log('✅ Grant Archive 初期化完了');
});
</script>

<style>
/* カスタムスタイル */
.grant-archive-complete {
    min-height: 100vh;
}

/* スケルトンローディング */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* フィルターセクション */
.filter-section-header {
    user-select: none;
}

.toggle-icon {
    transition: transform 0.3s ease;
}

/* トースト通知 */
.toast {
    min-width: 300px;
    max-width: 500px;
}

/* レスポンシブ対応 */
@media (max-width: 1024px) {
    #filter-sidebar {
        display: none !important;
    }
}

@media (max-width: 640px) {
    .grants-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php get_footer(); ?>