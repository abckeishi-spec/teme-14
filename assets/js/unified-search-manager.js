/**
 * 統合検索システム - 完全統合版
 * Grant Insight Perfect - 統合検索マネージャー
 * 
 * @version 1.0.0-unified
 * @package Grant_Insight_Perfect
 */
class GIUnifiedSearchManager {
    constructor() {
        this.config = window.GISearchConfig;
        this.state = {
            isLoading: false,
            currentQuery: null,
            results: [],
            filters: {},
            cache: new Map(),
            lastSearchTime: 0,
            searchHistory: [],
            suggestions: []
        };
        this.elements = {};
        this.legacyManagers = {}; // 既存システム参照
        this.eventListeners = new Map(); // イベントリスナー管理
        this.retryCount = 0;
        this.maxRetries = 3;
        
        // Phase 5: 音声検索とサジェスト機能の初期化
        this.voiceRecognition = null;
        this.isVoiceRecording = false;
        this.suggestionHistory = [];
        this.selectedSuggestionIndex = -1;
        this.debouncedSuggestion = null;
    }

    // 初期化
    async init() {
        console.log('🚀 統合検索マネージャー初期化開始');
        
        // 設定チェック
        if (!this.config) {
            throw new Error('GISearchConfig が見つかりません');
        }

        // DOM要素取得
        this.cacheElements();
        
        // 既存システム統合
        await this.integrateLegacySystems();
        
        // イベントバインド
        this.bindEvents();
        
        // 初期状態設定
        this.setupInitialState();
        
        // Phase 5: 音声検索とサジェスト機能の初期化
        this.initVoiceSearch();
        this.initSuggestions();
        
        // 設定の統合フラグ更新
        if (this.config.integration) {
            this.config.integration.initialized = true;
        }
        
        console.log('✅ 統合検索マネージャー初期化完了（Phase 5: 音声検索・サジェスト機能対応）');
        
        // 初期化完了イベント発火
        this.dispatchEvent('gi:manager:initialized', {
            manager: this,
            elements: this.elements,
            legacySystems: Object.keys(this.legacyManagers)
        });
    }

    // DOM要素キャッシュ（フェイルセーフ付き）
    cacheElements() {
        console.log('📦 DOM要素キャッシュ開始');

        // 検索入力要素（優先度順で検索）
        this.elements.searchInput = this.findFirstElement(this.config.elements.searchInputs);
        if (!this.elements.searchInput) {
            console.warn('⚠️ 検索入力要素が見つかりません');
        }

        // 検索ボタン要素
        this.elements.searchButton = this.findFirstElement(this.config.elements.searchButtons);
        if (!this.elements.searchButton) {
            console.warn('⚠️ 検索ボタンが見つかりません');
        }
        
        // 結果表示要素
        this.elements.resultsContainer = this.findFirstElement(this.config.elements.resultsContainers);
        if (!this.elements.resultsContainer) {
            console.warn('⚠️ 結果表示コンテナが見つかりません');
        }
        
        // フィルター要素
        this.elements.filters = {};
        Object.keys(this.config.elements.filters).forEach(filterType => {
            this.elements.filters[filterType] = this.findFirstElement(this.config.elements.filters[filterType]);
            if (!this.elements.filters[filterType]) {
                console.warn(`⚠️ フィルター要素が見つかりません: ${filterType}`);
            }
        });

        // その他の要素
        this.elements.loadingIndicator = this.findFirstElement(this.config.elements.loadingIndicator);
        this.elements.errorContainer = this.findFirstElement(this.config.elements.errorContainer);
        this.elements.suggestionContainer = this.findFirstElement(this.config.elements.suggestionContainer);
        this.elements.voiceButton = this.findFirstElement(this.config.elements.voiceButton);
        this.elements.clearButton = this.findFirstElement(this.config.elements.clearButton);
        this.elements.pagination = this.findFirstElement(this.config.elements.pagination);
        this.elements.filterPanel = this.findFirstElement(this.config.elements.filterPanel);
        this.elements.filterToggle = this.findFirstElement(this.config.elements.filterToggle);

        console.log('📦 要素キャッシュ完了:', {
            found: Object.keys(this.elements).filter(key => this.elements[key] !== null).length,
            total: Object.keys(this.elements).length,
            elements: Object.keys(this.elements)
        });
    }

    // 要素検索ヘルパー（フェイルセーフ機能強化）
    findFirstElement(ids) {
        if (!Array.isArray(ids)) {
            ids = [ids];
        }

        for (const id of ids) {
            if (!id) continue;
            
            try {
                const element = document.getElementById(id);
                if (element) {
                    console.log(`✓ 要素発見: ${id}`);
                    return element;
                }
            } catch (error) {
                console.warn(`❌ 要素検索エラー (${id}):`, error);
            }
        }
        
        if (this.config.debug.enabled) {
            console.warn(`❌ 要素が見つかりません: ${ids.join(', ')}`);
        }
        return null;
    }

    // 既存システム統合（レガシーブリッジ）
    async integrateLegacySystems() {
        console.log('🔗 既存システム統合開始');

        // ArchiveManager統合
        if (window.ArchiveManager) {
            console.log('🔗 ArchiveManager統合中');
            this.legacyManagers.archive = window.ArchiveManager;
            
            // 元のメソッドを保存
            if (typeof window.ArchiveManager.executeSearch === 'function') {
                this.legacyManagers.archive._originalExecuteSearch = 
                    window.ArchiveManager.executeSearch.bind(window.ArchiveManager);
            }
            
            if (typeof window.ArchiveManager.displayResults === 'function') {
                this.legacyManagers.archive._originalDisplayResults = 
                    window.ArchiveManager.displayResults.bind(window.ArchiveManager);
            }
            
            // 統合検索で置き換え
            window.ArchiveManager.executeSearch = (...args) => {
                console.log('🔄 ArchiveManager.executeSearch -> 統合検索');
                return this.executeUnifiedSearch('archive', ...args);
            };

            // 統合フラグ設定
            if (this.config.integration.legacySystems) {
                this.config.integration.legacySystems.archiveManager = true;
            }
        }

        // SearchSection統合
        if (window.SearchSection) {
            console.log('🔗 SearchSection統合中');
            this.legacyManagers.section = window.SearchSection;
            
            // 元のメソッドを保存
            if (typeof window.SearchSection.executeSearch === 'function') {
                this.legacyManagers.section._originalExecuteSearch = 
                    window.SearchSection.executeSearch.bind(window.SearchSection);
            }
            
            if (typeof window.SearchSection.showPreviewResults === 'function') {
                this.legacyManagers.section._originalShowPreviewResults = 
                    window.SearchSection.showPreviewResults.bind(window.SearchSection);
            }
            
            // 統合検索で置き換え
            window.SearchSection.executeSearch = (...args) => {
                console.log('🔄 SearchSection.executeSearch -> 統合検索');
                return this.executeUnifiedSearch('section', ...args);
            };

            // 統合フラグ設定
            if (this.config.integration.legacySystems) {
                this.config.integration.legacySystems.searchSection = true;
            }
        }

        // HeaderSearch統合（存在する場合）
        if (window.HeaderSearch) {
            console.log('🔗 HeaderSearch統合中');
            this.legacyManagers.header = window.HeaderSearch;
            
            if (typeof window.HeaderSearch.executeSearch === 'function') {
                this.legacyManagers.header._originalExecuteSearch = 
                    window.HeaderSearch.executeSearch.bind(window.HeaderSearch);
                    
                window.HeaderSearch.executeSearch = (...args) => {
                    console.log('🔄 HeaderSearch.executeSearch -> 統合検索');
                    return this.executeUnifiedSearch('header', ...args);
                };
            }

            // 統合フラグ設定
            if (this.config.integration.legacySystems) {
                this.config.integration.legacySystems.headerSearch = true;
            }
        }

        console.log('✅ 既存システム統合完了:', {
            archive: !!this.legacyManagers.archive,
            section: !!this.legacyManagers.section,
            header: !!this.legacyManagers.header
        });
    }

    // 統合検索実行（コアメソッド）
    async executeUnifiedSearch(source = 'unified', options = {}) {
        console.log(`🔍 統合検索実行 (source: ${source})`, options);

        // 検索中の場合はスキップ
        if (this.state.isLoading) {
            console.log('⏳ 検索中のためスキップ');
            return Promise.resolve(this.state.results);
        }

        // 重複リクエスト防止（短時間での連続実行）
        const now = Date.now();
        if (now - this.state.lastSearchTime < 500) {
            console.log('⚡ 重複リクエスト防止');
            return Promise.resolve(this.state.results);
        }
        this.state.lastSearchTime = now;

        try {
            this.state.isLoading = true;
            this.showLoading();

            // パラメータ構築
            const params = this.buildSearchParams(options);
            
            // 空の検索条件チェック
            if (!this.hasValidSearchCondition(params)) {
                console.log('🔍 検索条件が空のため全件取得');
                // 空検索の場合は全件取得
                params.search = '';
            }

            // キャッシュチェック
            const cacheKey = this.getCacheKey(params);
            if (this.config.defaults.cache.enabled && this.state.cache.has(cacheKey)) {
                const cachedData = this.state.cache.get(cacheKey);
                const now = Date.now();
                
                // キャッシュの有効期限チェック
                if (now - cachedData.timestamp < this.config.defaults.cache.ttl) {
                    console.log('📋 キャッシュから結果取得');
                    this.distributeResults(cachedData.results, source);
                    return cachedData.results;
                } else {
                    // 期限切れキャッシュを削除
                    this.state.cache.delete(cacheKey);
                }
            }

            // AJAX実行
            const results = await this.performAjaxSearch(params);
            
            // キャッシュ保存
            if (this.config.defaults.cache.enabled) {
                this.saveToCache(cacheKey, results);
            }
            
            // 検索履歴に追加
            this.addToSearchHistory(params);
            
            // 結果配布
            this.distributeResults(results, source);
            
            // リトライカウントリセット
            this.retryCount = 0;
            
            return results;

        } catch (error) {
            console.error('❌ 統合検索エラー:', error);
            
            // リトライ処理
            if (this.retryCount < this.maxRetries && this.shouldRetry(error)) {
                this.retryCount++;
                console.log(`🔄 検索リトライ (${this.retryCount}/${this.maxRetries})`);
                
                // 指数バックオフでリトライ
                const delay = Math.pow(2, this.retryCount) * 1000;
                await this.delay(delay);
                
                return this.executeUnifiedSearch(source, options);
            }
            
            this.handleError(error, source);
            throw error;
        } finally {
            this.state.isLoading = false;
            this.hideLoading();
        }
    }

    // 検索パラメータ構築
    buildSearchParams(options = {}) {
        const params = {
            search: this.getSearchValue(),
            categories: this.getFilterValue('category'),
            prefectures: this.getFilterValue('prefecture'),
            industries: this.getFilterValue('industry'),
            amount: this.getFilterValue('amount'),
            status: this.getFilterValue('status'),
            difficulty: this.getFilterValue('difficulty'),
            success_rate: this.getFilterValue('success_rate'),
            page: 1,
            posts_per_page: this.config.defaults.searchParams.posts_per_page,
            orderby: this.config.defaults.searchParams.orderby,
            order: this.config.defaults.searchParams.order,
            ...options
        };

        // 既存システムの状態も考慮
        if (this.legacyManagers.archive?.state?.filters) {
            Object.assign(params, this.legacyManagers.archive.state.filters);
        }

        if (this.legacyManagers.section?.state?.filters) {
            Object.assign(params, this.legacyManagers.section.state.filters);
        }

        // パラメータ正規化
        this.normalizeParams(params);

        console.log('🔧 検索パラメータ:', params);
        return params;
    }

    // パラメータ正規化
    normalizeParams(params) {
        // 配列フィルターの正規化
        ['categories', 'prefectures', 'industries', 'status', 'difficulty', 'success_rate'].forEach(key => {
            if (typeof params[key] === 'string' && params[key]) {
                params[key] = [params[key]];
            } else if (!Array.isArray(params[key])) {
                params[key] = [];
            }
            // 空の値を除去
            params[key] = params[key].filter(val => val && val.toString().trim());
        });

        // ページ番号の正規化
        params.page = Math.max(1, parseInt(params.page) || 1);
        params.posts_per_page = Math.max(1, Math.min(100, parseInt(params.posts_per_page) || 12));
    }

    // 有効な検索条件があるかチェック
    hasValidSearchCondition(params) {
        return !!(
            params.search ||
            params.categories.length > 0 ||
            params.prefectures.length > 0 ||
            params.industries.length > 0 ||
            params.amount ||
            params.status.length > 0 ||
            params.difficulty.length > 0 ||
            params.success_rate.length > 0
        );
    }

    // 検索値取得（フェイルセーフ強化）
    getSearchValue() {
        // メイン検索入力から取得
        if (this.elements.searchInput && this.elements.searchInput.value) {
            return this.elements.searchInput.value.trim();
        }
        
        // フォールバック: 全ての検索入力をチェック
        for (const id of this.config.elements.searchInputs) {
            const element = document.getElementById(id);
            if (element && element.value && element.value.trim()) {
                console.log(`🔍 フォールバック検索値取得: ${id}`);
                return element.value.trim();
            }
        }
        
        return '';
    }

    // フィルター値取得
    getFilterValue(filterType) {
        const element = this.elements.filters[filterType];
        if (element && element.value) {
            return element.value;
        }
        
        // フォールバック検索
        const ids = this.config.elements.filters[filterType] || [];
        for (const id of ids) {
            const el = document.getElementById(id);
            if (el && el.value) {
                console.log(`🔍 フォールバックフィルター取得: ${id}`);
                return el.value;
            }
        }
        
        return '';
    }

    // AJAX検索実行（エラーハンドリング強化）
    async performAjaxSearch(params) {
        const formData = new FormData();
        formData.append('action', this.config.ajax.action);
        
        // nonceの取得（複数のソースから試行）
        const nonce = window.gi_ajax?.nonce || 
                     window.ajax_object?.nonce || 
                     window.wpAjax?.nonce || '';
        
        if (nonce) {
            formData.append('nonce', nonce);
        }

        // パラメータ追加
        Object.keys(params).forEach(key => {
            const value = params[key];
            if (Array.isArray(value)) {
                formData.append(key, JSON.stringify(value));
            } else if (value !== null && value !== undefined && value !== '') {
                formData.append(key, value.toString());
            }
        });

        // AJAX URLの取得
        const ajaxUrl = window.gi_ajax?.ajax_url || 
                       window.ajax_object?.ajax_url || 
                       window.wpAjax?.ajaxUrl ||
                       '/wp-admin/admin-ajax.php';

        const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: formData,
            signal: AbortSignal.timeout(this.config.ajax.timeout)
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.data?.message || data.message || '検索に失敗しました');
        }

        return data.data;
    }

    // 結果配布（全システムに配信）
    distributeResults(results, source) {
        console.log(`📤 結果配布開始 (source: ${source})`, {
            grants: results.grants?.length || 0,
            total: results.found_posts || 0
        });

        try {
            // 直接表示（統合システム）
            this.renderResults(results);

            // ArchiveManagerに配布
            if (this.legacyManagers.archive && source !== 'archive') {
                try {
                    if (typeof this.legacyManagers.archive.displayResults === 'function') {
                        this.legacyManagers.archive.displayResults(results);
                    } else if (this.legacyManagers.archive._originalDisplayResults) {
                        this.legacyManagers.archive._originalDisplayResults(results);
                    }
                } catch (e) {
                    console.warn('ArchiveManager配布エラー:', e);
                }
            }

            // SearchSectionに配布  
            if (this.legacyManagers.section && source !== 'section') {
                try {
                    if (typeof this.legacyManagers.section.showPreviewResults === 'function') {
                        this.legacyManagers.section.showPreviewResults(results);
                    } else if (this.legacyManagers.section._originalShowPreviewResults) {
                        this.legacyManagers.section._originalShowPreviewResults(results);
                    }
                } catch (e) {
                    console.warn('SearchSection配布エラー:', e);
                }
            }

            // HeaderSearchに配布
            if (this.legacyManagers.header && source !== 'header') {
                try {
                    if (typeof this.legacyManagers.header.displayResults === 'function') {
                        this.legacyManagers.header.displayResults(results);
                    }
                } catch (e) {
                    console.warn('HeaderSearch配布エラー:', e);
                }
            }

            // 状態更新
            this.state.results = results.grants || [];
            this.state.currentQuery = results.query || {};

            // イベント発火
            this.dispatchEvent('gi:results:updated', {
                results: results,
                source: source,
                grants: this.state.results
            });

        } catch (error) {
            console.error('❌ 結果配布エラー:', error);
            throw error;
        }
    }

    // 結果描画（統合表示システム）
    renderResults(results) {
        if (!this.elements.resultsContainer) {
            console.warn('結果表示コンテナが見つかりません');
            return;
        }

        // ローディング状態をクリア
        this.elements.resultsContainer.classList.remove(this.config.classes.loadingState);

        if (!results.grants || results.grants.length === 0) {
            this.renderNoResults();
            return;
        }

        let html = '';
        results.grants.forEach((grant, index) => {
            html += grant.html || this.generateGrantCardHTML(grant, index);
        });

        this.elements.resultsContainer.innerHTML = html;
        
        // 結果統計の更新
        this.updateResultsStats(results);
        
        // ページネーションの更新
        this.updatePagination(results);
        
        // アニメーション
        this.animateResults();

        // 結果表示完了イベント
        this.dispatchEvent('gi:results:rendered', {
            container: this.elements.resultsContainer,
            count: results.grants.length,
            total: results.found_posts
        });
    }

    // 結果なし表示
    renderNoResults() {
        this.elements.resultsContainer.innerHTML = `
            <div class="gi-no-results text-center py-16">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        ${this.config.messages.noResults}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">検索条件を変更して再度お試しください</p>
                    <button onclick="window.unifiedSearch.resetSearch()" 
                            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all">
                        検索条件をリセット
                    </button>
                </div>
            </div>
        `;

        // 結果統計をクリア
        this.updateResultsStats({ found_posts: 0, grants: [] });
    }

    // 助成金カードHTML生成（統一フォーマット）
    generateGrantCardHTML(grant, index = 0) {
        const data = grant.data || grant;
        const cardId = `grant-card-${data.id || index}`;
        
        return `
            <div class="${this.config.classes.grantCard} w-full" data-grant-id="${data.id}" id="${cardId}">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col">
                    <!-- カードヘッダー -->
                    <div class="px-4 pt-4 pb-3">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${this.getStatusBadgeClass(data.status)}">
                                <span class="w-1.5 h-1.5 bg-current rounded-full mr-1.5"></span>
                                ${data.status || '募集中'}
                            </span>
                            <button class="gi-favorite-btn text-gray-400 hover:text-red-500 transition-colors p-1" 
                                    data-post-id="${data.id}" 
                                    title="お気に入りに追加">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-tight line-clamp-2 mb-2">
                            <a href="${data.permalink || '#'}" 
                               class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                ${data.title || '助成金情報'}
                            </a>
                        </h3>
                    </div>
                    
                    <!-- カードコンテンツ -->
                    <div class="px-4 pb-3 flex-grow">
                        <div class="flex items-center gap-2 mb-3 flex-wrap">
                            ${data.main_category ? `
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                ${data.main_category}
                            </span>
                            ` : ''}
                            ${data.prefecture ? `
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                📍 ${data.prefecture}
                            </span>
                            ` : ''}
                        </div>
                        
                        ${data.amount ? `
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg p-3 mb-3 border border-emerald-100 dark:border-emerald-700">
                            <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">最大助成額</div>
                            <div class="text-xl font-bold text-emerald-700 dark:text-emerald-300">
                                ${data.amount}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400 mb-2">
                            ${data.deadline ? `<div>締切: ${data.deadline}</div>` : ''}
                            ${data.difficulty ? `<div>難易度: ${data.difficulty}</div>` : ''}
                        </div>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            ${data.excerpt || data.description || ''}
                        </p>
                    </div>
                    
                    <!-- カードフッター -->
                    <div class="px-4 pb-4 pt-3 border-t border-gray-100 dark:border-gray-700 mt-auto">
                        <a href="${data.permalink || '#'}" 
                           class="block w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-center py-2 px-4 rounded-lg transition-all duration-200 text-sm font-medium">
                            詳細を見る
                        </a>
                    </div>
                </div>
            </div>
        `;
    }

    // ステータスバッジのクラス取得
    getStatusBadgeClass(status) {
        const statusClasses = {
            'open': 'bg-green-100 text-green-700',
            'recruiting': 'bg-green-100 text-green-700',
            'closed': 'bg-red-100 text-red-700',
            'scheduled': 'bg-yellow-100 text-yellow-700',
            'draft': 'bg-gray-100 text-gray-700'
        };
        
        return statusClasses[status] || statusClasses['open'];
    }

    // 結果統計の更新
    updateResultsStats(results) {
        const totalElement = document.getElementById('total-grants-count');
        const activeElement = document.getElementById('active-grants-count');
        
        if (totalElement) {
            totalElement.textContent = results.found_posts || 0;
        }
        
        if (activeElement) {
            const activeCount = results.grants?.filter(grant => 
                grant.status === 'open' || grant.status === 'recruiting'
            ).length || 0;
            activeElement.textContent = activeCount;
        }
    }

    // ページネーションの更新
    updatePagination(results) {
        if (!this.elements.pagination || !results.pagination) {
            return;
        }

        this.elements.pagination.innerHTML = results.pagination;
        
        // ページネーションボタンにイベントリスナーを追加
        this.elements.pagination.querySelectorAll('a[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                if (page && page > 0) {
                    this.executeUnifiedSearch('pagination', { page: page });
                }
            });
        });
    }

    // 結果アニメーション
    animateResults() {
        const cards = this.elements.resultsContainer.querySelectorAll(`.${this.config.classes.grantCard}`);
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * this.config.defaults.display.animationDelay);
        });
    }

    // イベントバインディング（統合イベントシステム）
    bindEvents() {
        console.log('📡 イベントバインド開始');

        // 検索ボタン
        if (this.elements.searchButton) {
            this.addEventListener(this.elements.searchButton, 'click', (e) => {
                e.preventDefault();
                this.executeUnifiedSearch('manual');
            });
        }

        // 検索入力 (Enter キー + リアルタイム検索 + キーボードナビゲーション)
        if (this.elements.searchInput) {
            // キーボードイベント（Enter, Arrow, Escape）
            this.addEventListener(this.elements.searchInput, 'keydown', (e) => {
                // サジェストのキーボードナビゲーションを優先
                if (this.handleSuggestionKeyboard(e)) {
                    return; // サジェストで処理された場合は終了
                }
                
                // Enterキーでの検索
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.executeUnifiedSearch('keyboard');
                }
            });

            // リアルタイムサジェスト（入力中）
            this.addEventListener(this.elements.searchInput, 'input', (e) => {
                const query = e.target.value.trim();
                if (query.length >= 2) {
                    if (this.debouncedSuggestion) {
                        this.debouncedSuggestion(query);
                    }
                } else {
                    this.hideSuggestions();
                }
            });

            // フォーカス時の処理
            this.addEventListener(this.elements.searchInput, 'focus', () => {
                const query = this.elements.searchInput.value.trim();
                if (query.length >= 2) {
                    if (this.debouncedSuggestion) {
                        this.debouncedSuggestion(query);
                    }
                }
            });

            // フォーカス喪失時の処理
            this.addEventListener(this.elements.searchInput, 'blur', () => {
                // 少し遅延してサジェストを隠す（クリック処理のため）
                setTimeout(() => {
                    this.hideSuggestions();
                }, 150);
            });
        }

        // フィルター変更
        Object.values(this.elements.filters).forEach(filterElement => {
            if (filterElement) {
                this.addEventListener(filterElement, 'change', () => {
                    this.executeUnifiedSearch('filter');
                });
            }
        });

        // クリアボタン
        if (this.elements.clearButton) {
            this.addEventListener(this.elements.clearButton, 'click', (e) => {
                e.preventDefault();
                this.clearSearch();
            });
        }

        // 音声検索ボタン
        if (this.elements.voiceButton) {
            this.addEventListener(this.elements.voiceButton, 'click', (e) => {
                e.preventDefault();
                this.startVoiceSearch();
            });
        }

        // フィルタートグル
        if (this.elements.filterToggle) {
            this.addEventListener(this.elements.filterToggle, 'click', (e) => {
                e.preventDefault();
                this.toggleFilterPanel();
            });
        }

        // お気に入りボタン（イベント委任）
        this.addEventListener(document, 'click', (e) => {
            if (e.target.closest('.gi-favorite-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.gi-favorite-btn');
                const postId = btn.dataset.postId;
                if (postId) {
                    this.toggleFavorite(postId);
                }
            }
        });

        // サジェストクリック（イベント委任）
        this.addEventListener(document, 'click', (e) => {
            if (e.target.closest('.gi-suggestion-item')) {
                e.preventDefault();
                const item = e.target.closest('.gi-suggestion-item');
                const text = item.dataset.text;
                if (text && this.elements.searchInput) {
                    this.elements.searchInput.value = text;
                    this.executeUnifiedSearch('suggestion');
                    this.hideSuggestions();
                }
            }
        });

        console.log('📡 イベントバインド完了');
    }

    // イベントリスナー管理（メモリリーク防止）
    addEventListener(element, event, handler) {
        if (!element) return;
        
        element.addEventListener(event, handler);
        
        // リスナーを記録（後で削除できるように）
        const key = `${element.id || 'anonymous'}-${event}`;
        if (!this.eventListeners.has(key)) {
            this.eventListeners.set(key, []);
        }
        this.eventListeners.get(key).push({ element, event, handler });
    }

    // お気に入り切り替え
    async toggleFavorite(postId) {
        if (!postId) return;

        try {
            const formData = new FormData();
            formData.append('action', this.config.endpoints.favorite);
            formData.append('nonce', window.gi_ajax?.nonce || '');
            formData.append('post_id', postId);

            const ajaxUrl = window.gi_ajax?.ajax_url || '/wp-admin/admin-ajax.php';
            const response = await fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // UI更新
                const btn = document.querySelector(`.gi-favorite-btn[data-post-id="${postId}"]`);
                if (btn) {
                    const svg = btn.querySelector('svg');
                    if (data.data.is_favorite) {
                        svg.setAttribute('fill', 'currentColor');
                        btn.classList.add('text-red-500');
                        btn.title = 'お気に入りから削除';
                    } else {
                        svg.setAttribute('fill', 'none');
                        btn.classList.remove('text-red-500');
                        btn.title = 'お気に入りに追加';
                    }
                }

                this.showNotification(
                    data.data.message || 
                    (data.data.is_favorite ? this.config.messages.favoriteAdded : this.config.messages.favoriteRemoved), 
                    'success'
                );
                
                // イベント発火
                this.dispatchEvent('gi:favorite:toggled', {
                    postId: postId,
                    isFavorite: data.data.is_favorite
                });
            } else {
                throw new Error(data.data?.message || 'お気に入りの更新に失敗しました');
            }
        } catch (error) {
            console.error('❌ お気に入りエラー:', error);
            this.showNotification('お気に入りの更新に失敗しました', 'error');
        }
    }

    // 検索クリア
    clearSearch() {
        // 検索入力クリア
        if (this.elements.searchInput) {
            this.elements.searchInput.value = '';
        }

        // 全ての検索入力をクリア（フェイルセーフ）
        this.config.elements.searchInputs.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.value = '';
            }
        });

        // フィルタークリア
        Object.values(this.elements.filters).forEach(filterElement => {
            if (filterElement) {
                filterElement.value = '';
                filterElement.selectedIndex = 0;
            }
        });

        // クリアボタンを隠す
        if (this.elements.clearButton) {
            this.elements.clearButton.classList.add('hidden');
        }

        // サジェストを隠す
        this.hideSuggestions();

        this.showNotification(this.config.messages.searchReset, 'info');
        
        // 検索実行（全件表示）
        this.executeUnifiedSearch('clear');
    }

    // 検索リセット（別名でも呼べるように）
    resetSearch() {
        this.clearSearch();
    }

    // フィルターパネルの切り替え
    toggleFilterPanel() {
        if (!this.elements.filterPanel) return;

        const isVisible = !this.elements.filterPanel.classList.contains('hidden');
        
        if (isVisible) {
            this.elements.filterPanel.classList.add('hidden');
            if (this.elements.filterToggle) {
                this.elements.filterToggle.classList.remove(this.config.classes.filterActive);
            }
        } else {
            this.elements.filterPanel.classList.remove('hidden');
            if (this.elements.filterToggle) {
                this.elements.filterToggle.classList.add(this.config.classes.filterActive);
            }
        }

        // イベント発火
        this.dispatchEvent('gi:filter:toggled', {
            visible: !isVisible,
            panel: this.elements.filterPanel
        });
    }

    // ローディング表示
    showLoading() {
        // 結果コンテナにローディングクラス追加
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.classList.add(this.config.classes.loadingState);
        }
        
        // 検索ボタンの状態変更
        if (this.elements.searchButton) {
            this.elements.searchButton.disabled = true;
            const originalContent = this.elements.searchButton.innerHTML;
            this.elements.searchButton.dataset.originalContent = originalContent;
            this.elements.searchButton.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>検索中...';
        }

        // ローディングインジケーター表示
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.classList.remove('hidden');
        }

        // イベント発火
        this.dispatchEvent('gi:loading:start');
    }

    // ローディング非表示
    hideLoading() {
        // 結果コンテナからローディングクラス削除
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.classList.remove(this.config.classes.loadingState);
        }
        
        // 検索ボタンの状態復元
        if (this.elements.searchButton) {
            this.elements.searchButton.disabled = false;
            const originalContent = this.elements.searchButton.dataset.originalContent;
            if (originalContent) {
                this.elements.searchButton.innerHTML = originalContent;
            } else {
                this.elements.searchButton.innerHTML = '<i class="fas fa-search mr-2"></i>検索';
            }
        }

        // ローディングインジケーター非表示
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.classList.add('hidden');
        }

        // イベント発火
        this.dispatchEvent('gi:loading:end');
    }

    // エラーハンドリング（統合エラー処理）
    handleError(error, source) {
        console.error(`❌ 統合検索エラー (${source}):`, error);
        
        // エラー表示
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.innerHTML = `
                <div class="gi-error-message text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-exclamation-triangle text-6xl text-red-400 mb-6"></i>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">エラーが発生しました</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">${this.getErrorMessage(error)}</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button onclick="window.unifiedSearch.executeUnifiedSearch('retry')" 
                                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                再試行
                            </button>
                            <button onclick="window.unifiedSearch.resetSearch()" 
                                    class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                検索条件をリセット
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // エラー通知表示
        this.showNotification(this.getErrorMessage(error), 'error');

        // エラーイベント発火
        this.dispatchEvent('gi:error', {
            error: error,
            source: source,
            message: this.getErrorMessage(error)
        });
    }

    // エラーメッセージの取得
    getErrorMessage(error) {
        if (error.name === 'AbortError') {
            return this.config.messages.timeout;
        }
        if (error.message.includes('NetworkError') || error.message.includes('fetch')) {
            return this.config.messages.networkError;
        }
        return error.message || this.config.messages.error;
    }

    // リトライすべきエラーかどうか判定
    shouldRetry(error) {
        // ネットワークエラーやタイムアウトはリトライする
        return error.name === 'AbortError' || 
               error.message.includes('NetworkError') ||
               error.message.includes('fetch') ||
               error.message.includes('500') ||
               error.message.includes('502') ||
               error.message.includes('503');
    }

    // 遅延処理
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // 通知表示（統合通知システム）
    showNotification(message, type = 'info', duration = null) {
        // 既存の通知を削除
        const existingNotification = document.querySelector('.gi-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        const notification = document.createElement('div');
        notification.className = `${this.config.classes.notification} ${this.config.classes['notification' + type.charAt(0).toUpperCase() + type.slice(1)]} fixed bottom-6 right-6 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform translate-x-full transition-transform duration-300`;

        const styles = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'warning': 'bg-yellow-500',
            'info': 'bg-blue-500'
        };

        if (styles[type]) {
            notification.classList.add(styles[type]);
        }

        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle', 
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${icons[type] || icons['info']}"></i>
                <span class="flex-1">${message}</span>
                <button class="ml-4 text-white/80 hover:text-white transition-colors gi-notification-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // クリックで閉じる
        const closeBtn = notification.querySelector('.gi-notification-close');
        closeBtn.addEventListener('click', () => {
            this.hideNotification(notification);
        });

        // 表示アニメーション
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);

        // 自動で閉じる
        const autoDuration = duration || this.config.defaults.display.notificationDuration;
        setTimeout(() => {
            if (notification.parentNode) {
                this.hideNotification(notification);
            }
        }, autoDuration);

        // 通知イベント発火
        this.dispatchEvent('gi:notification:shown', {
            message: message,
            type: type,
            element: notification
        });
    }

    // 通知を隠す
    hideNotification(notification) {
        if (!notification || !notification.parentNode) return;
        
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }

    // キャッシュ関連メソッド
    getCacheKey(params) {
        return JSON.stringify(params, Object.keys(params).sort());
    }

    saveToCache(key, data) {
        // キャッシュサイズ制限
        if (this.state.cache.size >= this.config.defaults.cache.maxSize) {
            // 古いエントリを削除（LRU方式）
            const firstKey = this.state.cache.keys().next().value;
            this.state.cache.delete(firstKey);
        }

        this.state.cache.set(key, {
            results: data,
            timestamp: Date.now()
        });
    }

    clearCache() {
        this.state.cache.clear();
        console.log('🗑️ キャッシュクリア完了');
    }

    // 検索履歴管理
    addToSearchHistory(params) {
        if (!params.search && !this.hasValidSearchCondition(params)) {
            return;
        }

        const historyItem = {
            query: params.search,
            filters: { ...params },
            timestamp: Date.now()
        };

        // 重複を避ける
        this.state.searchHistory = this.state.searchHistory.filter(
            item => item.query !== params.search
        );

        this.state.searchHistory.unshift(historyItem);

        // 履歴の最大件数制限
        if (this.state.searchHistory.length > 50) {
            this.state.searchHistory = this.state.searchHistory.slice(0, 50);
        }
    }

    // ユーティリティメソッド
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // カスタムイベント発火
    dispatchEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            detail: detail,
            bubbles: true,
            cancelable: true
        });
        document.dispatchEvent(event);
    }

    // 初期状態設定
    setupInitialState() {
        // URLパラメータから検索条件を復元
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        
        if (searchParam && this.elements.searchInput) {
            this.elements.searchInput.value = searchParam;
            
            // クリアボタン表示
            if (this.elements.clearButton) {
                this.elements.clearButton.classList.remove('hidden');
            }
        }

        // フィルターパラメータの復元
        Object.keys(this.elements.filters).forEach(filterType => {
            const filterValue = urlParams.get(filterType);
            if (filterValue && this.elements.filters[filterType]) {
                this.elements.filters[filterType].value = filterValue;
            }
        });

        // 初期検索実行（条件がある場合）
        if (searchParam || this.hasValidSearchCondition(this.buildSearchParams())) {
            setTimeout(() => {
                this.executeUnifiedSearch('initial');
            }, 100);
        }
    }

    // クリーンアップ（メモリリーク防止）
    destroy() {
        // イベントリスナーの削除
        this.eventListeners.forEach((listeners) => {
            listeners.forEach(({ element, event, handler }) => {
                element.removeEventListener(event, handler);
            });
        });
        this.eventListeners.clear();

        // キャッシュクリア
        this.clearCache();

        // 参照クリア
        this.elements = {};
        this.legacyManagers = {};
        this.state = {
            isLoading: false,
            currentQuery: null,
            results: [],
            filters: {},
            cache: new Map(),
            lastSearchTime: 0,
            searchHistory: [],
            suggestions: []
        };

        console.log('🗑️ 統合検索マネージャー破棄完了');
    }

    // =================================================================
    // Phase 5: 音声検索とサジェスト機能 (完全実装版)
    // =================================================================
    
    /**
     * 音声検索機能の初期化
     */
    initVoiceSearch() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            console.warn('⚠️ 音声認識APIがサポートされていません');
            if (this.elements.voiceButton) {
                this.elements.voiceButton.style.display = 'none';
            }
            return false;
        }

        this.voiceRecognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        this.voiceRecognition.continuous = false;
        this.voiceRecognition.interimResults = true;
        this.voiceRecognition.lang = 'ja-JP';
        this.voiceRecognition.maxAlternatives = 1;

        this.voiceRecognition.onstart = () => {
            console.log('🎤 音声認識開始');
            this.handleVoiceStart();
        };

        this.voiceRecognition.onresult = (event) => {
            this.handleVoiceResult(event);
        };

        this.voiceRecognition.onerror = (event) => {
            this.handleVoiceError(event);
        };

        this.voiceRecognition.onend = () => {
            this.handleVoiceEnd();
        };

        return true;
    }

    /**
     * 音声検索開始
     */
    startVoiceSearch() {
        if (!this.voiceRecognition) {
            if (!this.initVoiceSearch()) {
                this.showNotification('音声検索に対応していません', 'error');
                return;
            }
        }

        if (this.isVoiceRecording) {
            this.stopVoiceSearch();
            return;
        }

        try {
            this.isVoiceRecording = true;
            this.voiceRecognition.start();
            
            // UI更新
            if (this.elements.voiceButton) {
                this.elements.voiceButton.classList.add('gi-voice-recording');
                this.elements.voiceButton.innerHTML = '<i class="fas fa-stop"></i>';
                this.elements.voiceButton.setAttribute('title', '音声認識停止');
            }
            
            this.showNotification('音声入力中... 話してください', 'info');
        } catch (error) {
            console.error('音声認識開始エラー:', error);
            this.handleVoiceError({ error: error.name });
        }
    }

    /**
     * 音声検索停止
     */
    stopVoiceSearch() {
        if (this.voiceRecognition && this.isVoiceRecording) {
            this.voiceRecognition.stop();
        }
        this.isVoiceRecording = false;
    }

    /**
     * 音声認識開始ハンドラー
     */
    handleVoiceStart() {
        this.isVoiceRecording = true;
        console.log('🎤 音声認識が開始されました');
        
        // ビジュアルフィードバック
        if (this.elements.searchInput) {
            this.elements.searchInput.setAttribute('placeholder', '音声入力中...');
            this.elements.searchInput.classList.add('gi-voice-input-active');
        }
    }

    /**
     * 音声認識結果ハンドラー
     */
    handleVoiceResult(event) {
        let transcript = '';
        let isFinal = false;

        for (let i = event.resultIndex; i < event.results.length; i++) {
            const result = event.results[i];
            transcript += result[0].transcript;
            if (result.isFinal) {
                isFinal = true;
            }
        }

        console.log('🎤 音声認識結果:', transcript, 'Final:', isFinal);

        // 検索入力フィールドに結果を表示
        if (this.elements.searchInput) {
            this.elements.searchInput.value = transcript;
            
            // リアルタイムサジェスト表示
            if (transcript.length > 1) {
                this.debouncedSuggestion(transcript);
            }
        }

        // 最終結果の場合は自動検索
        if (isFinal && transcript.length > 1) {
            setTimeout(() => {
                this.executeUnifiedSearch('voice', { search: transcript });
                this.showNotification(`"${transcript}" で検索しました`, 'success');
            }, 500);
        }
    }

    /**
     * 音声認識エラーハンドラー
     */
    handleVoiceError(event) {
        console.error('音声認識エラー:', event.error);
        this.isVoiceRecording = false;
        
        let errorMessage = '音声認識エラーが発生しました';
        switch (event.error) {
            case 'no-speech':
                errorMessage = '音声が検出されませんでした';
                break;
            case 'audio-capture':
                errorMessage = 'マイクにアクセスできませんでした';
                break;
            case 'not-allowed':
                errorMessage = 'マイクの使用が許可されていません';
                break;
            case 'network':
                errorMessage = 'ネットワークエラーが発生しました';
                break;
        }
        
        this.showNotification(errorMessage, 'error');
        this.resetVoiceUI();
    }

    /**
     * 音声認識終了ハンドラー
     */
    handleVoiceEnd() {
        console.log('🎤 音声認識が終了しました');
        this.isVoiceRecording = false;
        this.resetVoiceUI();
    }

    /**
     * 音声UI リセット
     */
    resetVoiceUI() {
        if (this.elements.voiceButton) {
            this.elements.voiceButton.classList.remove('gi-voice-recording');
            this.elements.voiceButton.innerHTML = '<i class="fas fa-microphone"></i>';
            this.elements.voiceButton.setAttribute('title', '音声検索');
        }
        
        if (this.elements.searchInput) {
            this.elements.searchInput.setAttribute('placeholder', 'キーワードを入力してください');
            this.elements.searchInput.classList.remove('gi-voice-input-active');
        }
    }

    // =================================================================
    // サジェスト機能 (完全実装版)
    // =================================================================

    /**
     * サジェスト機能の初期化
     */
    initSuggestions() {
        // デバウンス関数の作成
        this.debouncedSuggestion = this.debounce(this.fetchSuggestions.bind(this), 300);
        
        // サジェスト履歴の初期化
        this.suggestionHistory = JSON.parse(localStorage.getItem('gi_suggestion_history') || '[]');
        
        console.log('💡 サジェスト機能初期化完了');
    }

    /**
     * サジェスト取得とキャッシュ
     */
    async fetchSuggestions(query) {
        if (!query || query.length < 2) {
            this.hideSuggestions();
            return;
        }

        const cacheKey = `suggestion:${query}`;
        
        // キャッシュから取得
        if (this.state.cache.has(cacheKey)) {
            const cached = this.state.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < 300000) { // 5分間有効
                this.showSuggestions(cached.results, query);
                return;
            }
        }

        try {
            console.log('💡 サジェスト取得:', query);
            
            const response = await fetch(gi_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'gi_get_search_suggestions',
                    nonce: gi_ajax.nonce,
                    query: query,
                    limit: 8
                })
            });

            const data = await response.json();
            
            if (data.success && data.data) {
                // キャッシュに保存
                this.state.cache.set(cacheKey, {
                    results: data.data,
                    timestamp: Date.now()
                });
                
                this.showSuggestions(data.data, query);
            } else {
                console.warn('サジェスト取得失敗:', data.data);
                this.showHistoryBasedSuggestions(query);
            }
            
        } catch (error) {
            console.error('サジェスト取得エラー:', error);
            this.showHistoryBasedSuggestions(query);
        }
    }

    /**
     * サジェスト表示
     */
    showSuggestions(suggestions, query) {
        if (!this.elements.suggestionContainer) {
            this.createSuggestionContainer();
        }

        if (!suggestions || suggestions.length === 0) {
            this.hideSuggestions();
            return;
        }

        console.log('💡 サジェスト表示:', suggestions.length + '件');

        // サジェストHTML生成
        let html = '<div class="gi-suggestion-header">候補</div>';
        html += '<ul class="gi-suggestion-list">';
        
        suggestions.forEach((suggestion, index) => {
            const highlightedText = this.highlightMatch(suggestion.text, query);
            html += `
                <li class="gi-suggestion-item" data-suggestion="${suggestion.text}" data-index="${index}">
                    <i class="fas ${suggestion.type === 'history' ? 'fa-history' : 'fa-search'}"></i>
                    <span class="suggestion-text">${highlightedText}</span>
                    ${suggestion.count ? `<span class="suggestion-count">${suggestion.count}</span>` : ''}
                </li>
            `;
        });
        
        html += '</ul>';

        this.elements.suggestionContainer.innerHTML = html;
        this.elements.suggestionContainer.classList.remove('hidden');
        this.elements.suggestionContainer.classList.add('gi-suggestion-active');

        // サジェストクリックイベント
        this.bindSuggestionEvents();
    }

    /**
     * 履歴ベースのサジェスト
     */
    showHistoryBasedSuggestions(query) {
        const historyMatches = this.suggestionHistory
            .filter(item => item.toLowerCase().includes(query.toLowerCase()))
            .slice(0, 5)
            .map(text => ({ text, type: 'history' }));

        if (historyMatches.length > 0) {
            this.showSuggestions(historyMatches, query);
        } else {
            this.hideSuggestions();
        }
    }

    /**
     * サジェストイベントバインド
     */
    bindSuggestionEvents() {
        if (!this.elements.suggestionContainer) return;

        const items = this.elements.suggestionContainer.querySelectorAll('.gi-suggestion-item');
        items.forEach((item, index) => {
            // マウスオーバー
            item.addEventListener('mouseenter', () => {
                this.clearSuggestionSelection();
                item.classList.add('gi-suggestion-selected');
                this.selectedSuggestionIndex = index;
            });

            // クリック
            item.addEventListener('click', () => {
                const text = item.getAttribute('data-suggestion');
                this.applySuggestion(text);
            });
        });
    }

    /**
     * サジェスト適用
     */
    applySuggestion(text) {
        if (this.elements.searchInput) {
            this.elements.searchInput.value = text;
            this.elements.searchInput.focus();
        }
        
        // 履歴に追加
        this.addToSuggestionHistory(text);
        
        // サジェスト非表示
        this.hideSuggestions();
        
        // 検索実行
        setTimeout(() => {
            this.executeUnifiedSearch('suggestion', { search: text });
        }, 100);
    }

    /**
     * サジェスト履歴管理
     */
    addToSuggestionHistory(text) {
        if (!text || text.length < 2) return;
        
        // 重複削除
        this.suggestionHistory = this.suggestionHistory.filter(item => item !== text);
        
        // 先頭に追加
        this.suggestionHistory.unshift(text);
        
        // 最大50件まで
        if (this.suggestionHistory.length > 50) {
            this.suggestionHistory = this.suggestionHistory.slice(0, 50);
        }
        
        // ローカルストレージに保存
        localStorage.setItem('gi_suggestion_history', JSON.stringify(this.suggestionHistory));
    }

    /**
     * サジェスト非表示
     */
    hideSuggestions() {
        if (this.elements.suggestionContainer) {
            this.elements.suggestionContainer.classList.add('hidden');
            this.elements.suggestionContainer.classList.remove('gi-suggestion-active');
        }
        this.selectedSuggestionIndex = -1;
    }

    /**
     * サジェストコンテナ作成
     */
    createSuggestionContainer() {
        if (!this.elements.searchInput) return;

        const container = document.createElement('div');
        container.id = 'gi-suggestions-unified';
        container.className = 'gi-suggestion-container hidden';
        
        // 検索入力の直後に挿入
        this.elements.searchInput.parentNode.insertBefore(
            container, 
            this.elements.searchInput.nextSibling
        );
        
        this.elements.suggestionContainer = container;
    }

    /**
     * キーワードハイライト
     */
    highlightMatch(text, query) {
        if (!query) return text;
        
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    /**
     * サジェスト選択クリア
     */
    clearSuggestionSelection() {
        if (this.elements.suggestionContainer) {
            const items = this.elements.suggestionContainer.querySelectorAll('.gi-suggestion-item');
            items.forEach(item => item.classList.remove('gi-suggestion-selected'));
        }
    }

    /**
     * キーボードナビゲーション（サジェスト用）
     */
    handleSuggestionKeyboard(event) {
        if (!this.elements.suggestionContainer || 
            this.elements.suggestionContainer.classList.contains('hidden')) {
            return false;
        }

        const items = this.elements.suggestionContainer.querySelectorAll('.gi-suggestion-item');
        if (items.length === 0) return false;

        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                this.selectedSuggestionIndex = (this.selectedSuggestionIndex + 1) % items.length;
                this.updateSuggestionSelection(items);
                return true;

            case 'ArrowUp':
                event.preventDefault();
                this.selectedSuggestionIndex = this.selectedSuggestionIndex <= 0 
                    ? items.length - 1 
                    : this.selectedSuggestionIndex - 1;
                this.updateSuggestionSelection(items);
                return true;

            case 'Enter':
                if (this.selectedSuggestionIndex >= 0) {
                    event.preventDefault();
                    const selectedItem = items[this.selectedSuggestionIndex];
                    const text = selectedItem.getAttribute('data-suggestion');
                    this.applySuggestion(text);
                    return true;
                }
                break;

            case 'Escape':
                this.hideSuggestions();
                return true;
        }

        return false;
    }

    /**
     * サジェスト選択更新
     */
    updateSuggestionSelection(items) {
        this.clearSuggestionSelection();
        if (this.selectedSuggestionIndex >= 0 && items[this.selectedSuggestionIndex]) {
            items[this.selectedSuggestionIndex].classList.add('gi-suggestion-selected');
        }
    }
}

// 初期化処理
document.addEventListener('DOMContentLoaded', async () => {
    try {
        console.log('📋 DOM読み込み完了 - 統合検索マネージャー初期化開始');
        
        // 設定の読み込みを待つ
        if (!window.GISearchConfig) {
            console.log('⏳ 設定ファイルの読み込みを待機中...');
            await new Promise((resolve) => {
                const checkConfig = setInterval(() => {
                    if (window.GISearchConfig) {
                        clearInterval(checkConfig);
                        resolve();
                    }
                }, 50);
                
                // 5秒でタイムアウト
                setTimeout(() => {
                    clearInterval(checkConfig);
                    console.warn('⚠️ 設定ファイルの読み込みタイムアウト');
                    resolve();
                }, 5000);
            });
        }

        window.unifiedSearch = new GIUnifiedSearchManager();
        await window.unifiedSearch.init();
        
        // 既存の参照も更新（互換性）
        window.GISearchManager = window.unifiedSearch;
        window.GIUnifiedSearch = window.unifiedSearch;
        
        // グローバル関数の作成（テンプレートから呼び出し用）
        window.searchGrants = function(params) {
            return window.unifiedSearch.executeUnifiedSearch('global', params);
        };

        window.resetSearch = function() {
            return window.unifiedSearch.resetSearch();
        };

        console.log('✅ 統合検索システム起動完了');
        
    } catch (error) {
        console.error('❌ 統合検索システム初期化失敗:', error);
        
        // フォールバック処理
        window.unifiedSearch = {
            executeUnifiedSearch: () => console.warn('統合検索システムが利用できません'),
            resetSearch: () => console.warn('統合検索システムが利用できません')
        };
    }
});

// ページ離脱時のクリーンアップ
window.addEventListener('beforeunload', () => {
    if (window.unifiedSearch && typeof window.unifiedSearch.destroy === 'function') {
        window.unifiedSearch.destroy();
    }
});