/**
 * Legacy Bridge - 統合検索システム互換性ブリッジ
 * Grant Insight Perfect - Phase 4 Implementation
 * 
 * 既存のレガシーシステムと新統合システムの互換性を保つためのブリッジファイル
 * 
 * @version 4.0.0-bridge
 * @package Grant_Insight_Perfect
 * @author Grant Insight Development Team
 */

(function($) {
    'use strict';

    // レガシーシステム対応フラグ
    window.GI_LEGACY_BRIDGE_LOADED = true;

    /**
     * レガシー関数の互換性ブリッジ
     */
    const LegacyBridge = {
        
        /**
         * 初期化
         */
        init: function() {
            this.setupLegacySupport();
            this.registerLegacyHandlers();
            this.migrateExistingElements();
            this.setupDebugMode();
            
            console.log('[Legacy Bridge] 互換性ブリッジが初期化されました');
        },

        /**
         * レガシーサポートの設定
         */
        setupLegacySupport: function() {
            // レガシー関数のエイリアス作成
            if (typeof window.loadGrants === 'undefined') {
                window.loadGrants = function(params) {
                    console.log('[Legacy Bridge] loadGrants() → UnifiedSearchManager.search() にリダイレクト');
                    if (window.UnifiedSearchManager) {
                        return window.UnifiedSearchManager.search(params);
                    }
                };
            }

            // レガシーAJAX関数の互換性
            if (typeof window.gi_ajax_load_grants === 'undefined') {
                window.gi_ajax_load_grants = function(params) {
                    console.log('[Legacy Bridge] gi_ajax_load_grants() → UnifiedSearchManager.performSearch() にリダイレクト');
                    if (window.UnifiedSearchManager) {
                        return window.UnifiedSearchManager.performSearch(params);
                    }
                };
            }

            // レガシーフィルター関数
            if (typeof window.applyFilters === 'undefined') {
                window.applyFilters = function() {
                    console.log('[Legacy Bridge] applyFilters() → UnifiedSearchManager.handleFilterChange() にリダイレクト');
                    if (window.UnifiedSearchManager) {
                        return window.UnifiedSearchManager.handleFilterChange();
                    }
                };
            }

            // レガシー検索リセット
            if (typeof window.resetSearch === 'undefined') {
                window.resetSearch = function() {
                    console.log('[Legacy Bridge] resetSearch() → UnifiedSearchManager.reset() にリダイレクト');
                    if (window.UnifiedSearchManager) {
                        return window.UnifiedSearchManager.reset();
                    }
                };
            }
        },

        /**
         * レガシーイベントハンドラーの登録
         */
        registerLegacyHandlers: function() {
            const self = this;

            // レガシーDOM要素のイベント転送
            $(document).on('click', '#search-btn, #execute-search, #search-submit-btn', function(e) {
                console.log('[Legacy Bridge] レガシー検索ボタンクリックを統合システムに転送');
                if (window.UnifiedSearchManager) {
                    window.UnifiedSearchManager.handleSearch(e);
                }
            });

            // レガシー検索入力のイベント転送
            $(document).on('keyup input', '#grant-search, #search-keyword-input, #search-keyword', function(e) {
                console.log('[Legacy Bridge] レガシー検索入力を統合システムに転送');
                if (window.UnifiedSearchManager) {
                    window.UnifiedSearchManager.handleInput(e);
                }
            });

            // レガシーフィルター変更のイベント転送
            $(document).on('change', 'select[id*="filter-"], input[id*="filter-"]', function(e) {
                console.log('[Legacy Bridge] レガシーフィルター変更を統合システムに転送');
                if (window.UnifiedSearchManager) {
                    window.UnifiedSearchManager.handleFilterChange();
                }
            });

            // レガシーフォーム送信の防止と転送
            $(document).on('submit', '#grant-search-form, .search-form', function(e) {
                e.preventDefault();
                console.log('[Legacy Bridge] レガシーフォーム送信を統合システムに転送');
                if (window.UnifiedSearchManager) {
                    window.UnifiedSearchManager.handleFormSubmit(e);
                }
            });
        },

        /**
         * 既存要素のマイグレーション
         */
        migrateExistingElements: function() {
            // data-legacy-id属性の設定（まだ設定されていない場合）
            $('#grant-search').attr('data-legacy-id', 'grant-search').attr('data-migrated', 'true');
            $('#search-keyword-input').attr('data-legacy-id', 'search-keyword-input').attr('data-migrated', 'true');
            $('#search-keyword').attr('data-legacy-id', 'search-keyword').attr('data-migrated', 'true');
            
            $('#search-btn').attr('data-legacy-id', 'search-btn').attr('data-migrated', 'true');
            $('#execute-search').attr('data-legacy-id', 'execute-search').attr('data-migrated', 'true');
            $('#search-submit-btn').attr('data-legacy-id', 'search-submit-btn').attr('data-migrated', 'true');

            $('#grants-display').attr('data-legacy-id', 'grants-display').attr('data-migrated', 'true');
            $('#search-results-preview').attr('data-legacy-id', 'search-results-preview').attr('data-migrated', 'true');

            console.log('[Legacy Bridge] 既存要素のマイグレーション完了');
        },

        /**
         * 統合システム待機とフォールバック
         */
        waitForUnifiedSystem: function(callback, maxAttempts = 50, currentAttempt = 0) {
            if (window.UnifiedSearchManager && window.UnifiedSearchManager.isInitialized) {
                callback();
                return;
            }

            if (currentAttempt >= maxAttempts) {
                console.error('[Legacy Bridge] 統合システムの初期化を待機中にタイムアウト');
                this.enableFallbackMode();
                return;
            }

            setTimeout(() => {
                this.waitForUnifiedSystem(callback, maxAttempts, currentAttempt + 1);
            }, 100);
        },

        /**
         * フォールバックモードの有効化
         */
        enableFallbackMode: function() {
            console.warn('[Legacy Bridge] フォールバックモード有効化 - レガシーシステムで動作');
            
            // 基本的なレガシー機能の復元
            window.loadGrants = function(params) {
                // 最小限の検索機能
                $.post(gi_ajax.ajax_url, {
                    action: 'gi_unified_search_handler',
                    nonce: gi_ajax.nonce,
                    ...params
                }, function(response) {
                    if (response.success) {
                        $('#grants-display, #search-results-preview').html(response.data.html);
                    }
                });
            };

            $('body').addClass('gi-fallback-mode');
        },

        /**
         * デバッグモードの設定
         */
        setupDebugMode: function() {
            if (gi_ajax && gi_ajax.debug) {
                console.log('[Legacy Bridge] デバッグモード有効');
                
                // デバッグ用ウィンドウオブジェクト
                window.GILegacyBridge = {
                    version: '4.0.0-bridge',
                    checkCompatibility: this.checkCompatibility.bind(this),
                    listLegacyElements: this.listLegacyElements.bind(this),
                    testBridge: this.testBridge.bind(this),
                    // Phase 5: 音声検索・サジェスト機能のテスト
                    testVoiceSearch: this.testVoiceSearch.bind(this),
                    testSuggestions: this.testSuggestions.bind(this)
                };
            }
        },

        /**
         * 互換性チェック
         */
        checkCompatibility: function() {
            const issues = [];
            
            // 必要な要素の存在チェック
            const requiredElements = ['#grant-search', '#search-keyword-input', '#search-keyword'];
            requiredElements.forEach(selector => {
                if ($(selector).length === 0) {
                    issues.push(`要素が見つかりません: ${selector}`);
                }
            });

            // 統合システムの状態チェック
            if (!window.UnifiedSearchManager) {
                issues.push('UnifiedSearchManager が読み込まれていません');
            } else if (!window.UnifiedSearchManager.isInitialized) {
                issues.push('UnifiedSearchManager が初期化されていません');
            }

            return {
                compatible: issues.length === 0,
                issues: issues,
                timestamp: new Date().toISOString()
            };
        },

        /**
         * レガシー要素の一覧取得
         */
        listLegacyElements: function() {
            const elements = {
                inputs: [],
                buttons: [],
                containers: []
            };

            // レガシー検索入力
            $('#grant-search, #search-keyword-input, #search-keyword').each(function() {
                elements.inputs.push({
                    id: this.id,
                    exists: true,
                    migrated: $(this).attr('data-migrated') === 'true',
                    value: $(this).val()
                });
            });

            // レガシー検索ボタン
            $('#search-btn, #execute-search, #search-submit-btn').each(function() {
                elements.buttons.push({
                    id: this.id,
                    exists: true,
                    migrated: $(this).attr('data-migrated') === 'true',
                    enabled: !$(this).prop('disabled')
                });
            });

            // レガシー結果コンテナ
            $('#grants-display, #search-results-preview').each(function() {
                elements.containers.push({
                    id: this.id,
                    exists: true,
                    migrated: $(this).attr('data-migrated') === 'true',
                    hasContent: $(this).children().length > 0
                });
            });

            return elements;
        },

        /**
         * ブリッジ機能のテスト
         */
        testBridge: function() {
            console.log('[Legacy Bridge] ブリッジ機能テスト開始');
            
            const tests = [
                {
                    name: 'loadGrants関数テスト',
                    test: () => typeof window.loadGrants === 'function'
                },
                {
                    name: 'gi_ajax_load_grants関数テスト',
                    test: () => typeof window.gi_ajax_load_grants === 'function'
                },
                {
                    name: 'applyFilters関数テスト',
                    test: () => typeof window.applyFilters === 'function'
                },
                {
                    name: 'resetSearch関数テスト',
                    test: () => typeof window.resetSearch === 'function'
                },
                {
                    name: '統合システム連携テスト',
                    test: () => window.UnifiedSearchManager && window.UnifiedSearchManager.isInitialized
                }
            ];

            const results = tests.map(test => ({
                name: test.name,
                passed: test.test(),
                timestamp: new Date().toISOString()
            }));

            console.table(results);
            return results;
        },

        /**
         * 音声検索機能のテスト
         */
        testVoiceSearch: function() {
            console.log('[Legacy Bridge] 音声検索機能テスト開始');
            
            const tests = [
                {
                    name: 'Web Speech API対応',
                    test: () => 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window
                },
                {
                    name: '音声検索ボタン存在',
                    test: () => document.querySelector('#gi-voice-btn') !== null
                },
                {
                    name: 'UnifiedSearchManager音声機能',
                    test: () => window.UnifiedSearchManager && 
                              typeof window.UnifiedSearchManager.startVoiceSearch === 'function'
                },
                {
                    name: '音声認識オブジェクト初期化',
                    test: () => window.UnifiedSearchManager && 
                              window.UnifiedSearchManager.voiceRecognition !== null
                }
            ];

            const results = tests.map(test => ({
                name: test.name,
                passed: test.test(),
                timestamp: new Date().toISOString()
            }));

            console.table(results);
            
            // 実際の音声検索テスト（ユーザーの許可が必要）
            if (results.every(r => r.passed)) {
                console.log('[Voice Test] 全ての基本テストが通過しました');
                console.log('[Voice Test] 実際の音声検索をテストするには、音声検索ボタンをクリックしてください');
            }
            
            return results;
        },

        /**
         * サジェスト機能のテスト
         */
        testSuggestions: function() {
            console.log('[Legacy Bridge] サジェスト機能テスト開始');
            
            const tests = [
                {
                    name: 'サジェスト取得関数存在',
                    test: () => window.UnifiedSearchManager && 
                              typeof window.UnifiedSearchManager.fetchSuggestions === 'function'
                },
                {
                    name: 'サジェストコンテナ作成機能',
                    test: () => window.UnifiedSearchManager && 
                              typeof window.UnifiedSearchManager.createSuggestionContainer === 'function'
                },
                {
                    name: 'デバウンス関数設定',
                    test: () => window.UnifiedSearchManager && 
                              window.UnifiedSearchManager.debouncedSuggestion !== null
                },
                {
                    name: 'サジェスト履歴機能',
                    test: () => window.UnifiedSearchManager && 
                              Array.isArray(window.UnifiedSearchManager.suggestionHistory)
                }
            ];

            const results = tests.map(test => ({
                name: test.name,
                passed: test.test(),
                timestamp: new Date().toISOString()
            }));

            console.table(results);
            
            // 実際のサジェストテスト
            if (results.every(r => r.passed)) {
                console.log('[Suggestion Test] 全ての基本テストが通過しました');
                
                // テスト用サジェスト実行
                const searchInput = document.querySelector('#gi-search-input-unified');
                if (searchInput && window.UnifiedSearchManager) {
                    console.log('[Suggestion Test] テスト用サジェスト実行中...');
                    searchInput.value = 'テスト';
                    window.UnifiedSearchManager.debouncedSuggestion('テスト');
                    
                    setTimeout(() => {
                        const suggestionContainer = document.querySelector('.gi-suggestion-container');
                        console.log('[Suggestion Test] サジェストコンテナ:', suggestionContainer ? '表示' : '非表示');
                    }, 1000);
                }
            }
            
            return results;
        }
    };

    /**
     * DOM準備完了時の初期化
     */
    $(document).ready(function() {
        console.log('[Legacy Bridge] DOM準備完了 - 初期化開始');
        
        // 統合システムを待機してから初期化
        LegacyBridge.waitForUnifiedSystem(function() {
            LegacyBridge.init();
            
            // 統合完了イベント発火
            $(document).trigger('gi-legacy-bridge-ready');
            console.log('[Legacy Bridge] 初期化完了 - 統合システムとの連携確立');
        });
    });

    /**
     * ウィンドウロード時の最終チェック
     */
    $(window).on('load', function() {
        setTimeout(function() {
            if (gi_ajax && gi_ajax.debug) {
                const compatibility = LegacyBridge.checkCompatibility();
                console.log('[Legacy Bridge] 最終互換性チェック:', compatibility);
                
                if (!compatibility.compatible) {
                    console.warn('[Legacy Bridge] 互換性の問題が検出されました:', compatibility.issues);
                }
            }
        }, 1000);
    });

})(jQuery);