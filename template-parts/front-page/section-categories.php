<?php
/**
 * Ultra Modern Categories Section - Dark Mode Professional Edition
 * カテゴリー別助成金検索セクション - ダークモード対応版
 *
 * @package Grant_Insight_Perfect
 * @version 21.0-dark-mode
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// データベースから実際のカテゴリと件数を取得
$main_categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'orderby' => 'count',
    'order' => 'DESC',
    'number' => 6
));

$all_categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
));

$prefectures = get_terms(array(
    'taxonomy' => 'grant_prefecture',
    'hide_empty' => false,
    'orderby' => 'count',
    'order' => 'DESC'
));

// カテゴリアイコンとカラー設定（ダークモード対応）
$category_configs = array(
    0 => array(
        'icon' => 'fas fa-laptop-code',
        'color' => '#6366f1',
        'color_dark' => '#818cf8',
        'bg' => 'from-indigo-500 to-purple-600',
        'bg_dark' => 'from-indigo-600 to-purple-700',
        'description' => 'IT導入・DX推進・デジタル化支援'
    ),
    1 => array(
        'icon' => 'fas fa-industry',
        'color' => '#ef4444',
        'color_dark' => '#f87171',
        'bg' => 'from-red-500 to-orange-600',
        'bg_dark' => 'from-red-600 to-orange-700',
        'description' => 'ものづくり・製造業支援'
    ),
    2 => array(
        'icon' => 'fas fa-rocket',
        'color' => '#10b981',
        'color_dark' => '#34d399',
        'bg' => 'from-emerald-500 to-teal-600',
        'bg_dark' => 'from-emerald-600 to-teal-700',
        'description' => '創業・スタートアップ支援'
    ),
    3 => array(
        'icon' => 'fas fa-store',
        'color' => '#8b5cf6',
        'color_dark' => '#a78bfa',
        'bg' => 'from-purple-500 to-pink-600',
        'bg_dark' => 'from-purple-600 to-pink-700',
        'description' => '小規模事業者・商業支援'
    ),
    4 => array(
        'icon' => 'fas fa-leaf',
        'color' => '#06b6d4',
        'color_dark' => '#22d3ee',
        'bg' => 'from-cyan-500 to-blue-600',
        'bg_dark' => 'from-cyan-600 to-blue-700',
        'description' => '環境・省エネ・SDGs支援'
    ),
    5 => array(
        'icon' => 'fas fa-users',
        'color' => '#f97316',
        'color_dark' => '#fb923c',
        'bg' => 'from-orange-500 to-amber-600',
        'bg_dark' => 'from-orange-600 to-amber-700',
        'description' => '人材育成・雇用支援'
    )
);

$archive_base_url = get_post_type_archive_link('grant');
?>

<!-- フォント・アイコン読み込み -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- ダークモード対応カテゴリーセクション -->
<section class="ultra-modern-categories" data-theme="auto">
    <!-- ダークモード切替ボタン -->
    <button type="button" class="theme-toggle" aria-label="Toggle dark mode">
        <span class="theme-toggle-light">
            <i class="fas fa-sun"></i>
        </span>
        <span class="theme-toggle-dark">
            <i class="fas fa-moon"></i>
        </span>
    </button>

    <!-- 背景エフェクト -->
    <div class="background-effects">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
        <div class="grid-pattern"></div>
    </div>

    <div class="section-container">
        <!-- セクションヘッダー -->
        <div class="section-header" data-aos="fade-up">
            <div class="header-badge">
                <i class="fas fa-th-large"></i>
                <span>Category Search</span>
            </div>
            
            <h2 class="section-title">
                <span class="title-main">カテゴリーから探す</span>
                <span class="title-sub">業種・目的別の助成金検索</span>
            </h2>
            
            <p class="section-description">
                あなたのビジネスに最適な助成金を、カテゴリーから簡単に検索できます
            </p>

            <!-- 統計情報 -->
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-value"><?php echo count($all_categories); ?></span>
                    <span class="stat-label">カテゴリー</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1,250+</span>
                    <span class="stat-label">助成金</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo count($prefectures); ?></span>
                    <span class="stat-label">都道府県</span>
                </div>
            </div>
        </div>

        <!-- メインカテゴリーグリッド -->
        <div class="main-categories-grid">
            <?php
            if (!empty($main_categories)) :
                foreach ($main_categories as $index => $category) :
                    if ($index >= 6) break;
                    $config = $category_configs[$index] ?? array(
                        'icon' => 'fas fa-folder',
                        'color' => '#6b7280',
                        'color_dark' => '#9ca3af',
                        'bg' => 'from-gray-500 to-gray-600',
                        'bg_dark' => 'from-gray-600 to-gray-700',
                        'description' => ''
                    );
                    $category_url = add_query_arg('grant_category', $category->slug, $archive_base_url);
            ?>
            <div class="category-card" 
                 data-aos="fade-up" 
                 data-aos-delay="<?php echo $index * 50; ?>"
                 data-color="<?php echo $config['color']; ?>"
                 data-color-dark="<?php echo $config['color_dark']; ?>">
                <div class="card-gradient" 
                     data-gradient-light="<?php echo $config['bg']; ?>"
                     data-gradient-dark="<?php echo $config['bg_dark']; ?>"></div>
                
                <div class="card-content">
                    <div class="card-icon" 
                         data-bg-light="<?php echo $config['color']; ?>"
                         data-bg-dark="<?php echo $config['color_dark']; ?>">
                        <i class="<?php echo $config['icon']; ?>"></i>
                    </div>
                    
                    <h3 class="card-title"><?php echo esc_html($category->name); ?></h3>
                    
                    <?php if ($config['description']): ?>
                    <p class="card-description"><?php echo esc_html($config['description']); ?></p>
                    <?php endif; ?>
                    
                    <div class="card-stats">
                        <span class="stat-badge">
                            <i class="fas fa-file-alt"></i>
                            <?php echo number_format($category->count); ?>件
                        </span>
                    </div>
                    
                    <a href="<?php echo esc_url($category_url); ?>" class="card-link">
                        <span>詳細を見る</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>

        <!-- その他のカテゴリー -->
        <?php if (!empty($all_categories) && count($all_categories) > 6) :
            $other_categories = array_slice($all_categories, 6);
        ?>
        <div class="other-categories-section" data-aos="fade-up">
            <button type="button" id="toggle-categories" class="toggle-button">
                <i class="fas fa-plus toggle-icon"></i>
                <span class="toggle-text">その他のカテゴリーを表示</span>
                <span class="count-badge">+<?php echo count($other_categories); ?></span>
            </button>

            <div id="other-categories" class="other-categories-container">
                <div class="categories-grid">
                    <?php foreach ($other_categories as $category) :
                        $category_url = add_query_arg('grant_category', $category->slug, $archive_base_url);
                    ?>
                    <a href="<?php echo esc_url($category_url); ?>" class="mini-category-card">
                        <div class="mini-card-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="mini-card-content">
                            <span class="mini-card-title"><?php echo esc_html($category->name); ?></span>
                            <span class="mini-card-count"><?php echo $category->count; ?>件</span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 地域別検索 -->
        <div class="region-section" data-aos="fade-up">
            <div class="region-header">
                <div class="header-badge secondary">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Regional Search</span>
                </div>
                <h3 class="region-title">地域から探す</h3>
                <p class="region-description">都道府県別の助成金を検索</p>
            </div>

            <div class="regions-grid">
                <?php
                $popular_prefectures = array_slice($prefectures, 0, 12);
                foreach ($popular_prefectures as $index => $prefecture) :
                    $prefecture_url = add_query_arg('grant_prefecture', $prefecture->slug, $archive_base_url);
                    $display_name = str_replace(array('県','府','都','道'), '', $prefecture->name);
                ?>
                <a href="<?php echo esc_url($prefecture_url); ?>" class="region-card <?php echo $index < 3 ? 'featured' : ''; ?>">
                    <?php if ($index < 3): ?>
                    <span class="featured-badge">
                        <i class="fas fa-crown"></i>
                    </span>
                    <?php endif; ?>
                    <span class="region-name"><?php echo esc_html($display_name); ?></span>
                    <span class="region-count"><?php echo $prefecture->count; ?>件</span>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if (count($prefectures) > 12) :
                $other_prefectures = array_slice($prefectures, 12);
            ?>
            <button type="button" id="toggle-regions" class="toggle-button secondary">
                <i class="fas fa-plus toggle-icon"></i>
                <span class="toggle-text">その他の地域を表示</span>
                <span class="count-badge">+<?php echo count($other_prefectures); ?></span>
            </button>

            <div id="other-regions" class="other-regions-container">
                <div class="regions-grid">
                    <?php foreach ($other_prefectures as $prefecture) :
                        $prefecture_url = add_query_arg('grant_prefecture', $prefecture->slug, $archive_base_url);
                        $display_name = str_replace(array('県','府','都','道'), '', $prefecture->name);
                    ?>
                    <a href="<?php echo esc_url($prefecture_url); ?>" class="region-card">
                        <span class="region-name"><?php echo esc_html($display_name); ?></span>
                        <span class="region-count"><?php echo $prefecture->count; ?>件</span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- CTA -->
        <div class="cta-section" data-aos="fade-up">
            <a href="<?php echo esc_url($archive_base_url); ?>" class="cta-button">
                <i class="fas fa-search"></i>
                <span>すべての助成金を検索</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ダークモード対応スタイル -->
<style>
/* CSS変数定義 */
:root {
    /* ライトモード */
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --bg-card: #ffffff;
    --bg-hover: #f8fafc;
    
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-tertiary: #64748b;
    --text-inverse: #ffffff;
    
    --border-primary: #e2e8f0;
    --border-secondary: #cbd5e1;
    
    --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    --gradient-bg: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    --gradient-orb-opacity: 0.3;
    --grid-pattern-color: rgba(0, 0, 0, 0.01);
}

/* ダークモード変数 */
[data-theme="dark"] {
    --bg-primary: #0f172a;
    --bg-secondary: #1e293b;
    --bg-tertiary: #334155;
    --bg-card: #1e293b;
    --bg-hover: #334155;
    
    --text-primary: #f1f5f9;
    --text-secondary: #cbd5e1;
    --text-tertiary: #94a3b8;
    --text-inverse: #0f172a;
    
    --border-primary: #334155;
    --border-secondary: #475569;
    
    --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    
    --gradient-bg: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
    --gradient-orb-opacity: 0.15;
    --grid-pattern-color: rgba(255, 255, 255, 0.02);
}

/* システムのダークモード設定に従う */
@media (prefers-color-scheme: dark) {
    [data-theme="auto"] {
        --bg-primary: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --bg-card: #1e293b;
        --bg-hover: #334155;
        
        --text-primary: #f1f5f9;
        --text-secondary: #cbd5e1;
        --text-tertiary: #94a3b8;
        --text-inverse: #0f172a;
        
        --border-primary: #334155;
        --border-secondary: #475569;
        
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        
        --gradient-bg: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        --gradient-orb-opacity: 0.15;
        --grid-pattern-color: rgba(255, 255, 255, 0.02);
    }
}

/* ベース設定 */
.ultra-modern-categories {
    position: relative;
    padding: 80px 0;
    background: var(--gradient-bg);
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    transition: background 0.3s ease;
}

/* テーマ切替ボタン */
.theme-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--bg-card);
    border: 2px solid var(--border-primary);
    box-shadow: var(--shadow-md);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.theme-toggle:hover {
    transform: scale(1.1);
    box-shadow: var(--shadow-lg);
}

.theme-toggle-light,
.theme-toggle-dark {
    position: absolute;
    font-size: 20px;
    color: var(--text-primary);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.theme-toggle-light {
    opacity: 1;
    transform: rotate(0deg);
}

.theme-toggle-dark {
    opacity: 0;
    transform: rotate(180deg);
}

[data-theme="dark"] .theme-toggle-light,
[data-theme="auto"]:has(.theme-toggle-dark.active) .theme-toggle-light {
    opacity: 0;
    transform: rotate(180deg);
}

[data-theme="dark"] .theme-toggle-dark,
[data-theme="auto"]:has(.theme-toggle-dark.active) .theme-toggle-dark {
    opacity: 1;
    transform: rotate(0deg);
}

/* 背景エフェクト */
.background-effects {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: var(--gradient-orb-opacity);
    transition: opacity 0.3s ease;
}

.orb-1 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    top: -200px;
    right: -100px;
}

[data-theme="dark"] .orb-1 {
    background: linear-gradient(135deg, #4c1d95, #5b21b6);
}

.orb-2 {
    width: 350px;
    height: 350px;
    background: linear-gradient(135deg, #f093fb, #f5576c);
    bottom: -150px;
    left: -100px;
}

[data-theme="dark"] .orb-2 {
    background: linear-gradient(135deg, #831843, #be123c);
}

.orb-3 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

[data-theme="dark"] .orb-3 {
    background: linear-gradient(135deg, #1e3a8a, #0c4a6e);
}

.grid-pattern {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(var(--grid-pattern-color) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid-pattern-color) 1px, transparent 1px);
    background-size: 50px 50px;
}

/* コンテナ */
.section-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* セクションヘッダー */
.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: var(--text-primary);
    color: var(--bg-primary);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 24px;
    transition: all 0.3s ease;
}

.header-badge.secondary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
}

[data-theme="dark"] .header-badge.secondary {
    background: linear-gradient(135deg, #60a5fa, #a78bfa);
}

.section-title {
    margin-bottom: 16px;
}

.title-main {
    display: block;
    font-size: clamp(32px, 5vw, 48px);
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1.1;
    margin-bottom: 8px;
}

.title-sub {
    display: block;
    font-size: clamp(16px, 2vw, 20px);
    font-weight: 400;
    color: var(--text-tertiary);
}

.section-description {
    font-size: 16px;
    color: var(--text-tertiary);
    max-width: 600px;
    margin: 0 auto 32px;
    line-height: 1.6;
}

/* 統計情報 */
.stats-row {
    display: flex;
    justify-content: center;
    gap: 48px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* メインカテゴリーグリッド */
.main-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 48px;
}

@media (min-width: 768px) {
    .main-categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .main-categories-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* カテゴリーカード */
.category-card {
    position: relative;
    background: var(--bg-card);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: 1px solid var(--border-primary);
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--border-secondary);
}

[data-theme="dark"] .category-card:hover {
    background: var(--bg-hover);
}

.card-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    transition: height 0.3s ease;
}

.category-card:hover .card-gradient {
    height: 6px;
}

.card-content {
    padding: 32px;
}

.card-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-inverse);
    font-size: 24px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

[data-theme="dark"] .card-icon {
    color: var(--text-primary);
    background: var(--bg-tertiary) !important;
}

.card-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.card-description {
    font-size: 14px;
    color: var(--text-tertiary);
    line-height: 1.5;
    margin-bottom: 20px;
}

.card-stats {
    margin-bottom: 24px;
}

.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--bg-tertiary);
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
}

.card-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: gap 0.3s ease;
}

.card-link:hover {
    gap: 12px;
}

/* その他のカテゴリー */
.other-categories-section {
    margin-bottom: 60px;
}

.toggle-button {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 auto 32px;
    padding: 14px 28px;
    background: var(--bg-card);
    border: 2px solid var(--border-primary);
    border-radius: 999px;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-button:hover {
    background: var(--bg-hover);
    border-color: var(--border-secondary);
    transform: translateY(-2px);
}

.toggle-button.secondary {
    background: linear-gradient(135deg, var(--bg-secondary), var(--bg-card));
}

.toggle-icon {
    transition: transform 0.3s ease;
}

.toggle-button.active .toggle-icon {
    transform: rotate(45deg);
}

.count-badge {
    padding: 4px 10px;
    background: var(--text-primary);
    color: var(--bg-primary);
    border-radius: 999px;
    font-size: 12px;
}

.other-categories-container,
.other-regions-container {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease;
}

.other-categories-container.show,
.other-regions-container.show {
    max-height: 2000px;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    padding: 32px;
    background: var(--bg-secondary);
    border-radius: 20px;
    border: 1px solid var(--border-primary);
}

/* ミニカテゴリーカード */
.mini-category-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--bg-card);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid var(--border-primary);
}

.mini-category-card:hover {
    background: var(--bg-hover);
    transform: translateX(4px);
    border-color: var(--border-secondary);
}

.mini-card-icon {
    width: 40px;
    height: 40px;
    background: var(--bg-secondary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-tertiary);
}

.mini-card-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mini-card-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
}

.mini-card-count {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-tertiary);
    padding: 4px 8px;
    background: var(--bg-tertiary);
    border-radius: 999px;
}

/* 地域セクション */
.region-section {
    margin-bottom: 60px;
}

.region-header {
    text-align: center;
    margin-bottom: 40px;
}

.region-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 16px 0 8px;
}

.region-description {
    font-size: 16px;
    color: var(--text-tertiary);
}

.regions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
    margin-bottom: 32px;
}

@media (min-width: 640px) {
    .regions-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
}

/* 地域カード */
.region-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: var(--bg-card);
    border: 2px solid var(--border-primary);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    min-height: 80px;
}

.region-card:hover {
    background: var(--bg-hover);
    border-color: #3b82f6;
    transform: translateY(-4px);
}

[data-theme="dark"] .region-card:hover {
    border-color: #60a5fa;
}

.region-card.featured {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-color: #fbbf24;
}

[data-theme="dark"] .region-card.featured {
    background: linear-gradient(135deg, #78350f, #92400e);
    border-color: #f59e0b;
}

.featured-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    background: #fbbf24;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 12px;
}

[data-theme="dark"] .featured-badge {
    background: #f59e0b;
}

.region-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.region-count {
    font-size: 12px;
    color: var(--text-tertiary);
}

/* CTA */
.cta-section {
    text-align: center;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #fff;
    border-radius: 999px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-lg);
}

[data-theme="dark"] .cta-button {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
    gap: 16px;
}

/* アニメーション */
[data-aos] {
    transition-property: transform, opacity;
}

/* トランジション */
* {
    transition-property: background-color, border-color, color;
    transition-duration: 0.3s;
    transition-timing-function: ease;
}

/* レスポンシブ */
@media (max-width: 640px) {
    .ultra-modern-categories {
        padding: 60px 0;
    }
    
    .theme-toggle {
        width: 40px;
        height: 40px;
        top: 10px;
        right: 10px;
    }
    
    .theme-toggle-light,
    .theme-toggle-dark {
        font-size: 16px;
    }
    
    .stats-row {
        gap: 24px;
    }
    
    .stat-value {
        font-size: 24px;
    }
    
    .main-categories-grid {
        grid-template-columns: 1fr;
    }
    
    .card-content {
        padding: 24px;
    }
    
    .regions-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<!-- JavaScript（ダークモード対応） -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ダークモード管理
    const section = document.querySelector('.ultra-modern-categories');
    const themeToggle = document.querySelector('.theme-toggle');
    
    // ローカルストレージから設定を読み込み
    const savedTheme = localStorage.getItem('grant-categories-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // 初期テーマ設定
    if (savedTheme) {
        section.setAttribute('data-theme', savedTheme);
    } else if (systemPrefersDark) {
        section.setAttribute('data-theme', 'dark');
    } else {
        section.setAttribute('data-theme', 'light');
    }
    
    // グラデーション動的適用
    function applyDynamicStyles() {
        const isDark = section.getAttribute('data-theme') === 'dark' || 
                      (section.getAttribute('data-theme') === 'auto' && systemPrefersDark);
        
        // カードグラデーション
        document.querySelectorAll('.card-gradient').forEach(el => {
            const lightGradient = el.getAttribute('data-gradient-light');
            const darkGradient = el.getAttribute('data-gradient-dark');
            const gradientColors = isDark ? darkGradient : lightGradient;
            
            if (gradientColors) {
                const colors = gradientColors.replace('from-', '').replace('to-', ',');
                el.style.background = `linear-gradient(135deg, ${colors.replace('-500', isDark ? '-600' : '-500').replace('-600', isDark ? '-700' : '-600')})`;
            }
        });
        
        // カードアイコン背景
        document.querySelectorAll('.card-icon').forEach(el => {
            const lightBg = el.getAttribute('data-bg-light');
            const darkBg = el.getAttribute('data-bg-dark');
            
            if (!isDark && lightBg) {
                el.style.background = lightBg;
            }
        });
    }
    
    // 初期スタイル適用
    applyDynamicStyles();
    
    // テーマ切替
    themeToggle.addEventListener('click', function() {
        const currentTheme = section.getAttribute('data-theme');
        let newTheme;
        
        if (currentTheme === 'light' || (currentTheme === 'auto' && !systemPrefersDark)) {
            newTheme = 'dark';
        } else {
            newTheme = 'light';
        }
        
        section.setAttribute('data-theme', newTheme);
        localStorage.setItem('grant-categories-theme', newTheme);
        applyDynamicStyles();
        
        // アニメーション
        this.style.transform = 'scale(0.9)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 200);
    });
    
    // システムテーマ変更監視
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (section.getAttribute('data-theme') === 'auto') {
            applyDynamicStyles();
        }
    });
    
    // AOS風アニメーション
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('[data-aos]').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        const delay = el.getAttribute('data-aos-delay');
        if (delay) {
            el.style.transitionDelay = delay + 'ms';
        }
        
        observer.observe(el);
    });
    
    // カテゴリー開閉
    const toggleCategories = document.getElementById('toggle-categories');
    const otherCategories = document.getElementById('other-categories');
    
    if (toggleCategories && otherCategories) {
        toggleCategories.addEventListener('click', function() {
            const isOpen = otherCategories.classList.contains('show');
            
            if (isOpen) {
                otherCategories.classList.remove('show');
                this.classList.remove('active');
                this.querySelector('.toggle-text').textContent = 'その他のカテゴリーを表示';
                this.querySelector('.toggle-icon').classList.remove('fa-minus');
                this.querySelector('.toggle-icon').classList.add('fa-plus');
            } else {
                otherCategories.classList.add('show');
                this.classList.add('active');
                this.querySelector('.toggle-text').textContent = 'その他のカテゴリーを閉じる';
                this.querySelector('.toggle-icon').classList.remove('fa-plus');
                this.querySelector('.toggle-icon').classList.add('fa-minus');
            }
        });
    }
    
    // 地域開閉
    const toggleRegions = document.getElementById('toggle-regions');
    const otherRegions = document.getElementById('other-regions');
    
    if (toggleRegions && otherRegions) {
        toggleRegions.addEventListener('click', function() {
            const isOpen = otherRegions.classList.contains('show');
            
            if (isOpen) {
                otherRegions.classList.remove('show');
                this.classList.remove('active');
                this.querySelector('.toggle-text').textContent = 'その他の地域を表示';
                this.querySelector('.toggle-icon').classList.remove('fa-minus');
                this.querySelector('.toggle-icon').classList.add('fa-plus');
            } else {
                otherRegions.classList.add('show');
                this.classList.add('active');
                this.querySelector('.toggle-text').textContent = 'その他の地域を閉じる';
                this.querySelector('.toggle-icon').classList.remove('fa-plus');
                this.querySelector('.toggle-icon').classList.add('fa-minus');
            }
        });
    }
    
    // カードホバーエフェクト
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // パフォーマンス最適化
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.willChange = 'transform';
                    imageObserver.unobserve(entry.target);
                }
            });
        });
        
        document.querySelectorAll('.category-card, .region-card').forEach(card => {
            imageObserver.observe(card);
        });
    }
    
    console.log('🌙 Dark Mode Categories Section initialized');
    console.log('Current theme:', section.getAttribute('data-theme'));
});
</script>
