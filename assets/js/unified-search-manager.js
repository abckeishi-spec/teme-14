/**
 * Unified Search Manager
 * Version: 4.0.0
 * Updated: 2025-09-17
 * 
 * 統合検索システムのメインマネージャー
 */
class GIUnifiedSearchManager {
    constructor() {
        this.config = window.GISearchConfig || {};
        this.elements = {};
        this.state = {
            isLoading: false,
            currentQuery: {},
            currentRequest: null,
            cache: new Map(),
            debounceTimer: null
        };
    }

    init() {
        this.debugLog('Initializing Unified Search Manager');
        this.initializeElements();
        this.bindEvents();
        this.restoreState();
        this.debugLog('Initialization complete');
    }

    // DOM要素の初期化
    initializeElements() {
        this.elements = {
            searchInputs: [],
            filters: {},
            sortSelect: null,
            perPageSelect: null,
            resultsContainer: null,
            pagination: null,
            loadingIndicator: null
        };
        
        // 検索入力フィールドの収集
        this.config.elements.searchInputs.forEach(id => {
            const element = document.getElementById(id);
            if (element) this.elements.searchInputs.push(element);
        });
        
        // フィルター要素の収集
        Object.keys(this.config.elements.filters).forEach(filterType => {
            this.elements.filters[filterType] = [];
            this.config.elements.filters[filterType].forEach(id => {
                const element = document.getElementById(id);
                if (element) this.elements.filters[filterType].push(element);
            });
        });
        
        // その他の要素
        this.elements.sortSelect = document.getElementById(this.config.elements.sortSelect);
        this.elements.perPageSelect = document.getElementById(this.config.elements.perPageSelect);
        this.elements.resultsContainer = document.getElementById(this.config.elements.resultsContainer);
        this.elements.pagination = document.getElementById(this.config.elements.pagination);
        this.elements.loadingIndicator = document.getElementById(this.config.elements.loadingIndicator);
    }

    // イベントバインド
    bindEvents() {
        // 検索入力
        this.elements.searchInputs.forEach(input => {
            input.addEventListener('input', (e) => this.handleSearchInput(e));
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.executeUnifiedSearch();
                }
            });
        });
        
        // フィルター変更
        Object.values(this.elements.filters).forEach(filterElements => {
            filterElements.forEach(element => {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.addEventListener('change', () => this.executeUnifiedSearch());
                } else {
                    element.addEventListener('change', () => this.executeUnifiedSearch());
                }
            });
        });
        
        // ソート変更
        if (this.elements.sortSelect) {
            this.elements.sortSelect.addEventListener('change', () => this.executeUnifiedSearch());
        }
        
        // 表示件数変更
        if (this.elements.perPageSelect) {
            this.elements.perPageSelect.addEventListener('change', () => this.executeUnifiedSearch({ page: 1 }));
        }
        
        // ページネーションイベント（動的に追加）
        document.addEventListener('click', (e) => {
            if (e.target.closest('.pagination-item a')) {
                e.preventDefault();
                const link = e.target.closest('.pagination-item a');
                const page = parseInt(link.dataset.page, 10);
                if (page && page > 0) {
                    this.executeUnifiedSearch({ page });
                }
            }
        });
    }

    // 検索入力ハンドリング
    handleSearchInput(e) {
        clearTimeout(this.state.debounceTimer);
        
        if (this.config.ui.enableAutoComplete) {
            this.state.debounceTimer = setTimeout(() => {
                this.showSuggestions(e.target.value);
            }, this.config.ui.debounceDelay);
        }
    }

    // 統合検索実行
    async executeUnifiedSearch(overrides = {}) {
        if (this.state.isLoading) {
            this.debugLog('Search already in progress');
            return;
        }
        
        const params = this.collectSearchParams(overrides);
        
        // キャッシュチェック
        const cacheKey = JSON.stringify(params);
        if (this.state.cache.has(cacheKey)) {
            this.debugLog('Using cached results');
            const cachedData = this.state.cache.get(cacheKey);
            this.displayResults(cachedData);
            return;
        }
        
        this.state.isLoading = true;
        this.showLoadingState();
        
        try {
            const data = await this.performAjaxSearch(params);
            
            if (data.success) {
                this.state.cache.set(cacheKey, data);
                this.displayResults(data);
                this.saveState(params);
            } else {
                this.showError(data.data || 'Search failed');
            }
        } catch (error) {
            this.debugLog('Search error:', error);
            this.showError('Search request failed');
        } finally {
            this.state.isLoading = false;
            this.hideLoadingState();
        }
    }

    // 検索パラメータ収集
    collectSearchParams(overrides = {}) {
        const params = {
            search: this.getSearchKeyword(),
            amount: this.getFilterValue('amount'),
            status: this.getFilterValue('status'),
            industry: this.getFilterValue('industry'),
            region: this.getFilterValue('region'),
            orderby: this.elements.sortSelect?.value || 'date_desc',
            posts_per_page: parseInt(this.elements.perPageSelect?.value || 12),
            page: 1,
            ...overrides
        };
        
        this.state.currentQuery = params;
        return params;
    }

    // 検索キーワード取得
    getSearchKeyword() {
        for (const input of this.elements.searchInputs) {
            if (input.value) return input.value;
        }
        return '';
    }

    // フィルター値取得
    getFilterValue(filterType) {
        const elements = this.elements.filters[filterType];
        if (!elements || elements.length === 0) return '';
        
        const values = [];
        elements.forEach(element => {
            if (element.type === 'checkbox' || element.type === 'radio') {
                if (element.checked) values.push(element.value);
            } else if (element.tagName === 'SELECT') {
                if (element.multiple) {
                    Array.from(element.selectedOptions).forEach(option => {
                        values.push(option.value);
                    });
                } else if (element.value) {
                    values.push(element.value);
                }
            } else if (element.value) {
                values.push(element.value);
            }
        });
        
        return filterType === 'amount' || filterType === 'status' ? values.join(',') : values[0] || '';
    }

    // AJAX検索実行
    performAjaxSearch(params) {
        // 前のリクエストをキャンセル
        if (this.state.currentRequest) {
            this.state.currentRequest.abort();
        }
        
        this.state.currentRequest = new AbortController();
        
        // タイムアウト設定
        const timeoutId = setTimeout(() => {
            this.state.currentRequest.abort();
        }, this.config.timeout);
        
        const formData = new FormData();
        formData.append('action', this.config.api.endpoint);
        formData.append('nonce', window.gi_ajax?.nonce || '');
        Object.keys(params).forEach(key => {
            formData.append(key, params[key]);
        });
        
        return fetch(window.gi_ajax?.ajax_url || '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData,
            signal: this.state.currentRequest.signal
        })
        .then(response => response.json())
        .finally(() => {
            clearTimeout(timeoutId);
            this.state.currentRequest = null;
        });
    }

    // 結果表示
    displayResults(data) {
        if (!this.elements.resultsContainer) return;
        
        if (data.data && data.data.html) {
            this.elements.resultsContainer.innerHTML = data.data.html;
        } else if (data.data && data.data.grants) {
            this.renderGrants(data.data.grants);
        }
        
        if (data.data && data.data.pagination) {
            this.updatePagination(data.data.pagination);
        }
        
        // アニメーション
        this.animateResults();
    }

    // 助成金カード描画
    renderGrants(grants) {
        if (!this.elements.resultsContainer) return;
        
        if (grants.length === 0) {
            this.elements.resultsContainer.innerHTML = `
                <div class="no-results">
                    <p>${window.gi_ajax?.strings?.no_results || 'No results found'}</p>
                </div>
            `;
            return;
        }
        
        let html = '<div class="grant-cards-grid">';
        grants.forEach(grant => {
            html += this.createGrantCard(grant);
        });
        html += '</div>';
        
        this.elements.resultsContainer.innerHTML = html;
    }

    // 助成金カード生成
    createGrantCard(grant) {
        return `
            <article class="grant-card">
                <header class="grant-header">
                    <h3 class="grant-title">
                        <a href="${grant.permalink}">${grant.title}</a>
                    </h3>
                    <button class="grant-favorite" data-id="${grant.id}">
                        <i class="far fa-heart"></i>
                    </button>
                </header>
                <div class="grant-meta">
                    <span class="grant-meta-item">
                        <i class="fas fa-yen-sign"></i>
                        ${grant.amount}
                    </span>
                    <span class="grant-meta-item">
                        <i class="fas fa-calendar"></i>
                        ${grant.deadline}
                    </span>
                </div>
                <div class="grant-description">
                    ${grant.excerpt}
                </div>
                <div class="grant-tags">
                    ${grant.categories ? grant.categories.map(cat => 
                        `<span class="grant-tag">${cat}</span>`
                    ).join('') : ''}
                </div>
            </article>
        `;
    }

    // ページネーション更新
    updatePagination(paginationHtml) {
        if (this.elements.pagination) {
            this.elements.pagination.innerHTML = paginationHtml;
            
            // data-page属性を持つリンクにイベント追加
            this.elements.pagination.querySelectorAll('a[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = parseInt(link.dataset.page, 10);
                    if (page && page > 0) {
                        this.executeUnifiedSearch({ page });
                    }
                });
            });
        }
    }

    // サジェスト表示
    async showSuggestions(keyword) {
        if (!keyword || keyword.length < 2) {
            this.hideSuggestions();
            return;
        }
        
        // サジェストAPI呼び出し（実装が必要な場合）
        // const suggestions = await this.fetchSuggestions(keyword);
        // this.renderSuggestions(suggestions);
    }

    // サジェスト非表示
    hideSuggestions() {
        // サジェストコンテナを非表示にする処理
    }

    // アニメーション
    animateResults() {
        if (!this.elements.resultsContainer) return;
        
        const cards = this.elements.resultsContainer.querySelectorAll('.grant-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = `all ${this.config.ui.animationDuration}ms ease`;
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    // ローディング表示
    showLoadingState() {
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.classList.remove('hidden');
        }
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.style.opacity = '0.5';
        }
    }

    // ローディング非表示
    hideLoadingState() {
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.classList.add('hidden');
        }
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.style.opacity = '1';
        }
    }

    // エラー表示
    showError(message) {
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.innerHTML = `
                <div class="error-message">
                    <p>${message}</p>
                </div>
            `;
        }
    }

    // 状態保存
    saveState(params) {
        try {
            sessionStorage.setItem('gi_search_state', JSON.stringify(params));
        } catch (e) {
            this.debugLog('Failed to save state:', e);
        }
    }

    // 状態復元
    restoreState() {
        try {
            const savedState = sessionStorage.getItem('gi_search_state');
            if (savedState) {
                const params = JSON.parse(savedState);
                // 保存された状態をフォームに適用
                this.applyStateToForm(params);
            }
        } catch (e) {
            this.debugLog('Failed to restore state:', e);
        }
    }

    // フォームに状態適用
    applyStateToForm(params) {
        // 検索キーワード
        if (params.search && this.elements.searchInputs.length > 0) {
            this.elements.searchInputs[0].value = params.search;
        }
        
        // フィルター適用
        Object.keys(params).forEach(key => {
            if (this.elements.filters[key]) {
                this.setFilterValue(key, params[key]);
            }
        });
        
        // ソート順
        if (params.orderby && this.elements.sortSelect) {
            this.elements.sortSelect.value = params.orderby;
        }
        
        // 表示件数
        if (params.posts_per_page && this.elements.perPageSelect) {
            this.elements.perPageSelect.value = params.posts_per_page;
        }
    }

    // フィルター値設定
    setFilterValue(filterType, value) {
        const elements = this.elements.filters[filterType];
        if (!elements || elements.length === 0) return;
        
        elements.forEach(element => {
            if (element.type === 'checkbox' || element.type === 'radio') {
                element.checked = value.includes(element.value);
            } else if (element.tagName === 'SELECT') {
                element.value = value;
            } else {
                element.value = value;
            }
        });
    }

    // デバッグログ
    debugLog(message, data = null) {
        if (window.gi_ajax?.debug) {
            console.log(`[GIUnifiedSearchManager] ${message}`, data);
        }
    }

    // パブリックメソッド：検索実行
    search(params = {}) {
        return this.executeUnifiedSearch(params);
    }

    // パブリックメソッド：リセット
    reset() {
        // フォームリセット
        this.elements.searchInputs.forEach(input => input.value = '');
        Object.values(this.elements.filters).forEach(filterElements => {
            filterElements.forEach(element => {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.checked = false;
                } else {
                    element.value = '';
                }
            });
        });
        
        // 検索実行
        this.executeUnifiedSearch();
    }

    // パブリックメソッド：フィルター更新
    updateFilter(filterType, value) {
        this.setFilterValue(filterType, value);
        this.executeUnifiedSearch();
    }
}

// 初期化
document.addEventListener('DOMContentLoaded', () => {
    window.GIUnifiedSearchManager = new GIUnifiedSearchManager();
    window.GIUnifiedSearchManager.init();
    
    // グローバルエイリアス
    window.GISearchManager = window.GIUnifiedSearchManager;
});