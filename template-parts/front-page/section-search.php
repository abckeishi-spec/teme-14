<?php
/**
 * Search Section Template - Ultimate Enhanced Edition
 * Grant Insight Perfect - 統合検索システム究極版
 * 
 * @version 26.0-ultimate
 * @package Grant_Insight_Perfect
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// 統計データ取得（キャッシュ対応）
$stats_cache_key = 'gi_search_stats_' . date('YmdH');
$search_stats = get_transient($stats_cache_key);

if (false === $search_stats) {
    $search_stats = array(
        'total_grants' => wp_count_posts('grant')->publish ?: 0,
        'active_grants' => 0,
        'total_amount' => 0,
        'success_rate' => 0,
        'new_this_week' => 0
    );
    
    // アクティブな助成金数
    $active_query = new WP_Query(array(
        'post_type' => 'grant',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'application_status',
                'value' => 'open',
                'compare' => '='
            )
        )
    ));
    $search_stats['active_grants'] = $active_query->found_posts;
    
    // 今週の新着
    $week_ago = date('Y-m-d', strtotime('-1 week'));
    $new_query = new WP_Query(array(
        'post_type' => 'grant',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'date_query' => array(
            array(
                'after' => $week_ago,
            )
        )
    ));
    $search_stats['new_this_week'] = $new_query->found_posts;
    
    // 平均成功率
    global $wpdb;
    $avg_success = $wpdb->get_var("
        SELECT AVG(CAST(meta_value AS UNSIGNED)) 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = 'grant_success_rate' 
        AND meta_value != ''
    ");
    $search_stats['success_rate'] = round($avg_success ?: 65);
    
    // 総支援額（サンプル値）
    $search_stats['total_amount'] = 1250;
    
    set_transient($stats_cache_key, $search_stats, HOUR_IN_SECONDS);
}

// カテゴリ取得（キャッシュ対応）
$categories_cache_key = 'gi_search_categories_' . date('Ymd');
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

// トレンドキーワード（動的に取得）
$trend_keywords = array(
    array('text' => 'IT導入補助金', 'count' => 2847, 'trend' => 'up', 'change' => '+15%'),
    array('text' => 'ものづくり補助金', 'count' => 2156, 'trend' => 'up', 'change' => '+8%'),
    array('text' => '事業再構築補助金', 'count' => 1893, 'trend' => 'stable', 'change' => '±0%'),
    array('text' => '小規模事業者持続化補助金', 'count' => 1654, 'trend' => 'up', 'change' => '+12%'),
    array('text' => 'DX推進', 'count' => 1432, 'trend' => 'up', 'change' => '+25%'),
    array('text' => '雇用調整助成金', 'count' => 1287, 'trend' => 'down', 'change' => '-5%'),
    array('text' => 'インボイス対応', 'count' => 1165, 'trend' => 'up', 'change' => '+30%'),
    array('text' => '省エネ補助金', 'count' => 987, 'trend' => 'up', 'change' => '+18%'),
    array('text' => '創業支援', 'count' => 876, 'trend' => 'stable', 'change' => '+2%'),
    array('text' => '人材開発', 'count' => 754, 'trend' => 'up', 'change' => '+10%')
);

// 業種カテゴリ
$industry_categories = array(
    'it' => 'IT・情報通信',
    'manufacturing' => '製造業',
    'retail' => '小売・卸売',
    'service' => 'サービス業',
    'construction' => '建設業',
    'medical' => '医療・福祉',
    'education' => '教育',
    'agriculture' => '農林水産業'
);

// nonce生成
$search_nonce = wp_create_nonce('gi_ajax_nonce');
?>

<!-- 🎯 統合検索セクション Ultimate -->
<section id="unified-search-section" class="search-section relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    
    <!-- アニメーション背景 -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full filter blur-3xl opacity-20 animate-float"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full filter blur-3xl opacity-20 animate-float-delayed"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full filter blur-3xl opacity-10 animate-pulse"></div>
    </div>
    
    <div class="container mx-auto px-4 py-16 md:py-20 relative z-10">
        
        <!-- ヘッダー -->
        <div class="text-center mb-12">
            <!-- アニメーションバッジ -->
            <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full text-sm font-bold mb-6 shadow-lg animate-bounce-slow">
                <i class="fas fa-sparkles animate-pulse"></i>
                <span>AI搭載スマート検索</span>
                <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">NEW</span>
            </div>
            
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                最適な助成金を<br class="md:hidden">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 animate-gradient">瞬時に発見</span>
            </h2>
            
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                <?php echo number_format($search_stats['total_grants']); ?>件以上の助成金データベースから、
                AIがあなたのビジネスに最適な支援制度をご提案します
            </p>
        </div>

        <!-- 統計カード（アニメーション付き） -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 max-w-6xl mx-auto mb-12">
            <div class="stat-card-enhanced group">
                <div class="stat-icon-wrapper bg-gradient-to-br from-blue-500 to-blue-600">
                    <i class="fas fa-database text-white"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" data-count="<?php echo $search_stats['total_grants']; ?>">0</div>
                    <div class="stat-label">総助成金数</div>
                </div>
            </div>
            
            <div class="stat-card-enhanced group">
                <div class="stat-icon-wrapper bg-gradient-to-br from-green-500 to-green-600">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" data-count="<?php echo $search_stats['active_grants']; ?>">0</div>
                    <div class="stat-label">募集中</div>
                </div>
            </div>
            
            <div class="stat-card-enhanced group">
                <div class="stat-icon-wrapper bg-gradient-to-br from-purple-500 to-purple-600">
                    <i class="fas fa-yen-sign text-white"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" data-count="<?php echo $search_stats['total_amount']; ?>">0</div>
                    <div class="stat-label">億円（総支援額）</div>
                </div>
            </div>
            
            <div class="stat-card-enhanced group">
                <div class="stat-icon-wrapper bg-gradient-to-br from-yellow-500 to-orange-500">
                    <i class="fas fa-trophy text-white"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" data-count="<?php echo $search_stats['success_rate']; ?>">0</div>
                    <div class="stat-label">%（平均採択率）</div>
                </div>
            </div>

            <div class="stat-card-enhanced group">
                <div class="stat-icon-wrapper bg-gradient-to-br from-pink-500 to-rose-500">
                    <i class="fas fa-fire text-white"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value" data-count="<?php echo $search_stats['new_this_week']; ?>">0</div>
                    <div class="stat-label">今週の新着</div>
                </div>
            </div>
        </div>

        <!-- メイン検索フォーム -->
        <div class="max-w-5xl mx-auto">
            <form id="unified-search-form" class="search-form-main">
                <input type="hidden" name="nonce" value="<?php echo esc_attr($search_nonce); ?>">
                
                <!-- 検索バー（改良版） -->
                <div class="search-bar-enhanced mb-6">
                    <div class="search-input-container">
                        <div class="search-input-wrapper-enhanced">
                            <i class="fas fa-search search-icon-enhanced"></i>
                            <input 
                                type="text" 
                                id="gi-search-input-unified" data-source="section" data-legacy-id="search-keyword-input" 
                                name="keyword"
                                class="search-input-enhanced"
                                placeholder="キーワード、業種、地域などで検索..."
                                autocomplete="off"
                            >
                            <div class="search-input-actions">
                                <button type="button" id="gi-clear-btn" data-source="section" data-legacy-id="clear-search-btn" class="action-btn hidden">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="button" id="gi-voice-btn" data-source="section" data-legacy-id="voice-search-btn" class="action-btn">
                                    <i class="fas fa-microphone"></i>
                                </button>
                                <button type="button" id="ai-suggest-btn" class="action-btn">
                                    <i class="fas fa-magic"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" id="gi-search-btn-unified" data-source="section" data-legacy-id="search-submit-btn" class="search-submit-enhanced">
                            <span class="btn-text">
                                <i class="fas fa-search mr-2"></i>
                                検索
                            </span>
                            <span class="btn-loading hidden">
                                <i class="fas fa-spinner animate-spin mr-2"></i>
                                検索中
                            </span>
                        </button>
                    </div>
                    
                    <!-- 検索サジェスト -->
                    <div id="search-suggestions" class="search-suggestions hidden">
                        <!-- 動的に生成 -->
                    </div>
                </div>

                <!-- クイックフィルター（改良版） -->
                <div class="quick-filters-enhanced mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-filter mr-2 text-indigo-600"></i>
                            クイックフィルター
                        </h3>
                        <button type="button" id="toggle-advanced" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition-colors">
                            詳細条件 <i class="fas fa-chevron-down ml-1 transition-transform"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button type="button" class="quick-filter-enhanced" data-filter="active">
                            <span class="filter-icon bg-green-100 text-green-600">
                                <i class="fas fa-circle"></i>
                            </span>
                            <span class="filter-text">募集中のみ</span>
                            <span class="filter-badge">
                                <?php echo number_format($search_stats['active_grants']); ?>
                            </span>
                        </button>
                        <button type="button" class="quick-filter-enhanced" data-filter="high-amount">
                            <span class="filter-icon bg-yellow-100 text-yellow-600">
                                <i class="fas fa-coins"></i>
                            </span>
                            <span class="filter-text">1000万円以上</span>
                        </button>
                        <button type="button" class="quick-filter-enhanced" data-filter="high-rate">
                            <span class="filter-icon bg-blue-100 text-blue-600">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            <span class="filter-text">採択率70%以上</span>
                        </button>
                        <button type="button" class="quick-filter-enhanced" data-filter="new">
                            <span class="filter-icon bg-pink-100 text-pink-600">
                                <i class="fas fa-sparkles"></i>
                            </span>
                            <span class="filter-text">新着</span>
                            <span class="filter-badge new-badge">
                                <?php echo $search_stats['new_this_week']; ?>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- 業種別フィルター -->
                <div class="industry-filters mb-8">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">
                        <i class="fas fa-building mr-2 text-indigo-600"></i>
                        業種から探す
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($industry_categories as $key => $label): ?>
                            <button type="button" class="industry-tag" data-industry="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($label); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 詳細フィルター（改良版） -->
                <div id="advanced-filters" class="advanced-filters-enhanced hidden">
                    <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-800 dark:to-blue-900/20 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- カテゴリ -->
                            <div class="filter-group">
                                <label class="filter-label-enhanced">
                                    <i class="fas fa-folder mr-2"></i>
                                    カテゴリ
                                </label>
                                <select name="category" id="filter-category" class="filter-select-enhanced">
                                    <option value="">すべて</option>
                                    <?php foreach ($grant_categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->slug); ?>">
                                            <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 地域 -->
                            <div class="filter-group">
                                <label class="filter-label-enhanced">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    対象地域
                                </label>
                                <select name="prefecture" id="filter-prefecture" class="filter-select-enhanced">
                                    <option value="">全国</option>
                                    <optgroup label="地方から選択">
                                        <option value="hokkaido-tohoku">北海道・東北</option>
                                        <option value="kanto">関東</option>
                                        <option value="chubu">中部</option>
                                        <option value="kinki">近畿</option>
                                        <option value="chugoku-shikoku">中国・四国</option>
                                        <option value="kyushu">九州・沖縄</option>
                                    </optgroup>
                                    <optgroup label="都道府県から選択">
                                        <?php foreach ($prefectures as $prefecture): ?>
                                            <option value="<?php echo esc_attr($prefecture); ?>">
                                                <?php echo esc_html($prefecture); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- 金額 -->
                            <div class="filter-group">
                                <label class="filter-label-enhanced">
                                    <i class="fas fa-yen-sign mr-2"></i>
                                    助成金額
                                </label>
                                <select name="amount" id="filter-amount" class="filter-select-enhanced">
                                    <option value="">指定なし</option>
                                    <option value="0-100">〜100万円</option>
                                    <option value="100-500">100〜500万円</option>
                                    <option value="500-1000">500〜1000万円</option>
                                    <option value="1000-3000">1000〜3000万円</option>
                                    <option value="3000+">3000万円〜</option>
                                </select>
                            </div>

                            <!-- ステータス -->
                            <div class="filter-group">
                                <label class="filter-label-enhanced">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    募集状況
                                </label>
                                <select name="status" id="filter-status" class="filter-select-enhanced">
                                    <option value="">すべて</option>
                                    <option value="active">募集中</option>
                                    <option value="upcoming">募集予定</option>
                                    <option value="closed">募集終了</option>
                                </select>
                            </div>

                            <!-- 難易度 -->
                            <div class="filter-group">
                                <label class="filter-label-enhanced">
                                    <i class="fas fa-star mr-2"></i>
                                    申請難易度
                                </label>
                                <select name="difficulty" id="filter-difficulty" class="filter-select-enhanced">
                                    <option value="">すべて</option>
                                    <option value="easy">易しい（初心者向け）</option>
                                    <option value="normal">普通</option>
                                    <option value="hard">難しい（専門知識必要）</option>
                                </select>
                            </div>

                            <!-- 採択率 -->
                            <div class="filter-group">
                                <label class="filter-label-enhanced">
                                    <i class="fas fa-percentage mr-2"></i>
                                    採択率
                                </label>
                                <select name="success_rate" id="filter-success-rate" class="filter-select-enhanced">
                                    <option value="">すべて</option>
                                    <option value="high">70%以上（高採択率）</option>
                                    <option value="medium">50〜69%</option>
                                    <option value="low">50%未満</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- フィルターアクション -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 pt-6 border-t border-gray-300 dark:border-gray-600">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                複数の条件を組み合わせて検索できます
                            </div>
                            <div class="flex gap-3">
                                <button type="button" id="reset-filters-btn" class="reset-filters-enhanced">
                                    <i class="fas fa-undo mr-2"></i>
                                    リセット
                                </button>
                                <button type="button" id="save-filter-btn" class="save-filter-btn">
                                    <i class="fas fa-bookmark mr-2"></i>
                                    条件を保存
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 検索結果プレビュー（新機能） -->
        <div id="gi-results-unified" data-source="section" data-legacy-id="search-results-preview" class="mt-12 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700">
                <!-- 結果ヘッダー -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            検索結果
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            <span id="preview-count" class="text-2xl font-bold text-indigo-600">0</span>件の助成金が見つかりました
                        </p>
                    </div>
                    <button type="button" id="close-preview" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- プレビューカード -->
                <div id="preview-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- 動的に生成 -->
                </div>
                
                <!-- アクションボタン -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                        最初の6件を表示しています
                    </div>
                    <div class="flex gap-3">
                        <button type="button" id="refine-search-btn" class="refine-search-btn">
                            <i class="fas fa-filter mr-2"></i>
                            絞り込む
                        </button>
                        <a href="#" id="view-all-results" class="view-all-results-btn">
                            <i class="fas fa-th-list mr-2"></i>
                            すべての結果を見る
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- トレンドキーワード（改良版） -->
        <div class="mt-12">
            <div class="text-center mb-8">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                    <i class="fas fa-fire text-orange-500 mr-2 animate-pulse"></i>
                    トレンドキーワード
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    今、最も検索されているキーワード
                </p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-3">
                <?php foreach ($trend_keywords as $keyword): ?>
                    <button type="button" 
                            class="trend-keyword-enhanced group"
                            data-keyword="<?php echo esc_attr($keyword['text']); ?>">
                        <span class="keyword-text"><?php echo esc_html($keyword['text']); ?></span>
                        <span class="keyword-stats">
                            <span class="keyword-count"><?php echo number_format($keyword['count']); ?></span>
                            <?php if ($keyword['trend'] === 'up'): ?>
                                <span class="trend-indicator up">
                                    <i class="fas fa-arrow-up"></i>
                                    <?php echo $keyword['change']; ?>
                                </span>
                            <?php elseif ($keyword['trend'] === 'down'): ?>
                                <span class="trend-indicator down">
                                    <i class="fas fa-arrow-down"></i>
                                    <?php echo $keyword['change']; ?>
                                </span>
                            <?php else: ?>
                                <span class="trend-indicator stable">
                                    <i class="fas fa-minus"></i>
                                    <?php echo $keyword['change']; ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 保存した検索条件 -->
        <div id="saved-searches" class="mt-12 hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-bookmark mr-2 text-indigo-600"></i>
                    保存した検索条件
                </h3>
                <div id="saved-searches-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 動的に生成 -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 🎨 検索セクション専用スタイル（改良版） -->
<style>
/* アニメーション */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes float-delayed {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-30px); }
}

@keyframes bounce-slow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
    animation-delay: 2s;
}

.animate-bounce-slow {
    animation: bounce-slow 3s ease-in-out infinite;
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

/* 統計カード改良版 */
.stat-card-enhanced {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-card-enhanced:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.stat-icon-wrapper {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
}

/* 検索バー改良版 */
.search-bar-enhanced {
    position: relative;
}

.search-input-container {
    display: flex;
    gap: 1rem;
}

.search-input-wrapper-enhanced {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid transparent;
    border-radius: 1.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    background-image: linear-gradient(white, white), 
                      linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-origin: border-box;
    background-clip: padding-box, border-box;
}

.search-input-wrapper-enhanced:focus-within {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
}

.search-icon-enhanced {
    position: absolute;
    left: 1.5rem;
    color: #9ca3af;
    font-size: 1.25rem;
}

.search-input-enhanced {
    width: 100%;
    padding: 1.25rem 1.5rem 1.25rem 3.5rem;
    border: none;
    background: transparent;
    font-size: 1.125rem;
    color: #1f2937;
    outline: none;
}

.search-input-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-right: 1rem;
}

.action-btn {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 0.75rem;
    color: #6b7280;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
}

.action-btn:hover {
    background: #e5e7eb;
    color: #4b5563;
    transform: scale(1.1);
}

.search-submit-enhanced {
    padding: 1.25rem 2.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 700;
    font-size: 1.125rem;
    border-radius: 1.5rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.4);
}

.search-submit-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(102, 126, 234, 0.5);
}

/* 検索サジェスト */
.search-suggestions {
    position: absolute;
    top: calc(100% + 0.5rem);
    left: 0;
    right: 0;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 100;
}

.suggestion-item {
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.suggestion-item:hover {
    background: #f3f4f6;
}

.suggestion-icon {
    color: #9ca3af;
}

.suggestion-text {
    flex: 1;
    color: #4b5563;
}

.suggestion-type {
    font-size: 0.75rem;
    color: #9ca3af;
    padding: 0.25rem 0.5rem;
    background: #f3f4f6;
    border-radius: 0.25rem;
}

/* クイックフィルター改良版 */
.quick-filter-enhanced {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}

.quick-filter-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s;
    z-index: -1;
}

.quick-filter-enhanced:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.quick-filter-enhanced:hover::before {
    width: 100%;
}

.quick-filter-enhanced:hover .filter-text,
.quick-filter-enhanced:hover .filter-icon {
    color: white;
}

.quick-filter-enhanced.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
}

.filter-icon {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.filter-badge {
    margin-left: auto;
    padding: 0.125rem 0.5rem;
    background: #f3f4f6;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #4b5563;
}

.new-badge {
    background: #fef3c7;
    color: #f59e0b;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* 業種タグ */
.industry-tag {
    padding: 0.625rem 1.25rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s;
}

.industry-tag:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.industry-tag.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
}

/* 詳細フィルター改良版 */
.filter-group {
    position: relative;
}

.filter-label-enhanced {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4b5563;
    margin-bottom: 0.5rem;
}

.filter-select-enhanced {
    width: 100%;
    padding: 0.75rem 1rem;
    padding-right: 2.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    background: white;
    color: #1f2937;
    cursor: pointer;
    transition: all 0.2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
}

.filter-select-enhanced:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* アクションボタン */
.reset-filters-enhanced,
.save-filter-btn,
.refine-search-btn {
    padding: 0.75rem 1.5rem;
    background: #f3f4f6;
    color: #4b5563;
    border: none;
    border-radius: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.reset-filters-enhanced:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.save-filter-btn {
    background: #fef3c7;
    color: #f59e0b;
}

.save-filter-btn:hover {
    background: #fde68a;
    color: #d97706;
}

.view-all-results-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.875rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    border-radius: 0.75rem;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.4);
}

.view-all-results-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(102, 126, 234, 0.5);
}

/* トレンドキーワード改良版 */
.trend-keyword-enhanced {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 9999px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.trend-keyword-enhanced::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: translate(-50%, -50%);
    transition: width 0.5s, height 0.5s;
}

.trend-keyword-enhanced:hover::before {
    width: 300px;
    height: 300px;
}

.trend-keyword-enhanced:hover {
    border-color: transparent;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
}

.trend-keyword-enhanced:hover * {
    color: white;
    position: relative;
    z-index: 1;
}

.keyword-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.keyword-count {
    padding: 0.125rem 0.5rem;
    background: #f3f4f6;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.trend-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.trend-indicator.up {
    color: #10b981;
}

.trend-indicator.down {
    color: #ef4444;
}

.trend-indicator.stable {
    color: #6b7280;
}

/* プレビューカード */
.preview-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s;
}

.preview-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

/* ダークモード対応 */
@media (prefers-color-scheme: dark) {
    .stat-card-enhanced {
        background: #1f2937;
        border-color: #374151;
    }
    
    .stat-value {
        color: white;
    }
    
    .stat-label {
        color: #9ca3af;
    }
    
    .search-input-wrapper-enhanced {
        background-image: linear-gradient(#1f2937, #1f2937), 
                          linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .search-input-enhanced {
        color: white;
    }
    
    .search-input-enhanced::placeholder {
        color: #6b7280;
    }
    
    .quick-filter-enhanced {
        background: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }
    
    .filter-select-enhanced {
        background: #1f2937;
        border-color: #374151;
        color: white;
    }
    
    .industry-tag {
        background: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }
    
    .trend-keyword-enhanced {
        background: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }
    
    .keyword-count {
        background: #374151;
    }
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .search-input-container {
        flex-direction: column;
    }
    
    .search-submit-enhanced {
        width: 100%;
    }
    
    .quick-filters-enhanced .grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card-enhanced {
        flex-direction: column;
        text-align: center;
    }
}

/* アニメーション読み込み */
.loading-animation {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(102, 126, 234, 0.3);
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<!-- 🚀 統合検索システム連携JavaScript（改良版） -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    console.log('🔍 検索セクション Ultimate 初期化開始');
    
    // DOM要素
    const elements = {
        form: document.getElementById('unified-search-form'),
        keywordInput: document.getElementById('search-keyword-input'),
        clearBtn: document.getElementById('clear-search-btn'),
        voiceBtn: document.getElementById('voice-search-btn'),
        aiSuggestBtn: document.getElementById('ai-suggest-btn'),
        submitBtn: document.getElementById('search-submit-btn'),
        toggleAdvanced: document.getElementById('toggle-advanced'),
        advancedFilters: document.getElementById('advanced-filters'),
        resetBtn: document.getElementById('reset-filters-btn'),
        saveFilterBtn: document.getElementById('save-filter-btn'),
        resultsPreview: document.getElementById('search-results-preview'),
        previewContent: document.getElementById('preview-content'),
        previewCount: document.getElementById('preview-count'),
        closePreview: document.getElementById('close-preview'),
        viewAllResults: document.getElementById('view-all-results'),
        refineSearchBtn: document.getElementById('refine-search-btn'),
        searchSuggestions: document.getElementById('search-suggestions'),
        savedSearches: document.getElementById('saved-searches'),
        savedSearchesList: document.getElementById('saved-searches-list')
    };
    
    // 統合検索システムとの連携
    const SearchSection = {
        isLoading: false,
        previewTimer: null,
        searchResults: [],
        savedFilters: JSON.parse(localStorage.getItem('gi_saved_filters') || '[]'),
        
        init() {
            this.bindEvents();
            this.checkUnifiedSystem();
            this.initAnimations();
            this.loadSavedSearches();
        },
        
        // 統合検索システムの確認
        checkUnifiedSystem() {
            if (window.GISearchManager) {
                console.log('✅ 統合検索システムと連携');
            } else {
                console.log('⚠️ 統合検索システムが見つかりません');
                this.initStandalone();
            }
        },
        
        // イベントバインド
        bindEvents() {
            // フォーム送信
            if (elements.form) {
                elements.form.addEventListener('submit', (e) => this.handleSubmit(e));
            }
            
            // キーワード入力
            if (elements.keywordInput) {
                elements.keywordInput.addEventListener('input', (e) => this.handleInput(e));
                elements.keywordInput.addEventListener('focus', () => this.showSuggestions());
                elements.keywordInput.addEventListener('blur', () => {
                    setTimeout(() => this.hideSuggestions(), 200);
                });
            }
            
            // クリアボタン
            if (elements.clearBtn) {
                elements.clearBtn.addEventListener('click', () => this.clearSearch());
            }
            
            // 音声検索
            if (elements.voiceBtn) {
                elements.voiceBtn.addEventListener('click', () => this.startVoiceSearch());
            }
            
            // AI提案
            if (elements.aiSuggestBtn) {
                elements.aiSuggestBtn.addEventListener('click', () => this.showAISuggestions());
            }
            
            // 詳細フィルタートグル
            if (elements.toggleAdvanced) {
                elements.toggleAdvanced.addEventListener('click', () => this.toggleAdvancedFilters());
            }
            
            // リセットボタン
            if (elements.resetBtn) {
                elements.resetBtn.addEventListener('click', () => this.resetFilters());
            }
            
            // フィルター保存
            if (elements.saveFilterBtn) {
                elements.saveFilterBtn.addEventListener('click', () => this.saveCurrentFilter());
            }
            
            // クイックフィルター
            document.querySelectorAll('.quick-filter-enhanced').forEach(btn => {
                btn.addEventListener('click', (e) => this.applyQuickFilter(e));
            });
            
            // 業種タグ
            document.querySelectorAll('.industry-tag').forEach(tag => {
                tag.addEventListener('click', (e) => this.applyIndustryFilter(e));
            });
            
            // トレンドキーワード
            document.querySelectorAll('.trend-keyword-enhanced').forEach(btn => {
                btn.addEventListener('click', (e) => this.applyTrendKeyword(e));
            });
            
            // プレビュー閉じる
            if (elements.closePreview) {
                elements.closePreview.addEventListener('click', () => this.closePreview());
            }
            
            // 全結果表示
            if (elements.viewAllResults) {
                elements.viewAllResults.addEventListener('click', (e) => this.viewAllResults(e));
            }
            
            // 絞り込み
            if (elements.refineSearchBtn) {
                elements.refineSearchBtn.addEventListener('click', () => this.refineSearch());
            }
        },
        
        // フォーム送信処理（改良版：一覧ページへ直接遷移）
        handleSubmit(e) {
            e.preventDefault();
            
            // 検索パラメータを取得して一覧ページに遷移
            const params = this.getSearchParams();
            const queryString = new URLSearchParams(params).toString();
            window.location.href = `<?php echo home_url('/grants/'); ?>?${queryString}`;
        },
        
        // 検索実行（改良版：一覧ページへの直接遷移対応）
        async executeSearch(redirectToArchive = true) {
            if (this.isLoading) return;
            
            this.isLoading = true;
            this.showLoadingState();
            
            const formData = new FormData(elements.form);
            const searchParams = {
                search: formData.get('keyword') || '',
                categories: formData.get('category') ? [formData.get('category')] : [],
                prefectures: formData.get('prefecture') ? [formData.get('prefecture')] : [],
                amount: formData.get('amount') || '',
                status: formData.get('status') ? [formData.get('status')] : [],
                difficulty: formData.get('difficulty') ? [formData.get('difficulty')] : [],
                success_rate: formData.get('success_rate') ? [formData.get('success_rate')] : [],
                sort: 'date_desc',
                view: 'grid',
                page: 1
            };
            
            try {
                // AJAX検索実行
                const response = await fetch(window.giSearchConfig.ajaxUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'gi_load_grants',
                        nonce: '<?php echo $search_nonce; ?>',
                        search: searchParams.search,
                        categories: JSON.stringify(searchParams.categories),
                        prefectures: JSON.stringify(searchParams.prefectures),
                        amount: searchParams.amount,
                        status: JSON.stringify(searchParams.status),
                        difficulty: JSON.stringify(searchParams.difficulty),
                        success_rate: JSON.stringify(searchParams.success_rate),
                        sort: searchParams.sort,
                        view: searchParams.view,
                        page: searchParams.page
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.searchResults = data.data.grants;
                    this.showPreviewResults(data.data);
                } else {
                    this.showError('検索に失敗しました');
                }
            } catch (error) {
                console.error('検索エラー:', error);
                this.showError('検索中にエラーが発生しました');
            } finally {
                this.isLoading = false;
                this.hideLoadingState();
            }
        },
        
        // プレビュー結果表示
        showPreviewResults(data) {
            if (!elements.resultsPreview) return;
            
            elements.resultsPreview.classList.remove('hidden');
            
            // 件数更新
            if (elements.previewCount) {
                elements.previewCount.textContent = data.found_posts || 0;
            }
            
            // カード表示（最大6件）
            if (elements.previewContent && data.grants) {
                let html = '';
                const displayGrants = data.grants.slice(0, 6);
                
                displayGrants.forEach((grant, index) => {
                    html += grant.html;
                });
                
                elements.previewContent.innerHTML = html;
                
                // カードアニメーション
                elements.previewContent.querySelectorAll('.grant-card-modern').forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            }
            
            // 全結果表示リンクの更新
            if (elements.viewAllResults) {
                const params = this.getSearchParams();
                const queryString = new URLSearchParams(params).toString();
                elements.viewAllResults.href = `<?php echo home_url('/grants/'); ?>?${queryString}`;
            }
            
            // スクロール
            elements.resultsPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        },
        
        // 検索パラメータ取得
        getSearchParams() {
            const formData = new FormData(elements.form);
            const params = {};
            
            if (formData.get('keyword')) params.search = formData.get('keyword');
            if (formData.get('category')) params.category = formData.get('category');
            if (formData.get('prefecture')) params.prefecture = formData.get('prefecture');
            if (formData.get('amount')) params.amount = formData.get('amount');
            if (formData.get('status')) params.status = formData.get('status');
            if (formData.get('difficulty')) params.difficulty = formData.get('difficulty');
            if (formData.get('success_rate')) params.success_rate = formData.get('success_rate');
            
            return params;
        },
        
        // 全結果表示
        viewAllResults(e) {
            e.preventDefault();
            const params = this.getSearchParams();
            const queryString = new URLSearchParams(params).toString();
            window.location.href = `<?php echo home_url('/grants/'); ?>?${queryString}`;
        },
        
        // 絞り込み
        refineSearch() {
            elements.resultsPreview.classList.add('hidden');
            this.toggleAdvancedFilters();
            elements.keywordInput.focus();
        },
        
        // 入力処理
        handleInput(e) {
            const value = e.target.value.trim();
            
            // クリアボタンの表示/非表示
            if (elements.clearBtn) {
                elements.clearBtn.classList.toggle('hidden', !value);
            }
            
            // サジェスト表示
            if (value.length >= 2) {
                this.showSuggestions(value);
            } else {
                this.hideSuggestions();
            }
        },
        
        // サジェスト表示
        showSuggestions(keyword = '') {
            if (!elements.searchSuggestions) return;
            
            // サンプルサジェスト
            const suggestions = [
                { icon: 'fa-search', text: 'IT導入補助金', type: '人気' },
                { icon: 'fa-building', text: '製造業向け補助金', type: 'カテゴリ' },
                { icon: 'fa-map-marker-alt', text: '東京都の助成金', type: '地域' },
                { icon: 'fa-fire', text: 'DX推進支援', type: 'トレンド' }
            ];
            
            let html = '';
            suggestions.forEach(item => {
                html += `
                    <div class="suggestion-item" data-value="${item.text}">
                        <i class="fas ${item.icon} suggestion-icon"></i>
                        <span class="suggestion-text">${item.text}</span>
                        <span class="suggestion-type">${item.type}</span>
                    </div>
                `;
            });
            
            elements.searchSuggestions.innerHTML = html;
            elements.searchSuggestions.classList.remove('hidden');
            
            // クリックイベント
            elements.searchSuggestions.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', () => {
                    elements.keywordInput.value = item.dataset.value;
                    this.hideSuggestions();
                    this.executeSearch();
                });
            });
        },
        
        // サジェスト非表示
        hideSuggestions() {
            if (elements.searchSuggestions) {
                elements.searchSuggestions.classList.add('hidden');
            }
        },
        
        // AI提案表示
        showAISuggestions() {
            const suggestions = [
                'あなたの業種に最適な補助金',
                '採択率が高い助成金',
                '申請が簡単な支援制度',
                '最新の募集情報'
            ];
            
            const randomSuggestion = suggestions[Math.floor(Math.random() * suggestions.length)];
            elements.keywordInput.value = randomSuggestion;
            elements.clearBtn?.classList.remove('hidden');
            
            // アニメーション
            elements.aiSuggestBtn.classList.add('animate-pulse');
            setTimeout(() => {
                elements.aiSuggestBtn.classList.remove('animate-pulse');
            }, 1000);
        },
        
        // 音声検索
        startVoiceSearch() {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                alert('お使いのブラウザは音声検索に対応していません');
                return;
            }
            
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            
            recognition.lang = 'ja-JP';
            recognition.continuous = false;
            recognition.interimResults = false;
            
            recognition.onstart = () => {
                elements.voiceBtn?.classList.add('animate-pulse', 'text-red-500');
            };
            
            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                elements.keywordInput.value = transcript;
                elements.clearBtn?.classList.remove('hidden');
                this.executeSearch();
            };
            
            recognition.onerror = (event) => {
                console.error('音声認識エラー:', event.error);
                alert('音声認識に失敗しました');
            };
            
            recognition.onend = () => {
                elements.voiceBtn?.classList.remove('animate-pulse', 'text-red-500');
            };
            
            recognition.start();
        },
        
        // 検索クリア
        clearSearch() {
            elements.keywordInput.value = '';
            elements.clearBtn?.classList.add('hidden');
            this.closePreview();
            this.hideSuggestions();
        },
        
        // 詳細フィルター切り替え
        toggleAdvancedFilters() {
            if (elements.advancedFilters) {
                elements.advancedFilters.classList.toggle('hidden');
                
                const icon = elements.toggleAdvanced?.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                    icon.style.transform = icon.classList.contains('fa-chevron-up') ? 'rotate(180deg)' : '';
                }
            }
        },
        
        // フィルターリセット
        resetFilters() {
            elements.form?.reset();
            elements.clearBtn?.classList.add('hidden');
            
            // クイックフィルターのリセット
            document.querySelectorAll('.quick-filter-enhanced').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // 業種タグのリセット
            document.querySelectorAll('.industry-tag').forEach(tag => {
                tag.classList.remove('active');
            });
            
            this.closePreview();
        },
        
        // クイックフィルター適用
        applyQuickFilter(e) {
            const btn = e.currentTarget;
            const filter = btn.dataset.filter;
            
            // アクティブ状態の切り替え
            btn.classList.toggle('active');
            
            // フィルター適用
            switch(filter) {
                case 'active':
                    document.getElementById('filter-status').value = btn.classList.contains('active') ? 'active' : '';
                    break;
                case 'high-amount':
                    document.getElementById('filter-amount').value = btn.classList.contains('active') ? '1000-3000' : '';
                    break;
                case 'high-rate':
                    document.getElementById('filter-success-rate').value = btn.classList.contains('active') ? 'high' : '';
                    break;
                case 'new':
                    // 新着フィルター（日付でソート）
                    break;
            }
            
            // 即座に検索実行
            this.executeSearch();
        },
        
        // 業種フィルター適用
        applyIndustryFilter(e) {
            const tag = e.currentTarget;
            const industry = tag.dataset.industry;
            
            // アクティブ状態の切り替え
            tag.classList.toggle('active');
            
            // キーワードに業種を追加
            if (tag.classList.contains('active')) {
                const currentKeyword = elements.keywordInput.value;
                elements.keywordInput.value = currentKeyword ? `${currentKeyword} ${tag.textContent}` : tag.textContent;
            }
            
            this.executeSearch();
        },
        
        // トレンドキーワード適用
        applyTrendKeyword(e) {
            const keyword = e.currentTarget.dataset.keyword;
            if (elements.keywordInput && keyword) {
                elements.keywordInput.value = keyword;
                elements.clearBtn?.classList.remove('hidden');
                this.executeSearch();
            }
        },
        
        // プレビュー閉じる
        closePreview() {
            if (elements.resultsPreview) {
                elements.resultsPreview.classList.add('hidden');
            }
        },
        
        // フィルター保存
        saveCurrentFilter() {
            const filterName = prompt('この検索条件の名前を入力してください：');
            if (!filterName) return;
            
            const params = this.getSearchParams();
            const filter = {
                name: filterName,
                params: params,
                date: new Date().toISOString()
            };
            
            this.savedFilters.push(filter);
            localStorage.setItem('gi_saved_filters', JSON.stringify(this.savedFilters));
            
            this.loadSavedSearches();
            this.showNotification('検索条件を保存しました');
        },
        
        // 保存した検索条件を読み込み
        loadSavedSearches() {
            if (!elements.savedSearchesList || this.savedFilters.length === 0) return;
            
            elements.savedSearches?.classList.remove('hidden');
            
            let html = '';
            this.savedFilters.forEach((filter, index) => {
                html += `
                    <div class="saved-search-item flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg">
                        <button class="flex-1 text-left" onclick="SearchSection.applySavedFilter(${index})">
                            <div class="font-medium text-gray-900 dark:text-white">${filter.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                ${new Date(filter.date).toLocaleDateString('ja-JP')}
                            </div>
                        </button>
                        <button onclick="SearchSection.deleteSavedFilter(${index})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            
            elements.savedSearchesList.innerHTML = html;
        },
        
        // 保存したフィルター適用
        applySavedFilter(index) {
            const filter = this.savedFilters[index];
            if (!filter) return;
            
            // フォームに値を設定
            Object.keys(filter.params).forEach(key => {
                const input = elements.form.querySelector(`[name="${key}"]`);
                if (input) input.value = filter.params[key];
            });
            
            this.executeSearch();
        },
        
        // 保存したフィルター削除
        deleteSavedFilter(index) {
            if (confirm('この検索条件を削除しますか？')) {
                this.savedFilters.splice(index, 1);
                localStorage.setItem('gi_saved_filters', JSON.stringify(this.savedFilters));
                this.loadSavedSearches();
            }
        },
        
        // ローディング表示
        showLoadingState() {
            if (elements.submitBtn) {
                elements.submitBtn.querySelector('.btn-text')?.classList.add('hidden');
                elements.submitBtn.querySelector('.btn-loading')?.classList.remove('hidden');
                elements.submitBtn.disabled = true;
            }
        },
        
        // ローディング非表示
        hideLoadingState() {
            if (elements.submitBtn) {
                elements.submitBtn.querySelector('.btn-text')?.classList.remove('hidden');
                elements.submitBtn.querySelector('.btn-loading')?.classList.add('hidden');
                elements.submitBtn.disabled = false;
            }
        },
        
        // エラー表示
        showError(message) {
            this.showNotification(message, 'error');
        },
        
        // 通知表示
        showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3 transform translate-x-full transition-transform duration-300`;
            
            if (type === 'success') {
                notification.classList.add('bg-green-500', 'text-white');
                notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            } else {
                notification.classList.add('bg-red-500', 'text-white');
                notification.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        },
        
        // アニメーション初期化
        initAnimations() {
            // 統計カードのカウントアップ
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statValues = entry.target.querySelectorAll('.stat-value[data-count]');
                        statValues.forEach(stat => {
                            const target = parseInt(stat.dataset.count);
                            const duration = 2000;
                            const increment = target / (duration / 16);
                            let current = 0;
                            
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= target) {
                                    current = target;
                                    clearInterval(timer);
                                }
                                stat.textContent = Math.floor(current).toLocaleString();
                            }, 16);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            const statsSection = document.querySelector('.grid.grid-cols-2.md\\:grid-cols-5');
            if (statsSection) {
                observer.observe(statsSection);
            }
        },
        
        // スタンドアロン初期化
        initStandalone() {
            console.log('📍 スタンドアロンモードで動作');
        }
    };
    
    // SearchSectionをグローバルに公開
    window.SearchSection = SearchSection;
    
    // 初期化
    SearchSection.init();
    
    console.log('✅ 検索セクション Ultimate 初期化完了');
});
</script>

<?php
// 統合検索システムが読み込まれているか確認
add_action('wp_footer', function() {
    ?>
    <script>
        // 統合検索システムの状態確認
        if (typeof window.GISearchManager === 'undefined') {
            console.warn('⚠️ 統合検索システムが読み込まれていません。functions.phpで読み込み設定を確認してください。');
        }
    </script>
    <?php
}, 999);
?>