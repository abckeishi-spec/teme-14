/**
 * 統合検索システム共通設定
 * Grant Insight Perfect - 統合検索システム究極版
 * 
 * @version 1.0.0-unified
 * @package Grant_Insight_Perfect
 */
window.GISearchConfig = {
    // 統一DOM要素ID
    elements: {
        // 検索入力（優先度順）
        searchInputs: [
            'gi-search-input-unified',  // 新統一ID
            'grant-search',             // archive用（既存）
            'search-keyword-input',     // section用（既存）
            'search-keyword'            // header用（既存）
        ],
        
        // 検索ボタン
        searchButtons: [
            'gi-search-btn-unified',    // 新統一ID
            'search-btn',               // archive用
            'search-submit-btn',        // section用
            'execute-search'            // header用
        ],
        
        // 結果表示
        resultsContainers: [
            'gi-results-unified',       // 新統一ID
            'grants-display',           // archive用
            'search-results-preview'    // section用
        ],
        
        // フィルター
        filters: {
            category: ['filter-category', 'gi-filter-category'],
            prefecture: ['filter-prefecture', 'gi-filter-prefecture'],
            industry: ['filter-industry', 'gi-filter-industry'],
            amount: ['filter-amount', 'gi-filter-amount'],
            status: ['filter-status', 'gi-filter-status'],
            difficulty: ['filter-difficulty', 'gi-filter-difficulty'],
            success_rate: ['filter-success-rate', 'gi-filter-success-rate']
        },
        
        // その他の要素
        loadingIndicator: ['gi-loading', 'search-loading'],
        errorContainer: ['gi-error', 'search-error'],
        suggestionContainer: ['gi-suggestions', 'search-suggestions', 'gi-suggestions-unified'],
        voiceButton: ['gi-voice-btn', 'voice-search-btn', 'voice-search'],
        clearButton: ['gi-clear-btn', 'search-clear'],
        
        // ページネーション
        pagination: ['gi-pagination', 'search-pagination'],
        
        // フィルターパネル
        filterPanel: ['gi-filter-panel', 'advanced-filters'],
        filterToggle: ['gi-filter-toggle', 'filter-toggle-btn']
    },
    
    // AJAX設定
    ajax: {
        action: 'gi_unified_search',
        timeout: 30000,
        retryLimit: 3,
        retryDelay: 1000
    },
    
    // 統一CSS classes
    classes: {
        // 検索関連
        searchInput: 'gi-search-input-unified',
        searchButton: 'gi-search-button-unified',
        searchContainer: 'gi-search-container-unified',
        
        // 状態管理
        loadingState: 'gi-loading',
        errorState: 'gi-error',
        successState: 'gi-success',
        emptyState: 'gi-empty',
        
        // 結果表示
        grantCard: 'gi-grant-card-unified',
        grantGrid: 'gi-grant-grid-unified',
        grantList: 'gi-grant-list-unified',
        
        // フィルター
        filterActive: 'gi-filter-active',
        filterGroup: 'gi-filter-group',
        
        // アニメーション
        fadeIn: 'gi-fade-in',
        slideUp: 'gi-slide-up',
        pulse: 'gi-pulse',
        
        // 通知
        notification: 'gi-notification',
        notificationSuccess: 'gi-notification-success',
        notificationError: 'gi-notification-error',
        notificationWarning: 'gi-notification-warning',
        notificationInfo: 'gi-notification-info'
    },
    
    // デフォルト設定
    defaults: {
        // 検索パラメータ
        searchParams: {
            search: '',
            categories: [],
            prefectures: [],
            industries: [],
            amount: '',
            status: [],
            difficulty: [],
            success_rate: [],
            page: 1,
            posts_per_page: 12,
            orderby: 'date',
            order: 'DESC'
        },
        
        // 表示設定
        display: {
            postsPerPage: 12,
            maxSuggestions: 10,
            animationDelay: 100,
            notificationDuration: 5000
        },
        
        // 音声検索設定
        voice: {
            lang: 'ja-JP',
            continuous: false,
            interimResults: false,
            maxAlternatives: 1
        },
        
        // キャッシュ設定
        cache: {
            enabled: true,
            ttl: 300000, // 5分
            maxSize: 50
        }
    },
    
    // 統合フラグ（システム統合状態の追跡）
    integration: {
        initialized: false,
        legacySystems: {
            archiveManager: false,
            searchSection: false,
            headerSearch: false
        },
        bridgeLoaded: false,
        configLoaded: true
    },
    
    // デバッグ設定
    debug: {
        enabled: typeof window.gi_ajax !== 'undefined' && window.gi_ajax.debug,
        logLevel: 'info', // 'debug', 'info', 'warn', 'error'
        logPrefix: '[GI-Search]'
    },
    
    // 互換性設定
    compatibility: {
        // レガシーイベント名のマッピング
        eventMapping: {
            'search-execute': 'gi:search:execute',
            'search-complete': 'gi:search:complete',
            'search-error': 'gi:search:error',
            'filter-change': 'gi:filter:change',
            'results-update': 'gi:results:update'
        },
        
        // レガシー関数名のマッピング
        functionMapping: {
            'executeSearch': 'executeUnifiedSearch',
            'displayResults': 'renderResults',
            'showError': 'handleError',
            'clearSearch': 'resetSearch'
        }
    },
    
    // メッセージ定数
    messages: {
        loading: '検索中...',
        noResults: '該当する助成金が見つかりませんでした',
        error: 'エラーが発生しました',
        networkError: 'ネットワークエラーが発生しました',
        timeout: 'タイムアウトしました',
        voiceNotSupported: 'お使いのブラウザは音声検索に対応していません',
        voiceListening: '音声を認識中です...',
        voiceError: '音声認識に失敗しました',
        favoriteAdded: 'お気に入りに追加しました',
        favoriteRemoved: 'お気に入りから削除しました',
        searchReset: '検索条件をリセットしました',
        filterApplied: 'フィルターを適用しました'
    },
    
    // API エンドポイント
    endpoints: {
        search: 'gi_unified_search',
        suggestions: 'gi_get_search_suggestions',
        favorite: 'gi_toggle_favorite',
        stats: 'gi_get_search_stats'
    }
};

// Phase 5: 音声検索・サジェスト機能のCSS（動的注入）
window.GISearchConfig.injectPhase5Styles = function() {
    if (document.getElementById('gi-phase5-styles')) return; // 既に注入済み
    
    const css = `
    /* Phase 5: 音声検索機能スタイル */
    .gi-voice-btn {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-left: 8px;
        box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
    }
    
    .gi-voice-btn:hover {
        background: linear-gradient(135deg, #047857, #059669);
        box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        transform: translateY(-1px);
    }
    
    .gi-voice-btn.gi-voice-recording {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        animation: gi-voice-pulse 1s infinite;
    }
    
    @keyframes gi-voice-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .gi-voice-input-active {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
    }
    
    /* Phase 5: サジェスト機能スタイル */
    .gi-suggestion-container {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        margin-top: 4px;
    }
    
    .gi-suggestion-container.hidden {
        display: none;
    }
    
    .gi-suggestion-container.gi-suggestion-active {
        display: block;
        animation: gi-suggestion-fadeIn 0.2s ease-out;
    }
    
    @keyframes gi-suggestion-fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .gi-suggestion-header {
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .gi-suggestion-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .gi-suggestion-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .gi-suggestion-item:hover,
    .gi-suggestion-item.gi-suggestion-selected {
        background-color: #f0f9ff;
        color: #0369a1;
    }
    
    .gi-suggestion-item:last-child {
        border-bottom: none;
    }
    
    .gi-suggestion-item i {
        color: #9ca3af;
        margin-right: 8px;
        width: 14px;
        font-size: 12px;
    }
    
    .gi-suggestion-item:hover i,
    .gi-suggestion-item.gi-suggestion-selected i {
        color: #0369a1;
    }
    
    .suggestion-text {
        flex: 1;
        font-size: 14px;
    }
    
    .suggestion-text mark {
        background: #fef3c7;
        color: #92400e;
        padding: 1px 2px;
        border-radius: 2px;
    }
    
    .suggestion-count {
        font-size: 12px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 8px;
    }
    
    /* 検索入力エリアの相対位置設定 */
    .search-form,
    .gi-search-container,
    .search-input-container {
        position: relative;
    }
    
    /* レスポンシブ対応 */
    @media (max-width: 640px) {
        .gi-suggestion-container {
            left: -10px;
            right: -10px;
            margin-top: 2px;
        }
        
        .gi-voice-btn {
            width: 36px;
            height: 36px;
            margin-left: 6px;
        }
        
        .gi-suggestion-item {
            padding: 12px 16px;
        }
    }
    `;
    
    const style = document.createElement('style');
    style.id = 'gi-phase5-styles';
    style.textContent = css;
    document.head.appendChild(style);
    
    console.log('✨ Phase 5 スタイル注入完了');
};

// ページ読み込み時に自動でスタイル注入
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', window.GISearchConfig.injectPhase5Styles);
} else {
    window.GISearchConfig.injectPhase5Styles();
}

// 設定の凍結（変更防止）
if (typeof Object.freeze === 'function') {
    Object.freeze(window.GISearchConfig);
}

// 初期化完了イベントの発火
document.addEventListener('DOMContentLoaded', function() {
    // カスタムイベントで設定読み込み完了を通知
    const configLoadedEvent = new CustomEvent('gi:config:loaded', {
        detail: window.GISearchConfig
    });
    document.dispatchEvent(configLoadedEvent);
    
    // デバッグログ
    if (window.GISearchConfig.debug.enabled) {
        console.log(window.GISearchConfig.debug.logPrefix + ' 設定ファイルが読み込まれました', window.GISearchConfig);
    }
});

// グローバル参照の作成（互換性）
window.GI_CONFIG = window.GISearchConfig;
window.SearchConfig = window.GISearchConfig;