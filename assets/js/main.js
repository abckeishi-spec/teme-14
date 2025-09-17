// GIEnhanced 名前空間の作成（グローバルスコープ汚染を防ぐ）
const GIEnhanced = {
    // 設定オブジェクト
    config: {
        debounceDelay: 300,
        toastDuration: 3000,
        scrollTrackingInterval: 250
    },

    // 初期化フラグ
    initialized: false,

    // ユーティリティ関数群
    utils: {
        // HTMLエスケープ関数
        escapeHtml: function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        },

        // デバウンス関数
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // トースト通知関数
        showToast: function(message, type = 'info') {
            // 既存のtoast要素を削除
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }
            
            const toast = document.createElement('div');
            toast.className = `toast-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            
            // アイコンを追加
            const icon = document.createElement('span');
            icon.className = 'inline-block mr-2';
            icon.innerHTML = 
                type === 'success' ? '✓' :
                type === 'error' ? '✕' :
                type === 'warning' ? '⚠' :
                'ℹ';
            
            toast.appendChild(icon);
            toast.appendChild(document.createTextNode(message));
            
            document.body.appendChild(toast);
            
            // アニメーション
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 10);
            
            // 自動削除
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, GIEnhanced.config.toastDuration);
        },

        // メールアドレス検証
        isValidEmail: function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        // 電話番号検証
        isValidPhone: function(phone) {
            const phoneRegex = /^[\d\-\(\)\+\s]+$/;
            return phoneRegex.test(phone) && phone.replace(/\D/g, '').length >= 10;
        },

        // URL検証
        isValidUrl: function(url) {
            try {
                new URL(url);
                return true;
            } catch {
                return false;
            }
        },

        // 数値フォーマット（カンマ区切り）
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },

        // 日付フォーマット
        formatDate: function(date, format = 'YYYY-MM-DD') {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            
            return format
                .replace('YYYY', year)
                .replace('MM', month)
                .replace('DD', day);
        },

        // 文字列切り詰め
        truncateText: function(text, maxLength, suffix = '...') {
            if (text.length <= maxLength) {
                return text;
            }
            return text.substr(0, maxLength) + suffix;
        },

        // スクロール位置取得
        getScrollPosition: function() {
            return {
                x: window.pageXOffset || document.documentElement.scrollLeft,
                y: window.pageYOffset || document.documentElement.scrollTop
            };
        },

        // 要素が表示領域内にあるかチェック
        isElementInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    },

    // ストレージヘルパー
    storage: {
        set: function(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
                return true;
            } catch (e) {
                console.error('localStorage set error:', e);
                return false;
            }
        },
        
        get: function(key, defaultValue = null) {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : defaultValue;
            } catch (e) {
                console.error('localStorage get error:', e);
                return defaultValue;
            }
        },
        
        remove: function(key) {
            try {
                localStorage.removeItem(key);
                return true;
            } catch (e) {
                console.error('localStorage remove error:', e);
                return false;
            }
        }
    },

    // クッキーヘルパー
    cookie: {
        set: function(name, value, days = 7) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
        },
        
        get: function(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        
        remove: function(name) {
            document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
        }
    },

    // AJAX設定の確認とフォールバック
    ajax: {
        isAvailable: function() {
            return typeof window.gi_ajax !== 'undefined' && 
                   window.gi_ajax.ajax_url && 
                   window.gi_ajax.nonce;
        },

        getConfig: function() {
            if (this.isAvailable()) {
                return window.gi_ajax;
            }
            
            // フォールバック設定
            console.warn('gi_ajax is not defined. Using fallback configuration.');
            return {
                ajax_url: '/wp-admin/admin-ajax.php',
                nonce: '',
                fallback: true
            };
        },

        request: function(action, data = {}) {
            const config = this.getConfig();
            
            if (config.fallback) {
                console.error('AJAX request failed: gi_ajax configuration is missing.');
                GIEnhanced.utils.showToast('システムエラーが発生しました。ページを再読み込みしてください。', 'error');
                return Promise.reject('AJAX configuration missing');
            }

            const formData = new URLSearchParams({
                action: action,
                nonce: config.nonce,
                ...data
            });

            return fetch(config.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData
            })
            .then(response => response.json())
            .catch(error => {
                console.error('AJAX request error:', error);
                GIEnhanced.utils.showToast('通信エラーが発生しました', 'error');
                throw error;
            });
        }
    },

    // メイン初期化メソッド
    init: function() {
        if (this.initialized) {
            return;
        }

        // AJAX設定の確認
        if (!this.ajax.isAvailable()) {
            console.warn('gi_ajax object not found. Some features may not work properly.');
        }

        this.initializeEventListeners();
        this.initializeComponents();
        this.initializeTracking();
        this.initialized = true;
    },

    // イベントリスナーの初期化
    initializeEventListeners: function() {
        // ハンバーガーメニューの制御
        const hamburger = document.querySelector('.hamburger');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (hamburger && mobileMenu) {
            hamburger.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                
                // アイコンの切り替え
                const bars = hamburger.querySelector('.hamburger-bars');
                const close = hamburger.querySelector('.hamburger-close');
                
                if (bars && close) {
                    bars.classList.toggle('hidden');
                    close.classList.toggle('hidden');
                }
            });
        }

        // モバイルメニューの外側クリックで閉じる
        document.addEventListener('click', function(e) {
            if (mobileMenu && !mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    
                    const bars = hamburger.querySelector('.hamburger-bars');
                    const close = hamburger.querySelector('.hamburger-close');
                    
                    if (bars && close) {
                        bars.classList.remove('hidden');
                        close.classList.add('hidden');
                    }
                }
            }
        });

        // アフィリエイトリンクのトラッキング
        document.querySelectorAll('a[href*="affiliate"], a[href*="amazon"], a[href*="rakuten"]').forEach(link => {
            link.addEventListener('click', function() {
                // Google Analytics等のトラッキングコード
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click', {
                        'event_category': 'affiliate',
                        'event_label': this.href
                    });
                }
            });
        });

        // スムーズスクロール
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // 検索機能の拡張
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('input', GIEnhanced.utils.debounce(function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    GIEnhanced.performAdvancedSearch(query);
                } else if (query.length === 0) {
                    GIEnhanced.clearSearchResults();
                }
            }, GIEnhanced.config.debounceDelay));

            // 検索フォームのsubmitイベント
            const searchForm = searchInput.closest('form');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const query = searchInput.value.trim();
                    if (query.length >= 1) {
                        GIEnhanced.performAdvancedSearch(query);
                    }
                });
            }
        }

        // CTAボタンの最適化
        document.querySelectorAll('.cta-button').forEach(button => {
            button.addEventListener('click', function(e) {
                // クリック追跡
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click', {
                        'event_category': 'cta',
                        'event_label': this.textContent.trim()
                    });
                }

                // ボタンのクリックエフェクト
                this.classList.add('clicked');
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 200);
            });
        });

        // フォームの強化
        document.querySelectorAll('form').forEach(form => {
            // フォーム送信時のバリデーション
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidField = null;
                
                requiredFields.forEach(field => {
                    const value = field.value.trim();
                    
                    if (!value) {
                        field.classList.add('border-red-500', 'bg-red-50');
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else {
                        field.classList.remove('border-red-500', 'bg-red-50');
                        
                        // メールフィールドの追加バリデーション
                        if (field.type === 'email' && value && !GIEnhanced.utils.isValidEmail(value)) {
                            field.classList.add('border-red-500', 'bg-red-50');
                            isValid = false;
                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    GIEnhanced.utils.showToast('入力内容に不備があります。赤くなっている項目をご確認ください。', 'error');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    // フォーム送信成功の追跡
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'form_submit', {
                            'event_category': 'engagement',
                            'event_label': form.action || 'unknown'
                        });
                    }
                }
            });

            // リアルタイムバリデーション
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    GIEnhanced.validateField(this);
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500')) {
                        GIEnhanced.validateField(this);
                    }
                });
            });
        });

        // 外部リンクの処理
        document.querySelectorAll('a[href^="http"]').forEach(link => {
            if (!link.href.includes(window.location.hostname)) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
                
                // 外部リンククリックの追跡
                link.addEventListener('click', function() {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'click', {
                            'event_category': 'external_link',
                            'event_label': this.href
                        });
                    }
                });
            }
        });

        // カード要素のホバーエフェクト
        document.querySelectorAll('.hover-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    },

    // 画像の遅延読み込み初期化
    initializeLazyLoading: function() {
        const images = document.querySelectorAll('img[data-src]');
        if (images.length > 0) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            images.forEach(img => imageObserver.observe(img));
        }
    },

    // UIコンポーネントの初期化
    initializeComponents: function() {
        // 遅延読み込みの初期化
        this.initializeLazyLoading();

        // タブ機能
        document.querySelectorAll('[data-tab-target]').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.tabTarget;
                const target = document.querySelector(targetId);
                const container = this.closest('[data-tab-container]');
                
                if (container && target) {
                    // アクティブタブの切り替え
                    container.querySelectorAll('[data-tab-target]').forEach(t => {
                        t.classList.remove('active', 'bg-blue-500', 'text-white');
                        t.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    
                    container.querySelectorAll('[data-tab-content]').forEach(c => {
                        c.classList.add('hidden');
                    });
                    
                    this.classList.add('active', 'bg-blue-500', 'text-white');
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    target.classList.remove('hidden');

                    // タブ切り替えの追跡
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'tab_switch', {
                            'event_category': 'ui_interaction',
                            'event_label': targetId
                        });
                    }
                }
            });
        });

        // アコーディオン機能
        document.querySelectorAll('[data-accordion-trigger]').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.accordionTrigger;
                const target = document.querySelector(targetId);
                
                if (target) {
                    const isOpen = !target.classList.contains('hidden');
                    
                    if (isOpen) {
                        target.classList.add('hidden');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        target.classList.remove('hidden');
                        this.setAttribute('aria-expanded', 'true');
                    }
                    
                    // アイコンの回転
                    const icon = this.querySelector('.accordion-icon');
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }

                    // アコーディオン操作の追跡
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'accordion_toggle', {
                            'event_category': 'ui_interaction',
                            'event_label': targetId,
                            'value': isOpen ? 0 : 1
                        });
                    }
                }
            });
        });

        // モーダル機能
        document.querySelectorAll('[data-modal-trigger]').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.dataset.modalTrigger;
                const modal = document.querySelector(modalId);
                
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    
                    // フォーカス管理
                    const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    if (focusableElements.length > 0) {
                        focusableElements[0].focus();
                    }

                    // モーダル開示の追跡
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'modal_open', {
                            'event_category': 'ui_interaction',
                            'event_label': modalId
                        });
                    }
                }
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const modal = this.closest('[data-modal]');
                
                if (modal) {
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = 'auto';

                    // モーダル閉鎖の追跡
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'modal_close', {
                            'event_category': 'ui_interaction',
                            'event_label': modal.id || 'unknown'
                        });
                    }
                }
            });
        });

        // モーダルの背景クリックで閉じる
        document.querySelectorAll('[data-modal]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    const closeBtn = this.querySelector('[data-modal-close]');
                    if (closeBtn) {
                        closeBtn.click();
                    }
                }
            });
        });

        // ドロップダウン機能
        document.querySelectorAll('[data-dropdown-trigger]').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdownId = this.dataset.dropdownTrigger;
                const dropdown = document.querySelector(dropdownId);
                
                if (dropdown) {
                    // 他のドロップダウンを閉じる
                    document.querySelectorAll('[data-dropdown]').forEach(dd => {
                        if (dd !== dropdown) {
                            dd.classList.add('hidden');
                        }
                    });
                    
                    dropdown.classList.toggle('hidden');
                }
            });
        });

        // ドロップダウンの外側クリックで閉じる
        document.addEventListener('click', function() {
            document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        });

        // ツールチップ機能
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            let tooltip = null;
            
            element.addEventListener('mouseenter', function() {
                const text = this.dataset.tooltip;
                if (text) {
                    tooltip = document.createElement('div');
                    tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg';
                    tooltip.textContent = text;
                    tooltip.style.top = '-40px';
                    tooltip.style.left = '50%';
                    tooltip.style.transform = 'translateX(-50%)';
                    
                    this.style.position = 'relative';
                    this.appendChild(tooltip);
                }
            });
            
            element.addEventListener('mouseleave', function() {
                if (tooltip) {
                    tooltip.remove();
                    tooltip = null;
                }
            });
        });
    },

    // トラッキング機能の初期化
    initializeTracking: function() {
        // ユーザーエンゲージメント追跡
        let scrollDepth = 0;
        let maxScroll = 0;
        
        window.addEventListener('scroll', GIEnhanced.utils.debounce(function() {
            const currentScroll = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
            
            if (currentScroll > maxScroll) {
                maxScroll = currentScroll;
                
                // 25%刻みでトラッキング
                if (currentScroll > scrollDepth && currentScroll >= 25 && currentScroll % 25 === 0) {
                    scrollDepth = currentScroll;
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll', {
                            'event_category': 'engagement',
                            'event_label': scrollDepth + '%'
                        });
                    }
                }
            }
        }, GIEnhanced.config.scrollTrackingInterval));

        // ページ滞在時間の追跡
        let startTime = Date.now();
        let engaged = false;
        
        // エンゲージメントイベント（スクロール、クリック、キー入力）
        const engagementEvents = ['scroll', 'click', 'keydown', 'mousemove'];
        engagementEvents.forEach(event => {
            document.addEventListener(event, function() {
                if (!engaged) {
                    engaged = true;
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'engagement_start', {
                            'event_category': 'user_behavior'
                        });
                    }
                }
            }, { once: true });
        });

        // ページ離脱時の追跡
        window.addEventListener('beforeunload', function() {
            const timeSpent = Math.round((Date.now() - startTime) / 1000);
            if (typeof gtag !== 'undefined' && timeSpent > 10) {
                gtag('event', 'page_view_duration', {
                    'event_category': 'engagement',
                    'event_label': timeSpent + 's',
                    'value': timeSpent
                });
            }
        });

        // 読み込み完了後の最適化
        window.addEventListener('load', function() {
            // 画像の最適化
            GIEnhanced.optimizeImages();
            
            // パフォーマンス測定
            if (window.performance && window.performance.timing) {
                const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
                if (typeof gtag !== 'undefined' && loadTime > 0) {
                    gtag('event', 'timing_complete', {
                        'name': 'load',
                        'value': loadTime
                    });
                }
            }
        });
    },

    // 高度な検索機能（AJAX依存関係の解決）
    performAdvancedSearch: function(query) {
        const searchResults = document.querySelector('.search-results');
        
        if (searchResults) {
            searchResults.innerHTML = '<div class="text-center py-4">検索中...</div>';
            
            // AJAX設定が利用可能な場合のみ検索実行
            if (this.ajax.isAvailable()) {
                this.ajax.request('gi_ajax_search', { query: query })
                    .then(data => {
                        if (data.success) {
                            searchResults.innerHTML = data.data.html;
                        } else {
                            searchResults.innerHTML = '<div class="text-center py-4 text-red-500">検索エラーが発生しました。</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="text-center py-4 text-red-500">通信エラーが発生しました。</div>';
                    });
            } else {
                // フォールバック: 簡単なクライアントサイド検索
                searchResults.innerHTML = '<div class="text-center py-4 text-yellow-600">検索機能は現在利用できません。</div>';
                console.warn('Search functionality requires AJAX configuration.');
            }
        }
    },

    // 検索結果のクリア
    clearSearchResults: function() {
        const searchResults = document.querySelector('.search-results');
        if (searchResults) {
            searchResults.innerHTML = '';
        }
    },

    // フィールドバリデーション
    validateField: function(field) {
        const value = field.value.trim();
        let isValid = true;
        
        // 必須フィールドチェック
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        }
        
        // メールフィールドチェック
        if (field.type === 'email' && value && !this.utils.isValidEmail(value)) {
            isValid = false;
        }
        
        // 電話番号フィールドチェック
        if (field.type === 'tel' && value && !this.utils.isValidPhone(value)) {
            isValid = false;
        }
        
        // URLフィールドチェック
        if (field.type === 'url' && value && !this.utils.isValidUrl(value)) {
            isValid = false;
        }
        
        if (isValid) {
            field.classList.remove('border-red-500', 'bg-red-50');
            field.classList.add('border-green-500', 'bg-green-50');
        } else {
            field.classList.remove('border-green-500', 'bg-green-50');
            field.classList.add('border-red-500', 'bg-red-50');
        }
        
        return isValid;
    },

    // 画像最適化
    optimizeImages: function() {
        const images = document.querySelectorAll('img');
        
        images.forEach(img => {
            // 画像ロードエラーハンドリング
            img.addEventListener('error', function() {
                this.style.display = 'none';
                
                // プレースホルダー画像の表示
                const placeholder = document.createElement('div');
                placeholder.className = 'bg-gray-200 flex items-center justify-center text-gray-500';
                placeholder.style.width = this.style.width || '100%';
                placeholder.style.height = this.style.height || '200px';
                placeholder.innerHTML = '<span>画像を読み込めませんでした</span>';
                
                this.parentNode.insertBefore(placeholder, this.nextSibling);
            });
            
            // 画像ロード完了時の処理
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        });
    },

    // パフォーマンス監視
    measurePerformance: function(name, func) {
        const start = performance.now();
        const result = func();
        const end = performance.now();
        
        console.log(`${name} took ${end - start} milliseconds`);
        
        if (typeof gtag !== 'undefined') {
            gtag('event', 'timing_complete', {
                'name': name,
                'value': Math.round(end - start)
            });
        }
        
        return result;
    }
};

// DOM読み込み完了時に初期化
document.addEventListener('DOMContentLoaded', function() {
    GIEnhanced.init();
});

// エラーハンドリング
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'exception', {
            'description': e.error.message,
            'fatal': false
        });
    }
});

// 未処理のPromise拒否をキャッチ
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled Promise Rejection:', e.reason);
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'exception', {
            'description': 'Unhandled Promise: ' + e.reason,
            'fatal': false
        });
    }
});

// グローバルアクセス用（後方互換性のため）
// 他のスクリプトからアクセスする場合は GIEnhanced.utils.showToast() のように使用
window.GIEnhanced = GIEnhanced;

// 後方互換性のためのエイリアス（必要に応じて）
window.showToast = GIEnhanced.utils.showToast;
window.debounce = GIEnhanced.utils.debounce;
window.escapeHtml = GIEnhanced.utils.escapeHtml;
