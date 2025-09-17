/**
 * Mobile Menu Manager
 * Version: 4.0.0
 * 
 * モバイルメニューとレスポンシブナビゲーションを管理
 */

(function($) {
    'use strict';

    const MobileMenuManager = {
        // 設定
        config: {
            menuToggle: '.mobile-menu-toggle',
            mainNav: '.main-navigation',
            mobileBreakpoint: 768,
            animationSpeed: 300,
            overlayClass: 'mobile-menu-overlay',
            activeClass: 'active',
            openClass: 'menu-open'
        },

        // 状態
        state: {
            isOpen: false,
            isAnimating: false,
            isMobile: false
        },

        // 初期化
        init() {
            this.checkMobile();
            this.bindEvents();
            this.createOverlay();
            console.log('MobileMenuManager initialized');
        },

        // イベントバインド
        bindEvents() {
            // メニュートグル
            $(document).on('click', this.config.menuToggle, (e) => {
                e.preventDefault();
                this.toggleMenu();
            });

            // オーバーレイクリック
            $(document).on('click', '.' + this.config.overlayClass, () => {
                this.closeMenu();
            });

            // ESCキー
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.state.isOpen) {
                    this.closeMenu();
                }
            });

            // リサイズ
            let resizeTimer;
            $(window).on('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    this.checkMobile();
                    if (!this.state.isMobile && this.state.isOpen) {
                        this.closeMenu();
                    }
                }, 250);
            });

            // メニュー内のリンククリック
            $(document).on('click', this.config.mainNav + ' a', (e) => {
                // アンカーリンクの場合
                const href = $(e.currentTarget).attr('href');
                if (href && href.startsWith('#')) {
                    this.closeMenu();
                }
            });

            // サブメニューの展開
            $(document).on('click', this.config.mainNav + ' .has-submenu > a', function(e) {
                if ($(window).width() <= this.config.mobileBreakpoint) {
                    e.preventDefault();
                    const $submenu = $(this).next('.submenu');
                    $submenu.slideToggle(this.config.animationSpeed);
                    $(this).parent().toggleClass('submenu-open');
                }
            }.bind(this));
        },

        // モバイルチェック
        checkMobile() {
            this.state.isMobile = window.innerWidth <= this.config.mobileBreakpoint;
            
            if (this.state.isMobile) {
                this.setupMobileMenu();
            } else {
                this.teardownMobileMenu();
            }
        },

        // モバイルメニュー設定
        setupMobileMenu() {
            const $nav = $(this.config.mainNav);
            
            // モバイル用のクラス追加
            $nav.addClass('mobile-navigation');
            
            // アクセシビリティ属性
            $nav.attr({
                'role': 'navigation',
                'aria-label': 'モバイルメニュー'
            });

            $(this.config.menuToggle).attr({
                'aria-expanded': 'false',
                'aria-controls': 'mobile-navigation',
                'aria-label': 'メニューを開く'
            });
        },

        // モバイルメニュー解除
        teardownMobileMenu() {
            const $nav = $(this.config.mainNav);
            
            // モバイル用のクラス削除
            $nav.removeClass('mobile-navigation');
            $nav.removeClass(this.config.activeClass);
            
            // インラインスタイルをリセット
            $nav.css('display', '');
            
            // bodyクラス削除
            $('body').removeClass(this.config.openClass);
        },

        // オーバーレイ作成
        createOverlay() {
            if ($('.' + this.config.overlayClass).length === 0) {
                const $overlay = $('<div>', {
                    class: this.config.overlayClass,
                    'aria-hidden': 'true'
                });
                $('body').append($overlay);
            }
        },

        // メニュー切り替え
        toggleMenu() {
            if (this.state.isAnimating) return;

            if (this.state.isOpen) {
                this.closeMenu();
            } else {
                this.openMenu();
            }
        },

        // メニュー開く
        openMenu() {
            if (this.state.isOpen || this.state.isAnimating) return;

            this.state.isAnimating = true;
            
            const $toggle = $(this.config.menuToggle);
            const $nav = $(this.config.mainNav);
            const $overlay = $('.' + this.config.overlayClass);

            // メニューを表示
            $nav.addClass(this.config.activeClass);
            $toggle.addClass(this.config.activeClass);
            $overlay.addClass(this.config.activeClass);
            $('body').addClass(this.config.openClass);

            // アニメーション
            $nav.fadeIn(this.config.animationSpeed, () => {
                this.state.isAnimating = false;
                this.state.isOpen = true;
            });

            // アクセシビリティ
            $toggle.attr({
                'aria-expanded': 'true',
                'aria-label': 'メニューを閉じる'
            });

            // フォーカストラップ
            this.trapFocus($nav);
        },

        // メニュー閉じる
        closeMenu() {
            if (!this.state.isOpen || this.state.isAnimating) return;

            this.state.isAnimating = true;
            
            const $toggle = $(this.config.menuToggle);
            const $nav = $(this.config.mainNav);
            const $overlay = $('.' + this.config.overlayClass);

            // アニメーション
            $nav.fadeOut(this.config.animationSpeed, () => {
                $nav.removeClass(this.config.activeClass);
                $toggle.removeClass(this.config.activeClass);
                $overlay.removeClass(this.config.activeClass);
                $('body').removeClass(this.config.openClass);
                
                this.state.isAnimating = false;
                this.state.isOpen = false;
            });

            // アクセシビリティ
            $toggle.attr({
                'aria-expanded': 'false',
                'aria-label': 'メニューを開く'
            });

            // フォーカストラップ解除
            this.releaseFocus();
        },

        // フォーカストラップ
        trapFocus($element) {
            const focusableElements = $element.find('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstFocusable = focusableElements.first();
            const lastFocusable = focusableElements.last();

            // 最初の要素にフォーカス
            firstFocusable.focus();

            // Tab/Shift+Tabのハンドリング
            $(document).on('keydown.focustrap', (e) => {
                if (e.key !== 'Tab') return;

                if (e.shiftKey) {
                    // Shift + Tab
                    if ($(document.activeElement).is(firstFocusable)) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else {
                    // Tab
                    if ($(document.activeElement).is(lastFocusable)) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            });
        },

        // フォーカストラップ解除
        releaseFocus() {
            $(document).off('keydown.focustrap');
        },

        // スムーススクロール
        smoothScroll(target, offset = 0) {
            const $target = $(target);
            if ($target.length) {
                const position = $target.offset().top - offset;
                
                $('html, body').animate({
                    scrollTop: position
                }, this.config.animationSpeed);
            }
        },

        // パブリックメソッド：メニューを手動で開く
        open() {
            this.openMenu();
        },

        // パブリックメソッド：メニューを手動で閉じる
        close() {
            this.closeMenu();
        },

        // パブリックメソッド：メニュー状態を取得
        isOpen() {
            return this.state.isOpen;
        }
    };

    // DOMReady時に初期化
    $(document).ready(() => {
        MobileMenuManager.init();
        
        // グローバルに公開
        window.MobileMenuManager = MobileMenuManager;
    });

})(jQuery);

// CSS追加（動的）
(function() {
    const styles = `
        <style>
        /* モバイルメニューオーバーレイ */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mobile-menu-overlay.active {
            display: block;
            opacity: 1;
        }
        
        /* ボディのスクロール防止 */
        body.menu-open {
            overflow: hidden;
        }
        
        /* モバイルナビゲーション */
        @media (max-width: 768px) {
            .mobile-navigation {
                position: fixed;
                top: 60px;
                left: 0;
                width: 100%;
                max-width: 300px;
                height: calc(100vh - 60px);
                background: white;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
                z-index: 999;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .mobile-navigation.active {
                transform: translateX(0);
            }
            
            .mobile-navigation a {
                display: block;
                padding: 15px 20px;
                border-bottom: 1px solid #eee;
                color: #333;
                text-decoration: none;
                transition: background 0.2s ease;
            }
            
            .mobile-navigation a:hover {
                background: #f5f5f5;
            }
            
            /* サブメニュー */
            .mobile-navigation .submenu {
                display: none;
                background: #f9f9f9;
            }
            
            .mobile-navigation .submenu a {
                padding-left: 40px;
                font-size: 14px;
            }
            
            .mobile-navigation .has-submenu > a::after {
                content: '+';
                float: right;
                font-size: 18px;
                transition: transform 0.3s ease;
            }
            
            .mobile-navigation .has-submenu.submenu-open > a::after {
                transform: rotate(45deg);
            }
        }
        
        /* メニューボタンアニメーション */
        .mobile-menu-toggle {
            position: relative;
            width: 30px;
            height: 24px;
        }
        
        .mobile-menu-toggle span {
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background: #333;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-toggle span:nth-child(1) {
            top: 0;
        }
        
        .mobile-menu-toggle span:nth-child(2) {
            top: 50%;
            transform: translateY(-50%);
        }
        
        .mobile-menu-toggle span:nth-child(3) {
            bottom: 0;
        }
        
        .mobile-menu-toggle.active span:nth-child(1) {
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
        }
        
        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle.active span:nth-child(3) {
            bottom: 50%;
            transform: translateY(50%) rotate(-45deg);
        }
        </style>
    `;
    
    // DOMに追加
    document.head.insertAdjacentHTML('beforeend', styles);
})();