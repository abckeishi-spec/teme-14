<?php
/**
 * 補助金・助成金情報サイト - ダーク/ライトモード完全対応ヒーローセクション
 * Grant & Subsidy Information Site - Dark/Light Mode Complete Hero Section
 * @package Grant_Insight_Professional
 * @version 27.0-dual-theme-complete
 * 
 * === 主要機能 ===
 * 1. 完全ダーク/ライトモード対応
 * 2. PC + タブレット + スマートフォン重ね合わせ表示
 * 3. テーマ別最適化されたカラースキーム
 * 4. スムーズなテーマ切り替えアニメーション
 * 5. 高度なインタラクティブ要素
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

// ヘルパー関数
if (!function_exists('gip_safe_output')) {
    function gip_safe_output($text, $allow_html = false) {
        return $allow_html ? wp_kses_post($text) : esc_html($text);
    }
}

if (!function_exists('gip_get_option')) {
    function gip_get_option($key, $default = '') {
        $value = get_option('gip_' . $key, $default);
        return !empty($value) ? $value : $default;
    }
}

// 設定データ
$hero_config = array(
    'main_title' => gip_get_option('hero_main_title', '補助金・助成金を'),
    'sub_title' => gip_get_option('hero_sub_title', 'AIが瞬時に発見'),
    'description' => gip_get_option('hero_description', 'あなたのビジネスに最適な補助金・助成金情報を、最新AIテクノロジーが瞬時に発見。専門家による申請サポートで成功率98.7%を実現します。'),
    'cta_primary_text' => gip_get_option('hero_cta_primary_text', '無料で助成金を探す'),
    'cta_secondary_text' => gip_get_option('hero_cta_secondary_text', 'AI専門家に相談')
);

// リアルタイム統計データ
$live_stats = array(
    array('number' => '12,847', 'label' => '助成金データベース', 'icon' => '🗄️', 'color' => 'indigo', 'animatable' => true),
    array('number' => '98.7%', 'label' => 'マッチング精度', 'icon' => '🎯', 'color' => 'purple', 'animatable' => true),
    array('number' => '24時間', 'label' => 'AI自動更新', 'icon' => '🔄', 'color' => 'blue', 'animatable' => true),
    array('number' => '完全無料', 'label' => 'サービス利用', 'icon' => '🎁', 'color' => 'green', 'animatable' => false)
);

// タブレット用統計データ
$tablet_stats = array(
    array('number' => '2,847', 'label' => '今月の新着', 'icon' => '📈'),
    array('number' => '156', 'label' => '申請成功', 'icon' => '✅'),
    array('number' => '24/7', 'label' => 'サポート', 'icon' => '🛠️')
);

// スマートフォン用クイック統計
$mobile_quick_stats = array(
    array('number' => '98.7%', 'label' => '成功率', 'color' => 'green'),
    array('number' => '3分', 'label' => '検索時間', 'color' => 'blue'),
    array('number' => '無料', 'label' => '利用料金', 'color' => 'purple')
);
?>

<section id="hero-section" class="hero-dual-theme" role="banner" aria-label="補助金・助成金AIプラットフォーム">
    
    <!-- テーマ切り替えボタン -->
    <div class="theme-toggle-container">
        <button id="theme-toggle" class="theme-toggle-btn" aria-label="テーマ切り替え">
            <div class="toggle-track">
                <div class="toggle-thumb"></div>
            </div>
            <div class="toggle-icons">
                <span class="sun-icon">☀️</span>
                <span class="moon-icon">🌙</span>
            </div>
        </button>
    </div>
    
    <!-- 動的背景システム -->
    <div class="bg-system" aria-hidden="true">
        <div class="bg-layer bg-gradient"></div>
        <div class="bg-layer bg-particles"></div>
        <div class="bg-layer bg-mesh"></div>
        <div class="floating-elements">
            <?php for ($i = 1; $i <= 12; $i++): ?>
            <div class="float-element float-<?php echo $i; ?>"></div>
            <?php endfor; ?>
        </div>
        <!-- ライトモード用追加背景要素 -->
        <div class="light-mode-shapes">
            <div class="shape shape-circle"></div>
            <div class="shape shape-triangle"></div>
            <div class="shape shape-square"></div>
        </div>
    </div>
    
    <!-- メインコンテンツ -->
    <div class="container-main">
        
        <!-- デスクトップレイアウト -->
        <div class="desktop-layout">
            <div class="content-grid">
                
                <!-- 左側：メインコンテンツ -->
                <div class="content-main">
                    
                    <!-- プレミアムバッジ -->
                    <div class="premium-badge" role="note" aria-label="プレミアムAIプラットフォーム">
                        <div class="badge-glow" aria-hidden="true"></div>
                        <div class="badge-content">
                            <div class="status-dot" aria-hidden="true"></div>
                            <span class="badge-text">PREMIUM AI PLATFORM</span>
                            <div class="badge-pulse" aria-hidden="true"></div>
                        </div>
                    </div>
                    
                    <!-- メインタイトル -->
                    <h1 class="main-title">
                        <span class="title-line title-line-1"><?php echo gip_safe_output($hero_config['main_title']); ?></span>
                        <span class="title-line title-line-2">
                            <span class="ai-highlight"><?php echo gip_safe_output($hero_config['sub_title']); ?></span>
                        </span>
                        <span class="title-line title-line-3">成功まで完全サポート</span>
                    </h1>
                    
                    <!-- 説明文 -->
                    <p class="description">
                        <?php echo gip_safe_output($hero_config['description']); ?>
                    </p>
                    
                    <!-- CTAボタン -->
                    <div class="cta-container">
                        <button onclick="startGrantSearch()" class="btn-primary" aria-label="無料で助成金を探す">
                            <div class="btn-bg" aria-hidden="true"></div>
                            <div class="btn-content">
                                <span class="btn-icon" aria-hidden="true">🔍</span>
                                <span class="btn-text"><?php echo gip_safe_output($hero_config['cta_primary_text']); ?></span>
                                <span class="btn-arrow" aria-hidden="true">→</span>
                            </div>
                            <div class="btn-shine" aria-hidden="true"></div>
                        </button>
                        
                        <button onclick="openAIConsultation()" class="btn-secondary" aria-label="AI専門家に相談">
                            <div class="btn-content">
                                <span class="btn-icon" aria-hidden="true">🤖</span>
                                <span class="btn-text"><?php echo gip_safe_output($hero_config['cta_secondary_text']); ?></span>
                            </div>
                        </button>
                    </div>
                </div>
                
                <!-- 右側：マルチデバイスビジュアル -->
                <div class="visual-main">
                    <div class="multidevice-system">
                        
                        <!-- PCモニター（メイン） -->
                        <div class="pc-monitor">
                            <div class="monitor-frame">
                                <div class="monitor-bezel">
                                    <div class="monitor-brand">GRANT AI PRO</div>
                                    <div class="power-indicator"></div>
                                </div>
                                
                                <!-- PC画面部分 -->
                                <div class="monitor-screen">
                                    <div class="screen-reflection" aria-hidden="true"></div>
                                    <div class="screen-content">
                                        
                                        <!-- システムヘッダー -->
                                        <div class="system-header">
                                            <div class="window-controls">
                                                <div class="control-btn close"></div>
                                                <div class="control-btn minimize"></div>
                                                <div class="control-btn maximize"></div>
                                            </div>
                                            <div class="system-title">
                                                <span class="title-icon">📊</span>
                                                助成金マッチングシステム
                                            </div>
                                            <div class="system-status">
                                                <div class="status-dot active"></div>
                                                <span>稼働中</span>
                                            </div>
                                        </div>
                                        
                                        <!-- メインダッシュボード -->
                                        <div class="dashboard-main">
                                            
                                            <!-- 左パネル：統計情報 -->
                                            <div class="dashboard-left">
                                                <div class="stats-panel">
                                                    <div class="panel-header">
                                                        <h3>📈 リアルタイム統計</h3>
                                                        <div class="live-indicator">
                                                            <div class="live-dot"></div>
                                                            <span>LIVE</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="stats-grid">
                                                        <?php foreach ($live_stats as $stat): ?>
                                                        <div class="stat-card-screen stat-<?php echo esc_attr($stat['color']); ?>">
                                                            <div class="stat-icon-screen"><?php echo $stat['icon']; ?></div>
                                                            <div class="stat-content-screen">
                                                                <?php if ($stat['animatable']): ?>
                                                                    <div class="stat-number-screen" data-target="<?php echo esc_attr($stat['number']); ?>">
                                                                        <?php echo gip_safe_output($stat['number']); ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="stat-number-screen static-number">
                                                                        <?php echo gip_safe_output($stat['number']); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="stat-label-screen"><?php echo gip_safe_output($stat['label']); ?></div>
                                                            </div>
                                                            <?php if ($stat['animatable']): ?>
                                                            <div class="stat-trend-screen">
                                                                <div class="trend-line">
                                                                    <svg viewBox="0 0 60 20" aria-hidden="true">
                                                                        <path class="trend-path" d="M5,15 Q15,10 25,8 T45,5 T55,3"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- 右パネル：メイン機能 -->
                                            <div class="dashboard-right">
                                                
                                                <!-- プログレスリング -->
                                                <div class="progress-section">
                                                    <div class="progress-container-screen">
                                                        <div class="progress-ring-screen">
                                                            <svg viewBox="0 0 120 120">
                                                                <circle class="progress-bg-screen" cx="60" cy="60" r="50"></circle>
                                                                <circle class="progress-fill-screen" cx="60" cy="60" r="50"></circle>
                                                            </svg>
                                                            <div class="progress-content-screen">
                                                                <div class="progress-number-screen">98.7%</div>
                                                                <div class="progress-label-screen">マッチング精度</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- アクティビティフィード -->
                                                <div class="activity-feed">
                                                    <div class="activity-header">
                                                        <h4>🔄 最新アクティビティ</h4>
                                                    </div>
                                                    <div class="activity-list">
                                                        <div class="activity-item">
                                                            <div class="activity-icon">✅</div>
                                                            <div class="activity-text">
                                                                <span>新規助成金情報を3件追加</span>
                                                                <span class="activity-time">2分前</span>
                                                            </div>
                                                        </div>
                                                        <div class="activity-item">
                                                            <div class="activity-icon">🎯</div>
                                                            <div class="activity-text">
                                                                <span>マッチング精度を更新</span>
                                                                <span class="activity-time">5分前</span>
                                                            </div>
                                                        </div>
                                                        <div class="activity-item">
                                                            <div class="activity-icon">🔍</div>
                                                            <div class="activity-text">
                                                                <span>データベース同期完了</span>
                                                                <span class="activity-time">10分前</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- システムフッター -->
                                        <div class="system-footer">
                                            <div class="footer-info">
                                                <span>🔒 SSL暗号化通信</span>
                                                <span>⚡ 高速処理中</span>
                                                <span>🛡️ セキュア接続</span>
                                            </div>
                                            <div class="footer-status">
                                                システム正常稼働中 | CPU: 23% | RAM: 45%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- モニタースタンド -->
                            <div class="monitor-stand">
                                <div class="stand-neck"></div>
                                <div class="stand-base"></div>
                            </div>
                        </div>
                        
                        <!-- タブレット（重なり表示） -->
                        <div class="tablet-device">
                            <div class="tablet-frame">
                                <div class="tablet-screen">
                                    <div class="tablet-content">
                                        
                                        <!-- タブレットヘッダー -->
                                        <div class="tablet-header">
                                            <div class="tablet-time">14:32</div>
                                            <div class="tablet-status-icons">
                                                <span>📶</span>
                                                <span>🔋</span>
                                            </div>
                                        </div>
                                        
                                        <!-- タブレットアプリ画面 -->
                                        <div class="tablet-app">
                                            <div class="app-header">
                                                <div class="app-icon">📱</div>
                                                <div class="app-title">Grant Finder</div>
                                            </div>
                                            
                                            <!-- タブレット統計 -->
                                            <div class="tablet-stats">
                                                <?php foreach ($tablet_stats as $stat): ?>
                                                <div class="tablet-stat-card">
                                                    <div class="tablet-stat-icon"><?php echo $stat['icon']; ?></div>
                                                    <div class="tablet-stat-content">
                                                        <div class="tablet-stat-number"><?php echo gip_safe_output($stat['number']); ?></div>
                                                        <div class="tablet-stat-label"><?php echo gip_safe_output($stat['label']); ?></div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            
                                            <!-- タブレットチャート -->
                                            <div class="tablet-chart">
                                                <div class="chart-title">📊 月間推移</div>
                                                <div class="chart-bars">
                                                    <?php for ($i = 0; $i < 7; $i++): ?>
                                                    <div class="chart-bar" style="height: <?php echo rand(30, 80); ?>%;"></div>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- タブレットホームボタン -->
                                <div class="tablet-home-btn"></div>
                            </div>
                        </div>
                        
                        <!-- スマートフォン（重なり表示） -->
                        <div class="smartphone-device">
                            <div class="smartphone-frame">
                                <div class="smartphone-screen">
                                    <div class="smartphone-content">
                                        
                                        <!-- スマートフォンステータスバー -->
                                        <div class="smartphone-statusbar">
                                            <div class="statusbar-time">14:32</div>
                                            <div class="statusbar-icons">
                                                <span>📶</span>
                                                <span>🔋</span>
                                            </div>
                                        </div>
                                        
                                        <!-- スマートフォンアプリ -->
                                        <div class="smartphone-app">
                                            <div class="smartphone-app-header">
                                                <div class="smartphone-app-icon">💰</div>
                                                <div class="smartphone-app-title">助成金AI</div>
                                            </div>
                                            
                                            <!-- クイック統計 -->
                                            <div class="smartphone-quick-stats">
                                                <?php foreach ($mobile_quick_stats as $stat): ?>
                                                <div class="smartphone-stat-item stat-<?php echo esc_attr($stat['color']); ?>">
                                                    <div class="smartphone-stat-number"><?php echo gip_safe_output($stat['number']); ?></div>
                                                    <div class="smartphone-stat-label"><?php echo gip_safe_output($stat['label']); ?></div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            
                                            <!-- アクションボタン -->
                                            <div class="smartphone-action">
                                                <div class="smartphone-btn">
                                                    <span>🔍</span>
                                                    <span>検索開始</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- デバイス反射効果 -->
                        <div class="devices-reflection" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- モバイルレイアウト -->
        <div class="mobile-layout">
            <div class="mobile-content">
                
                <!-- モバイルバッジ -->
                <div class="mobile-badge">
                    <div class="mobile-status-dot"></div>
                    <span>PREMIUM AI PLATFORM</span>
                </div>
                
                <!-- モバイルタイトル -->
                <h1 class="mobile-title">
                    <span class="mobile-title-1"><?php echo gip_safe_output($hero_config['main_title']); ?></span>
                    <span class="mobile-title-2">
                        <span class="mobile-ai-highlight"><?php echo gip_safe_output($hero_config['sub_title']); ?></span>
                    </span>
                </h1>
                
                <!-- モバイル説明 -->
                <p class="mobile-description">
                    最新AIテクノロジーがあなたのビジネスに最適な補助金・助成金を瞬時に発見。専門家による完全サポートで成功率98.7%を実現。
                </p>
                
                <!-- モバイル統計 -->
                <div class="mobile-stats">
                    <div class="mobile-stats-header">
                        <h3>📱 リアルタイム統計</h3>
                        <div class="mobile-live-indicator">
                            <div class="mobile-live-dot"></div>
                            <span>LIVE</span>
                        </div>
                    </div>
                    
                    <div class="mobile-stats-scroll">
                        <?php foreach ($live_stats as $stat): ?>
                        <div class="mobile-stat-card">
                            <div class="mobile-stat-icon"><?php echo $stat['icon']; ?></div>
                            <div class="mobile-stat-content">
                                <?php if ($stat['animatable']): ?>
                                    <div class="mobile-stat-number" data-target="<?php echo esc_attr($stat['number']); ?>">
                                        <?php echo gip_safe_output($stat['number']); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="mobile-stat-number static-number">
                                        <?php echo gip_safe_output($stat['number']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="mobile-stat-label"><?php echo gip_safe_output($stat['label']); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- モバイルCTA -->
                <div class="mobile-cta">
                    <button onclick="startGrantSearch()" class="mobile-btn-primary">
                        <span class="btn-icon">🔍</span>
                        <span><?php echo gip_safe_output($hero_config['cta_primary_text']); ?></span>
                    </button>
                    
                    <button onclick="openAIConsultation()" class="mobile-btn-secondary">
                        <span class="btn-icon">🤖</span>
                        <span><?php echo gip_safe_output($hero_config['cta_secondary_text']); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
:root {
    /* === ダークモード カラーシステム === */
    --dark-bg-primary: #0a0e1a;
    --dark-bg-secondary: #0f172a;
    --dark-bg-tertiary: #1e293b;
    --dark-surface: rgba(15, 23, 42, 0.95);
    --dark-surface-elevated: rgba(30, 41, 59, 0.98);
    --dark-text-primary: #f8fafc;
    --dark-text-secondary: #cbd5e1;
    --dark-text-tertiary: #94a3b8;
    
    /* === ライトモード カラーシステム === */
    --light-bg-primary: #ffffff;
    --light-bg-secondary: #f8fafc;
    --light-bg-tertiary: #f1f5f9;
    --light-surface: rgba(255, 255, 255, 0.95);
    --light-surface-elevated: rgba(248, 250, 252, 0.98);
    --light-text-primary: #0f172a;
    --light-text-secondary: #475569;
    --light-text-tertiary: #64748b;
    
    /* === アクセントカラー（共通） === */
    --accent-indigo: #6366f1;
    --accent-purple: #a855f7;
    --accent-blue: #3b82f6;
    --accent-green: #10b981;
    --accent-danger: #ef4444;
    --accent-cyan: #00d4ff;
    --accent-orange: #f97316;
    --accent-pink: #ec4899;
    
    /* === グラデーション === */
    --gradient-primary: linear-gradient(135deg, var(--accent-indigo), var(--accent-purple));
    --gradient-secondary: linear-gradient(135deg, var(--accent-purple), var(--accent-blue));
    --gradient-tertiary: linear-gradient(135deg, var(--accent-cyan), var(--accent-blue));
    --gradient-light: linear-gradient(135deg, #e0e7ff, #fce7f3);
    --gradient-warm: linear-gradient(135deg, #fef3c7, #fde68a);
    
    /* === デバイス専用カラー === */
    --monitor-bezel-dark: #1a1a1a;
    --monitor-bezel-light: #e5e7eb;
    --tablet-frame-dark: #2a2a2a;
    --tablet-frame-light: #d1d5db;
    --smartphone-frame-dark: #333333;
    --smartphone-frame-light: #9ca3af;
    
    /* === スペーシング === */
    --spacing-xs: 0.375rem;
    --spacing-sm: 0.75rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    
    /* === ボーダーラディウス === */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    
    /* === シャドウ === */
    --shadow-sm: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 8px -2px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 16px -4px rgba(0, 0, 0, 0.15);
    --shadow-xl: 0 12px 24px -6px rgba(0, 0, 0, 0.2);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    /* === ダークモード専用シャドウ === */
    --shadow-dark-sm: 0 2px 4px -1px rgba(0, 0, 0, 0.3);
    --shadow-dark-md: 0 4px 8px -2px rgba(0, 0, 0, 0.4);
    --shadow-dark-lg: 0 8px 16px -4px rgba(0, 0, 0, 0.5);
    --shadow-dark-xl: 0 12px 24px -6px rgba(0, 0, 0, 0.6);
    --shadow-dark-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
    
    /* === ライトモード専用シャドウ === */
    --shadow-light-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    --shadow-light-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07);
    --shadow-light-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-light-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.12);
    --shadow-light-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    
    /* === トランジション === */
    --transition-fast: 0.15s ease-out;
    --transition-base: 0.25s ease-out;
    --transition-slow: 0.4s ease-out;
    --transition-theme: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* === デフォルト（ダークモード） === */
body {
    --current-bg-primary: var(--dark-bg-primary);
    --current-bg-secondary: var(--dark-bg-secondary);
    --current-bg-tertiary: var(--dark-bg-tertiary);
    --current-surface: var(--dark-surface);
    --current-surface-elevated: var(--dark-surface-elevated);
    --current-text-primary: var(--dark-text-primary);
    --current-text-secondary: var(--dark-text-secondary);
    --current-text-tertiary: var(--dark-text-tertiary);
    --current-shadow-sm: var(--shadow-dark-sm);
    --current-shadow-md: var(--shadow-dark-md);
    --current-shadow-lg: var(--shadow-dark-lg);
    --current-shadow-xl: var(--shadow-dark-xl);
    --current-shadow-2xl: var(--shadow-dark-2xl);
    --monitor-bezel: var(--monitor-bezel-dark);
    --tablet-frame: var(--tablet-frame-dark);
    --smartphone-frame: var(--smartphone-frame-dark);
}

/* === ライトモード === */
body.light-theme {
    --current-bg-primary: var(--light-bg-primary);
    --current-bg-secondary: var(--light-bg-secondary);
    --current-bg-tertiary: var(--light-bg-tertiary);
    --current-surface: var(--light-surface);
    --current-surface-elevated: var(--light-surface-elevated);
    --current-text-primary: var(--light-text-primary);
    --current-text-secondary: var(--light-text-secondary);
    --current-text-tertiary: var(--light-text-tertiary);
    --current-shadow-sm: var(--shadow-light-sm);
    --current-shadow-md: var(--shadow-light-md);
    --current-shadow-lg: var(--shadow-light-lg);
    --current-shadow-xl: var(--shadow-light-xl);
    --current-shadow-2xl: var(--shadow-light-2xl);
    --monitor-bezel: var(--monitor-bezel-light);
    --tablet-frame: var(--tablet-frame-light);
    --smartphone-frame: var(--smartphone-frame-light);
}

/* === テーマ切り替えボタン === */
.theme-toggle-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.theme-toggle-btn {
    position: relative;
    width: 64px;
    height: 32px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

.toggle-track {
    position: absolute;
    inset: 0;
    background: var(--current-surface-elevated);
    backdrop-filter: blur(20px);
    border: 2px solid rgba(99, 102, 241, 0.3);
    border-radius: 20px;
    transition: var(--transition-theme);
    box-shadow: var(--current-shadow-md);
}

.toggle-thumb {
    position: absolute;
    top: 2px;
    left: 2px;
    width: 24px;
    height: 24px;
    background: var(--gradient-primary);
    border-radius: 50%;
    transition: var(--transition-theme);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

body.light-theme .toggle-thumb {
    transform: translateX(32px);
    background: var(--gradient-warm);
}

.toggle-icons {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 8px;
    pointer-events: none;
}

.sun-icon,
.moon-icon {
    font-size: 0.875rem;
    transition: var(--transition-theme);
}

.sun-icon {
    opacity: 0;
}

.moon-icon {
    opacity: 1;
}

body.light-theme .sun-icon {
    opacity: 1;
}

body.light-theme .moon-icon {
    opacity: 0;
}

.theme-toggle-btn:hover .toggle-track {
    border-color: rgba(99, 102, 241, 0.5);
    box-shadow: var(--current-shadow-lg);
}

/* === ベーススタイル === */
.hero-dual-theme {
    font-family: 'Inter', 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    padding: var(--spacing-xl) 0;
    background: var(--current-bg-primary);
    color: var(--current-text-primary);
    transition: var(--transition-theme);
}

/* === 背景システム === */
.bg-system {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.bg-layer {
    position: absolute;
    inset: 0;
}

/* ダークモード背景 */
.bg-gradient {
    background: linear-gradient(135deg, 
        var(--current-bg-primary) 0%, 
        var(--current-bg-secondary) 30%, 
        var(--current-bg-tertiary) 60%,
        var(--current-bg-primary) 100%);
    transition: var(--transition-theme);
}

/* ライトモード背景 */
body.light-theme .bg-gradient {
    background: linear-gradient(135deg,
        #ffffff 0%,
        #f0f9ff 25%,
        #e0e7ff 50%,
        #ddd6fe 75%,
        #fce7f3 100%);
}

.bg-particles {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(168, 85, 247, 0.06) 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(0, 212, 255, 0.04) 0%, transparent 50%);
    animation: bg-float 25s ease-in-out infinite;
}

body.light-theme .bg-particles {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(168, 85, 247, 0.04) 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(249, 115, 22, 0.03) 0%, transparent 50%);
}

.bg-mesh {
    background-image: 
        linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px),
        linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px);
    background-size: 80px 80px;
    animation: mesh-drift 40s linear infinite;
}

body.light-theme .bg-mesh {
    background-image: 
        linear-gradient(90deg, rgba(99, 102, 241, 0.02) 1px, transparent 1px),
        linear-gradient(rgba(99, 102, 241, 0.02) 1px, transparent 1px);
}

/* ライトモード専用装飾 */
.light-mode-shapes {
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0;
    transition: opacity var(--transition-theme);
}

body.light-theme .light-mode-shapes {
    opacity: 1;
}

.shape {
    position: absolute;
    opacity: 0.03;
}

.shape-circle {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, var(--accent-indigo), transparent);
    border-radius: 50%;
    top: -200px;
    right: -100px;
    animation: float-shape 20s ease-in-out infinite;
}

.shape-triangle {
    width: 0;
    height: 0;
    border-left: 150px solid transparent;
    border-right: 150px solid transparent;
    border-bottom: 260px solid rgba(168, 85, 247, 0.05);
    bottom: -130px;
    left: 10%;
    animation: float-shape 25s ease-in-out infinite reverse;
}

.shape-square {
    width: 200px;
    height: 200px;
    background: linear-gradient(45deg, rgba(249, 115, 22, 0.05), transparent);
    transform: rotate(45deg);
    top: 50%;
    left: -100px;
    animation: float-shape 30s ease-in-out infinite;
}

.floating-elements {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.float-element {
    position: absolute;
    width: 4px;
    height: 4px;
    background: var(--gradient-primary);
    border-radius: 50%;
    opacity: 0;
    animation: float-drift 30s ease-in-out infinite;
}

body.light-theme .float-element {
    background: var(--gradient-secondary);
    width: 3px;
    height: 3px;
}

.float-1 { top: 5%; left: 5%; animation-delay: 0s; }
.float-2 { top: 15%; right: 10%; animation-delay: 2.5s; }
.float-3 { top: 25%; left: 15%; animation-delay: 5s; }
.float-4 { top: 35%; right: 20%; animation-delay: 7.5s; }
.float-5 { top: 45%; left: 8%; animation-delay: 10s; }
.float-6 { top: 55%; right: 12%; animation-delay: 12.5s; }
.float-7 { top: 65%; left: 25%; animation-delay: 15s; }
.float-8 { top: 75%; right: 8%; animation-delay: 17.5s; }
.float-9 { top: 85%; left: 18%; animation-delay: 20s; }
.float-10 { bottom: 20%; right: 25%; animation-delay: 22.5s; }
.float-11 { bottom: 10%; left: 30%; animation-delay: 25s; }
.float-12 { bottom: 5%; right: 15%; animation-delay: 27.5s; }

/* === コンテナ === */
.container-main {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

/* === デスクトップレイアウト === */
.desktop-layout {
    display: none;
}

@media (min-width: 1024px) {
    .desktop-layout {
        display: block;
    }
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: var(--spacing-2xl);
    align-items: center;
    min-height: calc(100vh - var(--spacing-xl) * 2);
    padding: var(--spacing-xl) 0;
}

/* === メインコンテンツ === */
.content-main {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
    max-width: 600px;
}

/* === プレミアムバッジ === */
.premium-badge {
    position: relative;
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #1e293b, #334155);
    border-radius: 50px;
    padding: var(--spacing-sm) var(--spacing-lg);
    cursor: pointer;
    transition: var(--transition-base);
    overflow: hidden;
    width: fit-content;
}

body.light-theme .premium-badge {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
}

.badge-glow {
    position: absolute;
    inset: -2px;
    background: var(--gradient-primary);
    border-radius: 50px;
    opacity: 0;
    transition: var(--transition-base);
    z-index: -1;
}

.premium-badge:hover .badge-glow {
    opacity: 1;
    animation: glow-pulse 2s ease-in-out infinite;
}

.badge-content {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    position: relative;
    z-index: 1;
}

.status-dot {
    width: 6px;
    height: 6px;
    background: var(--accent-green);
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

.badge-text {
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

body.light-theme .badge-text {
    color: var(--accent-indigo);
}

.badge-pulse {
    position: absolute;
    inset: -10px;
    border: 2px solid rgba(99, 102, 241, 0.3);
    border-radius: 50px;
    animation: badge-pulse 3s ease-in-out infinite;
}

/* === メインタイトル === */
.main-title {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.title-line {
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.title-line-1 {
    font-size: 3.5rem;
    font-weight: 300;
    color: var(--current-text-secondary);
    opacity: 0;
    transform: translateY(30px);
    animation: fade-up 1s ease-out 0.2s forwards;
}

.title-line-2 {
    font-size: 4rem;
    font-weight: 800;
    opacity: 0;
    transform: translateY(30px);
    animation: fade-up 1s ease-out 0.4s forwards;
}

.ai-highlight {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
}

body.light-theme .ai-highlight {
    background: linear-gradient(135deg, var(--accent-indigo), var(--accent-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ai-highlight::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
    border-radius: 2px;
    transform: scaleX(0);
    animation: underline-expand 1s ease-out 1s forwards;
}

.title-line-3 {
    font-size: 3.5rem;
    font-weight: 300;
    color: var(--current-text-primary);
    opacity: 0;
    transform: translateY(30px);
    animation: fade-up 1s ease-out 0.6s forwards;
}

/* === 説明文 === */
.description {
    font-size: 1.125rem;
    line-height: 1.6;
    color: var(--current-text-tertiary);
    font-weight: 400;
    opacity: 0;
    transform: translateY(20px);
    animation: fade-up 1s ease-out 0.8s forwards;
}

/* === CTAボタン === */
.cta-container {
    display: flex;
    gap: var(--spacing-lg);
    align-items: center;
    flex-wrap: wrap;
    opacity: 0;
    transform: translateY(20px);
    animation: fade-up 1s ease-out 1.2s forwards;
}

.btn-primary,
.btn-secondary {
    position: relative;
    display: inline-flex;
    align-items: center;
    border: none;
    border-radius: var(--radius-lg);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-base);
    overflow: hidden;
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
    padding: var(--spacing-lg) var(--spacing-xl);
    box-shadow: var(--current-shadow-lg);
}

body.light-theme .btn-primary {
    box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: var(--current-shadow-xl);
}

body.light-theme .btn-primary:hover {
    box-shadow: 0 8px 20px 0 rgba(99, 102, 241, 0.4);
}

.btn-secondary {
    background: var(--current-surface);
    color: var(--current-text-primary);
    padding: var(--spacing-lg);
    border: 2px solid rgba(99, 102, 241, 0.2);
    backdrop-filter: blur(20px);
}

body.light-theme .btn-secondary {
    background: white;
    border-color: var(--accent-indigo);
    color: var(--accent-indigo);
}

.btn-secondary:hover {
    background: var(--current-surface-elevated);
    border-color: rgba(99, 102, 241, 0.4);
    transform: translateY(-2px);
}

body.light-theme .btn-secondary:hover {
    background: var(--light-bg-secondary);
    border-color: var(--accent-purple);
    color: var(--accent-purple);
}

.btn-content {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    position: relative;
    z-index: 2;
}

.btn-bg {
    position: absolute;
    inset: 0;
    background: var(--gradient-secondary);
    opacity: 0;
    transition: var(--transition-base);
}

.btn-primary:hover .btn-bg {
    opacity: 1;
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    z-index: 3;
    transition: var(--transition-base);
}

.btn-primary:hover .btn-shine {
    animation: btn-shine 0.6s ease-out;
}

.btn-arrow {
    transition: var(--transition-base);
}

.btn-primary:hover .btn-arrow,
.btn-secondary:hover .btn-arrow {
    transform: translateX(3px);
}

/* === マルチデバイスビジュアル === */
.visual-main {
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1500px;
}

.multidevice-system {
    position: relative;
    transform-style: preserve-3d;
    transition: var(--transition-slow);
    width: 100%;
    height: 500px;
}

.multidevice-system:hover {
    transform: rotateY(-3deg) rotateX(1deg);
}

/* === PCモニター（メイン） === */
.pc-monitor {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

.monitor-frame {
    position: relative;
    width: 480px;
    height: 300px;
    background: var(--monitor-bezel);
    border-radius: var(--radius-lg);
    padding: 12px 12px 35px 12px;
    box-shadow: var(--current-shadow-2xl);
    transform-style: preserve-3d;
    transition: var(--transition-theme);
}

body.light-theme .monitor-frame {
    background: white;
    border: 2px solid var(--light-bg-tertiary);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.monitor-bezel {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    color: #666;
    font-size: 0.7rem;
}

.monitor-brand {
    font-weight: 600;
    color: #999;
    letter-spacing: 1px;
}

body.light-theme .monitor-brand {
    color: var(--light-text-tertiary);
}

.power-indicator {
    width: 6px;
    height: 6px;
    background: var(--accent-green);
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

/* === PC画面 === */
.monitor-screen {
    position: relative;
    width: 100%;
    height: 100%;
    background: var(--dark-bg-primary);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.5);
    transition: var(--transition-theme);
}

body.light-theme .monitor-screen {
    background: white;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.screen-reflection {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 40%;
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.08) 0%, 
        rgba(255, 255, 255, 0.03) 50%, 
        transparent 100%);
    pointer-events: none;
    z-index: 10;
}

body.light-theme .screen-reflection {
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.5) 0%, 
        rgba(255, 255, 255, 0.2) 50%, 
        transparent 100%);
}

.screen-content {
    position: relative;
    width: 100%;
    height: 100%;
    color: var(--dark-text-primary);
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
    display: flex;
    flex-direction: column;
    z-index: 1;
}

body.light-theme .screen-content {
    color: var(--light-text-primary);
}

/* === システムヘッダー === */
.system-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-xs) var(--spacing-sm);
    background: rgba(30, 41, 59, 0.9);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: var(--transition-theme);
}

body.light-theme .system-header {
    background: var(--light-bg-secondary);
    border-bottom: 1px solid var(--light-bg-tertiary);
}

.window-controls {
    display: flex;
    gap: var(--spacing-xs);
}

.control-btn {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-base);
}

.control-btn.close { background: #ff5f57; }
.control-btn.minimize { background: #ffbd2e; }
.control-btn.maximize { background: #28ca42; }

.control-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 0 8px currentColor;
}

.system-title {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark-text-primary);
}

body.light-theme .system-title {
    color: var(--light-text-primary);
}

.system-status {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: 0.7rem;
    color: var(--dark-text-secondary);
}

body.light-theme .system-status {
    color: var(--light-text-secondary);
}

.status-dot.active {
    width: 5px;
    height: 5px;
    background: var(--accent-green);
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

/* === メインダッシュボード === */
.dashboard-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm);
    flex: 1;
}

/* === 統計パネル === */
.dashboard-left {
    display: flex;
    flex-direction: column;
}

.stats-panel {
    background: var(--dark-surface);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm);
    height: 100%;
    transition: var(--transition-theme);
}

body.light-theme .stats-panel {
    background: var(--light-bg-secondary);
    border: 1px solid var(--light-bg-tertiary);
}

.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
    padding-bottom: var(--spacing-xs);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-theme .panel-header {
    border-bottom: 1px solid var(--light-bg-tertiary);
}

.panel-header h3 {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark-text-primary);
    margin: 0;
}

body.light-theme .panel-header h3 {
    color: var(--light-text-primary);
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.live-dot {
    width: 5px;
    height: 5px;
    background: var(--accent-danger);
    border-radius: 50%;
    animation: pulse-red 1.5s ease-in-out infinite;
}

.live-indicator span {
    font-size: 0.6rem;
    font-weight: 700;
    color: var(--accent-danger);
    letter-spacing: 0.5px;
}

/* === 統計グリッド === */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xs);
    height: 100%;
}

.stat-card-screen {
    position: relative;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-xs);
    padding: var(--spacing-xs);
    cursor: pointer;
    transition: var(--transition-base);
    overflow: hidden;
}

body.light-theme .stat-card-screen {
    background: white;
    border: 1px solid var(--light-bg-tertiary);
}

.stat-card-screen:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent-cyan);
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.2);
}

body.light-theme .stat-card-screen:hover {
    background: var(--light-bg-secondary);
    border-color: var(--accent-indigo);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
}

.stat-icon-screen {
    font-size: 1rem;
    margin-bottom: var(--spacing-xs);
}

.stat-content-screen {
    margin-bottom: var(--spacing-xs);
}

.stat-number-screen {
    font-size: 0.85rem;
    font-weight: 800;
    color: var(--dark-text-primary);
    line-height: 1.2;
    margin-bottom: 2px;
}

body.light-theme .stat-number-screen {
    color: var(--light-text-primary);
}

.stat-label-screen {
    font-size: 0.6rem;
    color: var(--dark-text-secondary);
    font-weight: 500;
    line-height: 1.2;
}

body.light-theme .stat-label-screen {
    color: var(--light-text-secondary);
}

.stat-trend-screen {
    position: absolute;
    bottom: var(--spacing-xs);
    right: var(--spacing-xs);
    width: 30px;
    height: 12px;
}

.trend-line svg {
    width: 100%;
    height: 100%;
}

.trend-path {
    fill: none;
    stroke: var(--accent-green);
    stroke-width: 1.5;
    stroke-linecap: round;
    opacity: 0.8;
    animation: trend-draw 2s ease-in-out infinite;
}

body.light-theme .trend-path {
    stroke: var(--accent-indigo);
}

/* === 右パネル === */
.dashboard-right {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

/* === プログレスセクション === */
.progress-section {
    background: var(--dark-surface);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm);
    text-align: center;
    transition: var(--transition-theme);
}

body.light-theme .progress-section {
    background: var(--light-bg-secondary);
    border: 1px solid var(--light-bg-tertiary);
}

.progress-container-screen {
    display: flex;
    justify-content: center;
}

.progress-ring-screen {
    position: relative;
    width: 60px;
    height: 60px;
}

.progress-ring-screen svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.progress-bg-screen {
    fill: none;
    stroke: rgba(255, 255, 255, 0.1);
    stroke-width: 3;
}

body.light-theme .progress-bg-screen {
    stroke: var(--light-bg-tertiary);
}

.progress-fill-screen {
    fill: none;
    stroke: var(--accent-cyan);
    stroke-width: 3;
    stroke-linecap: round;
    stroke-dasharray: 314;
    stroke-dashoffset: 31.4;
    transition: stroke-dashoffset 2s ease-out;
    filter: drop-shadow(0 0 4px var(--accent-cyan));
}

body.light-theme .progress-fill-screen {
    stroke: var(--accent-indigo);
    filter: drop-shadow(0 0 4px var(--accent-indigo));
}

.progress-content-screen {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.progress-number-screen {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--dark-text-primary);
}

body.light-theme .progress-number-screen {
    color: var(--light-text-primary);
}

.progress-label-screen {
    font-size: 0.55rem;
    color: var(--dark-text-secondary);
    margin-top: 2px;
}

body.light-theme .progress-label-screen {
    color: var(--light-text-secondary);
}

/* === アクティビティフィード === */
.activity-feed {
    background: var(--dark-surface);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm);
    flex: 1;
    transition: var(--transition-theme);
}

body.light-theme .activity-feed {
    background: var(--light-bg-secondary);
    border: 1px solid var(--light-bg-tertiary);
}

.activity-header {
    margin-bottom: var(--spacing-xs);
    padding-bottom: var(--spacing-xs);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-theme .activity-header {
    border-bottom: 1px solid var(--light-bg-tertiary);
}

.activity-header h4 {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--dark-text-primary);
    margin: 0;
}

body.light-theme .activity-header h4 {
    color: var(--light-text-primary);
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs);
    border-radius: var(--radius-xs);
    transition: var(--transition-base);
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

body.light-theme .activity-item:hover {
    background: var(--light-bg-tertiary);
}

.activity-icon {
    font-size: 0.65rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.activity-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.activity-text span:first-child {
    font-size: 0.6rem;
    color: var(--dark-text-primary);
    line-height: 1.3;
}

body.light-theme .activity-text span:first-child {
    color: var(--light-text-primary);
}

.activity-time {
    font-size: 0.55rem;
    color: var(--dark-text-secondary);
    opacity: 0.8;
}

body.light-theme .activity-time {
    color: var(--light-text-secondary);
}

/* === システムフッター === */
.system-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-xs) var(--spacing-sm);
    background: rgba(30, 41, 59, 0.9);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.6rem;
    color: var(--dark-text-secondary);
    transition: var(--transition-theme);
}

body.light-theme .system-footer {
    background: var(--light-bg-secondary);
    border-top: 1px solid var(--light-bg-tertiary);
    color: var(--light-text-secondary);
}

.footer-info {
    display: flex;
    gap: var(--spacing-sm);
}

.footer-status {
    opacity: 0.8;
}

/* === モニタースタンド === */
.monitor-stand {
    position: absolute;
    bottom: -50px;
    left: 50%;
    transform: translateX(-50%);
    z-index: -1;
}

.stand-neck {
    width: 30px;
    height: 50px;
    background: linear-gradient(180deg, var(--monitor-bezel), #333);
    border-radius: 0 0 var(--radius-sm) var(--radius-sm);
    margin: 0 auto;
    box-shadow: var(--current-shadow-md);
    transition: var(--transition-theme);
}

body.light-theme .stand-neck {
    background: linear-gradient(180deg, var(--light-bg-tertiary), var(--light-text-tertiary));
}

.stand-base {
    width: 100px;
    height: 15px;
    background: linear-gradient(135deg, var(--monitor-bezel), #333);
    border-radius: var(--radius-lg);
    margin-top: -5px;
    box-shadow: var(--current-shadow-lg);
    transition: var(--transition-theme);
}

body.light-theme .stand-base {
    background: linear-gradient(135deg, var(--light-bg-tertiary), var(--light-text-tertiary));
}

/* === タブレット（重なり表示） === */
.tablet-device {
    position: absolute;
    top: 80px;
    right: -50px;
    z-index: 2;
    transform: rotate(5deg);
    transition: var(--transition-slow);
}

.multidevice-system:hover .tablet-device {
    transform: rotate(3deg) translateY(-5px);
}

.tablet-frame {
    width: 200px;
    height: 280px;
    background: var(--tablet-frame);
    border-radius: var(--radius-lg);
    padding: 20px 15px;
    box-shadow: var(--current-shadow-xl);
    position: relative;
    transition: var(--transition-theme);
}

body.light-theme .tablet-frame {
    background: white;
    border: 2px solid var(--light-bg-tertiary);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.tablet-screen {
    width: 100%;
    height: 100%;
    background: var(--dark-bg-secondary);
    border-radius: var(--radius-md);
    overflow: hidden;
    position: relative;
    transition: var(--transition-theme);
}

body.light-theme .tablet-screen {
    background: white;
}

.tablet-content {
    width: 100%;
    height: 100%;
    color: var(--dark-text-primary);
    display: flex;
    flex-direction: column;
}

body.light-theme .tablet-content {
    color: var(--light-text-primary);
}

/* === タブレットヘッダー === */
.tablet-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-xs) var(--spacing-sm);
    background: rgba(30, 41, 59, 0.8);
    font-size: 0.7rem;
    transition: var(--transition-theme);
}

body.light-theme .tablet-header {
    background: var(--light-bg-secondary);
}

.tablet-time {
    font-weight: 600;
    color: var(--dark-text-primary);
}

body.light-theme .tablet-time {
    color: var(--light-text-primary);
}

.tablet-status-icons {
    display: flex;
    gap: var(--spacing-xs);
    font-size: 0.6rem;
}

/* === タブレットアプリ === */
.tablet-app {
    flex: 1;
    padding: var(--spacing-sm);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.app-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-bottom: var(--spacing-sm);
}

.app-icon {
    font-size: 1rem;
}

.app-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark-text-primary);
}

body.light-theme .app-title {
    color: var(--light-text-primary);
}

/* === タブレット統計 === */
.tablet-stats {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.tablet-stat-card {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-xs);
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-sm);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: var(--transition-theme);
}

body.light-theme .tablet-stat-card {
    background: var(--light-bg-secondary);
    border: 1px solid var(--light-bg-tertiary);
}

.tablet-stat-icon {
    font-size: 0.9rem;
}

.tablet-stat-content {
    flex: 1;
}

.tablet-stat-number {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--dark-text-primary);
    margin-bottom: 2px;
}

body.light-theme .tablet-stat-number {
    color: var(--light-text-primary);
}

.tablet-stat-label {
    font-size: 0.6rem;
    color: var(--dark-text-secondary);
}

body.light-theme .tablet-stat-label {
    color: var(--light-text-secondary);
}

/* === タブレットチャート === */
.tablet-chart {
    margin-top: var(--spacing-sm);
}

.chart-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--dark-text-primary);
    margin-bottom: var(--spacing-xs);
}

body.light-theme .chart-title {
    color: var(--light-text-primary);
}

.chart-bars {
    display: flex;
    align-items: end;
    gap: 2px;
    height: 40px;
    padding: var(--spacing-xs);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-sm);
}

body.light-theme .chart-bars {
    background: var(--light-bg-tertiary);
}

.chart-bar {
    flex: 1;
    background: linear-gradient(to top, var(--accent-blue), var(--accent-cyan));
    border-radius: 1px;
    min-height: 10%;
    animation: chart-grow 2s ease-out infinite;
}

body.light-theme .chart-bar {
    background: linear-gradient(to top, var(--accent-indigo), var(--accent-purple));
}

/* === タブレットホームボタン === */
.tablet-home-btn {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 6px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

body.light-theme .tablet-home-btn {
    background: var(--light-text-tertiary);
}

/* === スマートフォン（重なり表示） === */
.smartphone-device {
    position: absolute;
    top: 120px;
    left: -30px;
    z-index: 1;
    transform: rotate(-8deg);
    transition: var(--transition-slow);
}

.multidevice-system:hover .smartphone-device {
    transform: rotate(-5deg) translateY(-3px);
}

.smartphone-frame {
    width: 120px;
    height: 220px;
    background: var(--smartphone-frame);
    border-radius: var(--radius-xl);
    padding: 15px 8px;
    box-shadow: var(--current-shadow-lg);
    position: relative;
    transition: var(--transition-theme);
}

body.light-theme .smartphone-frame {
    background: white;
    border: 2px solid var(--light-bg-tertiary);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.smartphone-screen {
    width: 100%;
    height: 100%;
    background: var(--dark-bg-primary);
    border-radius: var(--radius-lg);
    overflow: hidden;
    position: relative;
    transition: var(--transition-theme);
}

body.light-theme .smartphone-screen {
    background: white;
}

.smartphone-content {
    width: 100%;
    height: 100%;
    color: var(--dark-text-primary);
    display: flex;
    flex-direction: column;
}

body.light-theme .smartphone-content {
    color: var(--light-text-primary);
}

/* === スマートフォンステータスバー === */
.smartphone-statusbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-xs);
    background: rgba(30, 41, 59, 0.8);
    font-size: 0.6rem;
    transition: var(--transition-theme);
}

body.light-theme .smartphone-statusbar {
    background: var(--light-bg-secondary);
}

.statusbar-time {
    font-weight: 600;
    color: var(--dark-text-primary);
}

body.light-theme .statusbar-time {
    color: var(--light-text-primary);
}

.statusbar-icons {
    display: flex;
    gap: 2px;
    font-size: 0.5rem;
}

/* === スマートフォンアプリ === */
.smartphone-app {
    flex: 1;
    padding: var(--spacing-xs);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.smartphone-app-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-bottom: var(--spacing-xs);
}

.smartphone-app-icon {
    font-size: 0.8rem;
}

.smartphone-app-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--dark-text-primary);
}

body.light-theme .smartphone-app-title {
    color: var(--light-text-primary);
}

/* === スマートフォンクイック統計 === */
.smartphone-quick-stats {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.smartphone-stat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-xs);
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-sm);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: var(--transition-theme);
}

body.light-theme .smartphone-stat-item {
    background: var(--light-bg-secondary);
    border: 1px solid var(--light-bg-tertiary);
}

.smartphone-stat-number {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--dark-text-primary);
}

body.light-theme .smartphone-stat-number {
    color: var(--light-text-primary);
}

.smartphone-stat-label {
    font-size: 0.55rem;
    color: var(--dark-text-secondary);
}

body.light-theme .smartphone-stat-label {
    color: var(--light-text-secondary);
}

/* === スマートフォンアクション === */
.smartphone-action {
    margin-top: auto;
    padding: var(--spacing-xs);
}

.smartphone-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs);
    background: var(--gradient-primary);
    color: white;
    border-radius: var(--radius-sm);
    font-size: 0.65rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-base);
}

.smartphone-btn:hover {
    transform: scale(0.98);
    opacity: 0.9;
}

/* === デバイス反射効果 === */
.devices-reflection {
    position: absolute;
    bottom: -100px;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(180deg, 
        rgba(15, 23, 42, 0.1) 0%, 
        transparent 100%);
    border-radius: var(--radius-2xl);
    filter: blur(20px);
    opacity: 0.6;
    z-index: -3;
}

body.light-theme .devices-reflection {
    background: linear-gradient(180deg, 
        rgba(99, 102, 241, 0.05) 0%, 
        transparent 100%);
}

/* === モバイルレイアウト === */
.mobile-layout {
    display: block;
    padding: var(--spacing-xl) 0;
}

@media (min-width: 1024px) {
    .mobile-layout {
        display: none;
    }
}

.mobile-content {
    max-width: 480px;
    margin: 0 auto;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

/* === モバイルバッジ === */
.mobile-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    background: linear-gradient(135deg, #1e293b, #334155);
    color: white;
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: 25px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin: 0 auto;
    width: fit-content;
}

body.light-theme .mobile-badge {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: var(--accent-indigo);
}

.mobile-status-dot {
    width: 5px;
    height: 5px;
    background: var(--accent-green);
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

/* === モバイルタイトル === */
.mobile-title {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.mobile-title-1 {
    font-size: 1.75rem;
    font-weight: 300;
    color: var(--current-text-secondary);
    line-height: 1.2;
}

.mobile-title-2 {
    font-size: 2.25rem;
    font-weight: 800;
    line-height: 1.2;
}

.mobile-ai-highlight {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* === モバイル説明 === */
.mobile-description {
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--current-text-tertiary);
    font-weight: 400;
}

/* === モバイル統計 === */
.mobile-stats {
    background: var(--current-surface-elevated);
    backdrop-filter: blur(30px);
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--current-shadow-md);
    padding: var(--spacing-md);
}

body.light-theme .mobile-stats {
    background: white;
    border: 1px solid var(--light-bg-tertiary);
}

.mobile-stats-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
}

.mobile-stats-header h3 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--current-text-primary);
    margin: 0;
}

.mobile-live-indicator {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.mobile-live-dot {
    width: 5px;
    height: 5px;
    background: var(--accent-danger);
    border-radius: 50%;
    animation: pulse-red 1.5s ease-in-out infinite;
}

.mobile-live-indicator span {
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--accent-danger);
    letter-spacing: 0.5px;
}

.mobile-stats-scroll {
    display: flex;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    gap: var(--spacing-sm);
    padding-bottom: var(--spacing-xs);
    scroll-snap-type: x mandatory;
}

.mobile-stats-scroll::-webkit-scrollbar {
    height: 3px;
}

.mobile-stats-scroll::-webkit-scrollbar-track {
    background: rgba(99, 102, 241, 0.1);
    border-radius: 1.5px;
}

body.light-theme .mobile-stats-scroll::-webkit-scrollbar-track {
    background: var(--light-bg-tertiary);
}

.mobile-stats-scroll::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.4);
    border-radius: 1.5px;
}

body.light-theme .mobile-stats-scroll::-webkit-scrollbar-thumb {
    background: var(--accent-indigo);
}

.mobile-stat-card {
    min-width: 90px;
    background: var(--current-surface);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm);
    text-align: center;
    transition: var(--transition-base);
    scroll-snap-align: start;
    flex-shrink: 0;
}

body.light-theme .mobile-stat-card {
    background: var(--light-bg-secondary);
    border: 1px solid var(--light-bg-tertiary);
}

.mobile-stat-card:hover {
    transform: translateY(-1px);
    box-shadow: var(--current-shadow-sm);
    background: var(--current-surface-elevated);
}

.mobile-stat-icon {
    font-size: 1rem;
    margin-bottom: var(--spacing-xs);
}

.mobile-stat-number {
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--current-text-primary);
    margin-bottom: 2px;
}

.mobile-stat-label {
    font-size: 0.6rem;
    color: var(--current-text-tertiary);
    font-weight: 500;
}

/* === モバイルCTA === */
.mobile-cta {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.mobile-btn-primary,
.mobile-btn-secondary {
    width: 100%;
    border: none;
    border-radius: var(--radius-md);
    padding: var(--spacing-lg);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-base);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
}

.mobile-btn-primary {
    background: var(--gradient-primary);
    color: white;
    box-shadow: var(--current-shadow-md);
}

body.light-theme .mobile-btn-primary {
    box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.3);
}

.mobile-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--current-shadow-lg);
}

.mobile-btn-secondary {
    background: var(--current-surface);
    color: var(--current-text-primary);
    border: 2px solid rgba(99, 102, 241, 0.2);
    backdrop-filter: blur(20px);
}

body.light-theme .mobile-btn-secondary {
    background: white;
    border-color: var(--accent-indigo);
    color: var(--accent-indigo);
}

/* === アニメーション === */
@keyframes bg-float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(2deg); }
}

@keyframes mesh-drift {
    from { transform: translateX(0) translateY(0); }
    to { transform: translateX(80px) translateY(80px); }
}

@keyframes float-drift {
    0%, 100% { opacity: 0; transform: translateY(0) scale(0.5); }
    50% { opacity: 0.9; transform: translateY(-50px) scale(1.3); }
}

@keyframes float-shape {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes glow-pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.2); }
}

@keyframes pulse-red {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.3); }
}

@keyframes badge-pulse {
    0%, 100% { opacity: 0; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(1.05); }
}

@keyframes fade-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes underline-expand {
    from { transform: scaleX(0); }
    to { transform: scaleX(1); }
}

@keyframes btn-shine {
    0% { left: -100%; }
    100% { left: 100%; }
}

@keyframes trend-draw {
    0%, 100% { stroke-dasharray: 0 100; }
    50% { stroke-dasharray: 50 50; }
}

@keyframes chart-grow {
    0%, 100% { transform: scaleY(0.8); }
    50% { transform: scaleY(1.2); }
}

/* === レスポンシブ調整 === */
@media (max-width: 1200px) {
    .monitor-frame {
        width: 400px;
        height: 250px;
    }
    
    .tablet-device {
        right: -40px;
    }
    
    .smartphone-device {
        left: -25px;
    }
    
    .title-line-1,
    .title-line-3 {
        font-size: 2.5rem;
    }
    
    .title-line-2 {
        font-size: 3rem;
    }
}

@media (max-width: 768px) {
    .container-main {
        padding: 0 var(--spacing-md);
    }
    
    .mobile-title-1 {
        font-size: 1.5rem;
    }
    
    .mobile-title-2 {
        font-size: 1.875rem;
    }
}

@media (max-width: 640px) {
    .mobile-stat-card {
        min-width: 80px;
        padding: var(--spacing-xs);
    }
    
    .mobile-stat-number {
        font-size: 0.75rem;
    }
    
    .mobile-stat-label {
        font-size: 0.55rem;
    }
    
    .theme-toggle-container {
        top: 15px;
        right: 15px;
    }
    
    .theme-toggle-btn {
        width: 56px;
        height: 28px;
    }
}

/* === アクセシビリティ === */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* === フォーカス管理 === */
button:focus,
a:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
}

body.light-theme button:focus,
body.light-theme a:focus {
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.8);
}

/* === プリント対応 === */
@media print {
    .hero-dual-theme {
        background: white !important;
        color: black !important;
    }
    
    .floating-elements,
    .light-mode-shapes,
    .theme-toggle-container {
        display: none !important;
    }
}

/* === 高パフォーマンス設定 === */
.multidevice-system,
.monitor-screen,
.tablet-screen,
.smartphone-screen {
    transform: translateZ(0);
    will-change: transform;
}

.stat-card-screen,
.mobile-stat-card,
.tablet-stat-card {
    transform: translateZ(0);
    will-change: transform, background-color;
}
</style>

<script>
/**
 * ダーク/ライトモード完全対応 補助金・助成金サイト JavaScript システム
 */
class GrantHeroDualThemeSystem {
    constructor() {
        this.currentTheme = localStorage.getItem('grant-hero-theme') || 'dark';
        this.init();
    }
    
    init() {
        this.setupThemeSystem();
        this.setupAnimations();
        this.setupInteractions();
        this.setupCounters();
        this.setupProgressRing();
        this.setupMultideviceEffects();
        this.setupAccessibility();
        this.setupPerformanceMonitoring();
    }
    
    setupThemeSystem() {
        // 初期テーマの適用
        this.applyTheme(this.currentTheme);
        
        // テーマ切り替えボタンの設定
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', this.toggleTheme.bind(this));
        }
        
        // システム設定の検出
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: light)');
            
            // 初回のシステム設定チェック
            if (!localStorage.getItem('grant-hero-theme')) {
                this.currentTheme = mediaQuery.matches ? 'light' : 'dark';
                this.applyTheme(this.currentTheme);
            }
            
            // システム設定変更の監視
            mediaQuery.addEventListener('change', this.handleSystemThemeChange.bind(this));
        }
    }
    
    toggleTheme() {
        const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.applyTheme(newTheme);
        
        // スムーズなトランジション
        this.animateThemeTransition();
        
        // 通知表示
        this.showSystemNotification(
            '🎨 テーマ変更', 
            `${newTheme === 'dark' ? 'ダークモード' : 'ライトモード'}に切り替えました`, 
            'success'
        );
    }
    
    applyTheme(theme) {
        this.currentTheme = theme;
        
        if (theme === 'light') {
            document.body.classList.add('light-theme');
        } else {
            document.body.classList.remove('light-theme');
        }
        
        // ローカルストレージに保存
        localStorage.setItem('grant-hero-theme', theme);
        
        // カスタムイベントの発火
        document.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
    }
    
    animateThemeTransition() {
        // 全要素にトランジションを追加
        const elements = document.querySelectorAll('*');
        elements.forEach(el => {
            el.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        // トランジション後にリセット
        setTimeout(() => {
            elements.forEach(el => {
                el.style.transition = '';
            });
        }, 300);
    }
    
    handleSystemThemeChange(e) {
        // ユーザーが手動で設定していない場合のみ自動切り替え
        const hasUserPreference = localStorage.getItem('grant-hero-theme-manual');
        if (!hasUserPreference) {
            const preferredTheme = e.matches ? 'light' : 'dark';
            this.applyTheme(preferredTheme);
        }
    }
    
    setupAnimations() {
        // フローティングエレメントの初期化
        const floatingElements = document.querySelectorAll('.float-element');
        floatingElements.forEach((element, index) => {
            element.style.animationDelay = `${index * 2.5}s`;
        });
        
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    
                    // 統計カードのスタガードアニメーション
                    if (entry.target.classList.contains('stats-grid')) {
                        const cards = entry.target.querySelectorAll('.stat-card-screen');
                        cards.forEach((card, index) => {
                            setTimeout(() => {
                                card.style.opacity = '0';
                                card.style.transform = 'translateY(20px)';
                                card.style.transition = 'all 0.6s ease-out';
                                
                                setTimeout(() => {
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, 50);
                            }, index * 120);
                        });
                    }
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.stats-grid, .activity-list').forEach(el => {
            observer.observe(el);
        });
    }
    
    setupInteractions() {
        // ボタンホバーエフェクト
        const primaryBtns = document.querySelectorAll('.btn-primary, .mobile-btn-primary');
        primaryBtns.forEach(btn => {
            btn.addEventListener('mouseenter', this.handlePrimaryHover.bind(this));
            btn.addEventListener('mouseleave', this.handlePrimaryLeave.bind(this));
        });
        
        // 統計カードインタラクション
        const statCards = document.querySelectorAll('.stat-card-screen, .mobile-stat-card, .tablet-stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', this.handleStatHover.bind(this));
            card.addEventListener('mouseleave', this.handleStatLeave.bind(this));
        });
        
        // ウィンドウコントロールボタン
        const controlBtns = document.querySelectorAll('.control-btn');
        controlBtns.forEach(btn => {
            btn.addEventListener('click', this.handleControlClick.bind(this));
        });
        
        // デバイスクリックインタラクション
        this.setupDeviceInteractions();
    }
    
    setupDeviceInteractions() {
        // タブレットクリック
        const tabletDevice = document.querySelector('.tablet-device');
        if (tabletDevice) {
            tabletDevice.addEventListener('click', () => {
                this.showSystemNotification('📱 タブレット', 'タブレット版アプリケーションを表示中', 'info');
                this.animateDevice(tabletDevice);
            });
        }
        
        // スマートフォンクリック
        const smartphoneDevice = document.querySelector('.smartphone-device');
        if (smartphoneDevice) {
            smartphoneDevice.addEventListener('click', () => {
                this.showSystemNotification('📱 スマートフォン', 'モバイル版アプリケーションを表示中', 'info');
                this.animateDevice(smartphoneDevice);
            });
        }
    }
    
    animateDevice(device) {
        device.style.transform += ' scale(1.05)';
        setTimeout(() => {
            device.style.transform = device.style.transform.replace(' scale(1.05)', '');
        }, 200);
    }
    
    setupCounters() {
        const counters = document.querySelectorAll('[data-target]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => observer.observe(counter));
    }
    
    setupProgressRing() {
        const progressRing = document.querySelector('.progress-fill-screen');
        if (progressRing) {
            const progress = 98.7;
            const circumference = 2 * Math.PI * 50;
            const offset = circumference - (progress / 100) * circumference;
            
            progressRing.style.strokeDasharray = circumference;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            progressRing.style.strokeDashoffset = offset;
                        }, 1200);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(progressRing.closest('.progress-section'));
        }
    }
    
    setupMultideviceEffects() {
        const multideviceSystem = document.querySelector('.multidevice-system');
        if (multideviceSystem) {
            // マウス追従3Dエフェクト
            multideviceSystem.addEventListener('mousemove', (e) => {
                const rect = multideviceSystem.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / centerY * 3;
                const rotateY = (x - centerX) / centerX * -3;
                
                multideviceSystem.style.transform = `perspective(1500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                
                // 個別デバイスの微調整
                const tablet = multideviceSystem.querySelector('.tablet-device');
                const smartphone = multideviceSystem.querySelector('.smartphone-device');
                
                if (tablet) {
                    tablet.style.transform = `rotate(${5 + rotateY * 0.5}deg) translateY(${-rotateX}px)`;
                }
                
                if (smartphone) {
                    smartphone.style.transform = `rotate(${-8 + rotateY * 0.3}deg) translateY(${-rotateX * 0.5}px)`;
                }
            });
            
            multideviceSystem.addEventListener('mouseleave', () => {
                multideviceSystem.style.transform = 'perspective(1500px) rotateX(0deg) rotateY(0deg)';
                
                const tablet = multideviceSystem.querySelector('.tablet-device');
                const smartphone = multideviceSystem.querySelector('.smartphone-device');
                
                if (tablet) {
                    tablet.style.transform = 'rotate(5deg)';
                }
                
                if (smartphone) {
                    smartphone.style.transform = 'rotate(-8deg)';
                }
            });
            
            // 画面の光る効果（テーマ対応）
            const screens = [
                multideviceSystem.querySelector('.screen-content'),
                multideviceSystem.querySelector('.tablet-screen'),
                multideviceSystem.querySelector('.smartphone-screen')
            ];
            
            setInterval(() => {
                screens.forEach(screen => {
                    if (screen) {
                        const glowColor = this.currentTheme === 'dark' 
                            ? 'rgba(0, 212, 255, 0.1)' 
                            : 'rgba(99, 102, 241, 0.1)';
                        screen.style.boxShadow = `0 0 30px ${glowColor}`;
                        setTimeout(() => {
                            const normalGlow = this.currentTheme === 'dark' 
                                ? 'rgba(0, 212, 255, 0.05)' 
                                : 'rgba(99, 102, 241, 0.05)';
                            screen.style.boxShadow = `0 0 20px ${normalGlow}`;
                        }, 1000);
                    }
                });
            }, 4000);
        }
    }
    
    setupAccessibility() {
        // キーボードナビゲーション
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
            
            // テーマ切り替えショートカット（Ctrl + Shift + T）
            if (e.ctrlKey && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                this.toggleTheme();
            }
        });
        
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
    }
    
    setupPerformanceMonitoring() {
        // パフォーマンス監視
        if ('performance' in window && 'PerformanceObserver' in window) {
            try {
                const observer = new PerformanceObserver((list) => {
                    list.getEntries().forEach((entry) => {
                        if (entry.entryType === 'measure' && entry.duration > 100) {
                            console.log(`Performance: ${entry.name} took ${entry.duration.toFixed(2)}ms`);
                        }
                    });
                });
                observer.observe({ entryTypes: ['measure'] });
            } catch (e) {
                console.warn('PerformanceObserver not supported');
            }
        }
        
        // リアルタイムシステム統計の更新
        this.updateSystemStats();
    }
    
    updateSystemStats() {
        const footerStatus = document.querySelector('.footer-status');
        if (footerStatus) {
            setInterval(() => {
                const cpu = Math.floor(Math.random() * 25) + 15;
                const ram = Math.floor(Math.random() * 20) + 40;
                const network = Math.floor(Math.random() * 50) + 50;
                footerStatus.textContent = `システム正常稼働中 | CPU: ${cpu}% | RAM: ${ram}% | NET: ${network}Mbps`;
            }, 6000);
        }
        
        // タブレットの時刻更新
        const tabletTime = document.querySelector('.tablet-time');
        const smartphoneTime = document.querySelector('.statusbar-time');
        
        if (tabletTime || smartphoneTime) {
            setInterval(() => {
                const now = new Date();
                const timeString = now.toLocaleTimeString('ja-JP', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: false 
                });
                
                if (tabletTime) tabletTime.textContent = timeString;
                if (smartphoneTime) smartphoneTime.textContent = timeString;
            }, 1000);
        }
    }
    
    animateCounter(element) {
        const target = element.getAttribute('data-target');
        const original = element.getAttribute('data-original') || target;
        
        // 数値部分のみを抽出
        const numericMatch = target.match(/[\d.]+/);
        if (!numericMatch) {
            element.textContent = original;
            return;
        }
        
        const numericTarget = parseFloat(numericMatch[0]);
        
        // NaN チェック
        if (!isFinite(numericTarget) || isNaN(numericTarget)) {
            element.textContent = original;
            return;
        }
        
        let current = 0;
        const increment = numericTarget / 120;
        const duration = 2500;
        const stepTime = duration / 120;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= numericTarget) {
                current = numericTarget;
                clearInterval(timer);
            }
            
            try {
                if (target.includes('%')) {
                    element.textContent = current.toFixed(1) + '%';
                } else if (target.includes('時間')) {
                    element.textContent = Math.floor(current) + '時間';
                } else if (target.includes(',')) {
                    element.textContent = Math.floor(current).toLocaleString();
                } else {
                    element.textContent = Math.floor(current);
                }
            } catch (e) {
                element.textContent = original;
                clearInterval(timer);
            }
        }, stepTime);
    }
    
    handlePrimaryHover(e) {
        const btn = e.currentTarget;
        const shine = btn.querySelector('.btn-shine');
        if (shine) {
            shine.style.animation = 'btn-shine 0.8s ease-out';
        }
    }
    
    handlePrimaryLeave(e) {
        const btn = e.currentTarget;
        const shine = btn.querySelector('.btn-shine');
        if (shine) {
            shine.style.animation = '';
        }
    }
    
    handleStatHover(e) {
        const card = e.currentTarget;
        card.style.transform = 'translateY(-3px) scale(1.02)';
        
        // トレンドラインのアニメーション強化
        const trendPath = card.querySelector('.trend-path');
        if (trendPath) {
            const accentColor = this.currentTheme === 'dark' ? '#00d4ff' : '#6366f1';
            trendPath.style.stroke = accentColor;
            trendPath.style.filter = `drop-shadow(0 0 4px ${accentColor})`;
        }
    }
    
    handleStatLeave(e) {
        const card = e.currentTarget;
        card.style.transform = '';
        
        // トレンドラインを元に戻す
        const trendPath = card.querySelector('.trend-path');
        if (trendPath) {
            trendPath.style.stroke = '';
            trendPath.style.filter = '';
        }
    }
    
    handleControlClick(e) {
        const btn = e.currentTarget;
        
        // 視覚的フィードバック
        btn.style.transform = 'scale(0.9)';
        setTimeout(() => {
            btn.style.transform = '';
        }, 150);
        
        // コントロール別の動作
        if (btn.classList.contains('close')) {
            this.showSystemNotification('❌ システム終了', 'アプリケーションを終了しますか？', 'warning');
        } else if (btn.classList.contains('minimize')) {
            this.showSystemNotification('➖ 最小化', 'アプリケーションを最小化しました', 'info');
        } else if (btn.classList.contains('maximize')) {
            this.showSystemNotification('⬜ 最大化', 'アプリケーションを最大化しました', 'success');
        }
    }
    
    showSystemNotification(title, message, type) {
        // 既存の通知を削除
        const existingNotifications = document.querySelectorAll('.system-notification');
        existingNotifications.forEach(notification => {
            notification.remove();
        });
        
        // システム風の通知を表示（テーマ対応）
        const notification = document.createElement('div');
        notification.className = 'system-notification';
        notification.innerHTML = `
            <div class="notification-header">
                <span class="notification-icon">${type === 'success' ? '✅' : type === 'warning' ? '⚠️' : type === 'info' ? 'ℹ️' : '🤖'}</span>
                <span class="notification-title">${title}</span>
            </div>
            <div class="notification-message">${message}</div>
        `;
        
        // テーマに応じた通知スタイル
        const isDark = this.currentTheme === 'dark';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)'};
            backdrop-filter: blur(20px);
            border: 1px solid ${isDark ? 'rgba(0, 212, 255, 0.3)' : 'rgba(99, 102, 241, 0.3)'};
            border-radius: 12px;
            padding: 16px;
            color: ${isDark ? '#f8fafc' : '#0f172a'};
            font-family: 'SF Mono', monospace;
            font-size: 0.875rem;
            max-width: 320px;
            z-index: 10000;
            box-shadow: ${isDark ? '0 8px 32px rgba(0, 0, 0, 0.4)' : '0 8px 32px rgba(0, 0, 0, 0.15)'};
            transform: translateX(100%);
            transition: transform 0.3s ease-out;
        `;
        
        document.body.appendChild(notification);
        
        // アニメーション
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // 自動削除
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
}

// グローバル関数
function startGrantSearch() {
    console.log('助成金検索を開始します');
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'click', {
            event_category: 'CTA',
            event_label: 'Start Grant Search'
        });
    }
    
    // 視覚的フィードバック
    const button = event?.target?.closest('button');
    if (button) {
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = '';
        }, 150);
    }
    
    // システム風の通知
    const system = window.grantHeroDualSystem;
    if (system && system.showSystemNotification) {
        system.showSystemNotification('🔍 助成金検索開始', 'AI が12,847件のデータベースから最適な助成金を検索中...', 'success');
    } else {
        alert('🔍 助成金検索システムを開始します。\n\nあなたのビジネスに最適な補助金・助成金をAIが瞬時に発見いたします。\n\n✅ 12,847件のデータベースから検索\n✅ 98.7%の高精度マッチング\n✅ 専門家による申請サポート\n✅ 完全無料でご利用可能');
    }
}

function openAIConsultation() {
    console.log('AI相談を開始します');
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'click', {
            event_category: 'CTA',
            event_label: 'Open AI Consultation'
        });
    }
    
    // システム風の通知
    const system = window.grantHeroDualSystem;
    if (system && system.showSystemNotification) {
        system.showSystemNotification('🤖 AI専門家相談', 'AI専門家が最適な助成金・補助金をご提案いたします', 'info');
    } else {
        alert('🤖 AI専門家による無料相談を開始します。\n\n以下のサポートをご提供いたします：\n\n• 最適な助成金・補助金の提案\n• 申請要件の詳細説明\n• 必要書類のチェックリスト\n• 申請スケジュールの策定\n• 成功率向上のアドバイス\n\n専門スタッフが迅速にサポートいたします。');
    }
}

// 初期化
document.addEventListener('DOMContentLoaded', () => {
    try {
        window.grantHeroDualSystem = new GrantHeroDualThemeSystem();
        
        console.log('🎨 Grant Hero Dual Theme System initialized successfully');
    } catch (error) {
        console.error('❌ Initialization error:', error);
    }
});

// エラーハンドリング
window.addEventListener('error', (e) => {
    console.error('❌ JavaScript Error:', {
        message: e.error?.message || e.message,
        filename: e.filename,
        lineno: e.lineno,
        colno: e.colno
    });
});

window.addEventListener('unhandledrejection', (e) => {
    console.error('❌ Unhandled Promise Rejection:', e.reason);
    e.preventDefault();
});
</script>

<!-- 追加CSS（通知システム用） -->
<style>
.notification-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 600;
}

.notification-message {
    font-size: 0.8rem;
    opacity: 0.9;
    line-height: 1.4;
}

.system-notification {
    animation: notification-slide 0.3s ease-out;
}

@keyframes notification-slide {
    from {
        transform: translateX(
	        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

/* キーボードナビゲーション時のフォーカススタイル */
body.keyboard-navigation button:focus,
body.keyboard-navigation a:focus {
    outline: 2px solid var(--accent-indigo);
    outline-offset: 2px;
}
</style>		