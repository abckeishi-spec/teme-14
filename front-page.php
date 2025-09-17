<?php
/**
 * Grant Insight Perfect - Front Page Template
 * テンプレートパーツを活用したシンプル構成
 * 
 * @package Grant_Insight_Perfect
 * @version 7.0-simple
 */

get_header(); ?>

<style>
/* フロントページ専用スタイル */
.site-main {
    padding: 0;
    background: #ffffff;
}

/* セクション間のスペーシング調整 */
.front-page-section {
    position: relative;
}

.front-page-section + .front-page-section {
    margin-top: -1px; /* セクション間の隙間を削除 */
}

/* スムーススクロール */
html {
    scroll-behavior: smooth;
}

/* セクションアニメーション */
.section-animate {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
}

.section-animate.visible {
    opacity: 1;
    transform: translateY(0);
}

/* モバイル最適化 */
@media (max-width: 768px) {
    .site-main {
        overflow-x: hidden;
    }
}
</style>

<main id="main" class="site-main" role="main">

    <?php
    /**
     * 1. Hero Section
     * メインビジュアルとキャッチコピー
     */
    ?>
    <section class="front-page-section section-animate" id="hero-section">
        <?php get_template_part('template-parts/front-page/section', 'hero'); ?>
    </section>

    <?php
    /**
     * 2. Search Section  
     * 助成金検索システム
     */
    ?>
    <section class="front-page-section section-animate" id="search-section">
        <?php get_template_part('template-parts/front-page/section', 'search'); ?>
    </section>

    <?php
    /**
     * 3. Categories Section
     * カテゴリ別ナビゲーション
     */
    ?>
    <section class="front-page-section section-animate" id="categories-section">
        <?php get_template_part('template-parts/front-page/section', 'categories'); ?>
    </section>

</main>

<!-- フローティングナビゲーション（モバイル用） -->
<nav class="floating-nav" id="floating-nav" style="display: none;">
    <div class="floating-nav-container">
        <button class="nav-item" data-target="#search-section">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
            </svg>
            <span>検索</span>
        </button>
        <button class="nav-item" data-target="#categories-section">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>カテゴリ</span>
        </button>
        <button class="nav-item" id="back-to-top">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>TOP</span>
        </button>
    </div>
</nav>

<!-- プログレスバー -->
<div class="scroll-progress" id="scroll-progress"></div>

<style>
/* フローティングナビゲーション */
.floating-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-top: 1px solid #e5e7eb;
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.floating-nav.visible {
    transform: translateY(0);
}

.floating-nav-container {
    display: flex;
    justify-content: space-around;
    padding: 8px 0;
    max-width: 480px;
    margin: 0 auto;
}

.nav-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 8px;
    background: none;
    border: none;
    color: #6b7280;
    font-size: 11px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.nav-item:active {
    transform: scale(0.95);
}

.nav-item svg {
    width: 24px;
    height: 24px;
}

.nav-item:hover,
.nav-item.active {
    color: #10b981;
}

/* スクロールプログレスバー */
.scroll-progress {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981, #3b82f6);
    z-index: 9999;
    transition: width 0.1s ease;
    width: 0%;
}

/* レスポンシブ */
@media (min-width: 769px) {
    .floating-nav {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .floating-nav {
        display: block !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // セクションアニメーション
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                sectionObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // 全セクションを監視
    document.querySelectorAll('.section-animate').forEach(section => {
        sectionObserver.observe(section);
    });
    
    // スクロールプログレスバー
    const progressBar = document.getElementById('scroll-progress');
    
    function updateProgressBar() {
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrolled = window.scrollY;
        const progress = (scrolled / scrollHeight) * 100;
        
        if (progressBar) {
            progressBar.style.width = Math.min(progress, 100) + '%';
        }
    }
    
    // フローティングナビゲーション（モバイル）
    const floatingNav = document.getElementById('floating-nav');
    let lastScrollY = 0;
    
    function handleFloatingNav() {
        const currentScrollY = window.scrollY;
        
        if (window.innerWidth <= 768 && floatingNav) {
            if (currentScrollY > 300) {
                // スクロール方向判定
                if (currentScrollY < lastScrollY) {
                    // 上スクロール時に表示
                    floatingNav.classList.add('visible');
                } else {
                    // 下スクロール時に非表示
                    floatingNav.classList.remove('visible');
                }
            } else {
                floatingNav.classList.remove('visible');
            }
        }
        
        lastScrollY = currentScrollY;
    }
    
    // ナビゲーションボタンのクリック処理
    document.querySelectorAll('.nav-item[data-target]').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // アクティブ状態の更新
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });
                this.classList.add('active');
            }
        });
    });
    
    // トップへ戻るボタン
    const backToTopBtn = document.getElementById('back-to-top');
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // スクロールイベント（最適化）
    let scrollTimer;
    window.addEventListener('scroll', function() {
        // デバウンス処理
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(() => {
            updateProgressBar();
            handleFloatingNav();
        }, 10);
    });
    
    // 初期化
    updateProgressBar();
    
    // パフォーマンス監視
    if ('performance' in window) {
        window.addEventListener('load', function() {
            const perfData = performance.getEntriesByType('navigation')[0];
            if (perfData) {
                console.log('🚀 ページ読み込み時間:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
            }
        });
    }
    
    // ページ内リンクのスムーススクロール
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href !== '#' && href !== '#0') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offset = 80; // ヘッダーの高さ分調整
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
    
    // リサイズ時の処理
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // モバイルナビゲーションの表示/非表示
            if (window.innerWidth > 768 && floatingNav) {
                floatingNav.classList.remove('visible');
            }
        }, 250);
    });
    
    console.log('✅ Grant Insight Perfect - フロントページ初期化完了');
});
</script>

<?php get_footer(); ?>
