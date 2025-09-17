<?php
/**
 * Template Name: 固定ページテンプレート
 * Tailwind CSS Play CDN対応版
 */

get_header(); ?>

<script src="https://cdn.tailwindcss.com/3.4.0"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#f0f9ff',
                        100: '#e0f2fe',
                        500: '#0ea5e9',
                        600: '#0284c7',
                        700: '#0369a1',
                        900: '#0c4a6e'
                    },
                    accent: {
                        500: '#f59e0b',
                        600: '#d97706'
                    }
                },
                animation: {
                    'fade-in': 'fadeIn 0.6s ease-out',
                    'slide-up': 'slideUp 0.8s ease-out',
                    'bounce-gentle': 'bounceGentle 1s ease-out',
                    'scale-in': 'scaleIn 0.5s ease-out'
                },
                keyframes: {
                    fadeIn: {
                        '0%': { opacity: '0' },
                        '100%': { opacity: '1' }
                    },
                    slideUp: {
                        '0%': { opacity: '0', transform: 'translateY(30px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    bounceGentle: {
                        '0%': { transform: 'scale(0.95)' },
                        '50%': { transform: 'scale(1.02)' },
                        '100%': { transform: 'scale(1)' }
                    },
                    scaleIn: {
                        '0%': { opacity: '0', transform: 'scale(0.9)' },
                        '100%': { opacity: '1', transform: 'scale(1)' }
                    }
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('animate-fade-in'); ?>>
                
                <!-- ヘッダーセクション -->
                <header class="bg-white border-b border-gray-100 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                        <div class="text-center animate-slide-up">
                            <?php if (get_the_title()) : ?>
                                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 
                                         bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                                    <?php the_title(); ?>
                                </h1>
                            <?php endif; ?>
                            
                            <?php if (has_excerpt()) : ?>
                                <div class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <!-- メインコンテンツ -->
                <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-scale-in">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="relative h-64 md:h-80 bg-gradient-to-r from-primary-500 to-primary-700 overflow-hidden">
                                <?php 
                                $thumbnail_id = get_post_thumbnail_id();
                                $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                                ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" 
                                     alt="<?php echo esc_attr($thumbnail_alt); ?>"
                                     class="w-full h-full object-cover opacity-90 hover:opacity-100 transition-opacity duration-300"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            </div>
                        <?php endif; ?>

                        <div class="p-8 md:p-12">
                            <!-- メタ情報 -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-8 pb-6 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>更新日: <?php echo get_the_modified_date('Y年n月j日'); ?></span>
                                </div>
                                
                                <?php if (get_the_author()) : ?>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        <span>作成者: <?php the_author(); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- コンテンツ -->
                            <div class="prose prose-lg max-w-none prose-headings:text-gray-900 
                                      prose-p:text-gray-700 prose-p:leading-relaxed
                                      prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline
                                      prose-strong:text-gray-900 prose-ul:text-gray-700 prose-ol:text-gray-700
                                      prose-blockquote:border-primary-500 prose-blockquote:bg-primary-50 
                                      prose-blockquote:rounded-lg prose-blockquote:p-4">
                                <?php
                                the_content();
                                wp_link_pages(array(
                                    'before' => '<div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-gray-200">',
                                    'after'  => '</div>',
                                    'link_before' => '<span class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition-colors duration-200">',
                                    'link_after' => '</span>',
                                ));
                                ?>
                            </div>

                            <!-- カスタムフィールド表示 -->
                            <?php 
                            $custom_fields = get_fields();
                            if ($custom_fields) : 
                            ?>
                                <div class="mt-12 pt-8 border-t border-gray-200">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                        <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        詳細情報
                                    </h2>
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <?php foreach ($custom_fields as $key => $value) : ?>
                                            <?php if ($value && !is_array($value)) : ?>
                                                <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors duration-200">
                                                    <dt class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">
                                                        <?php echo esc_html(str_replace('_', ' ', $key)); ?>
                                                    </dt>
                                                    <dd class="text-gray-900 font-medium">
                                                        <?php echo wp_kses_post($value); ?>
                                                    </dd>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 関連ページ -->
                            <?php
                            $related_pages = get_pages(array(
                                'exclude' => get_the_ID(),
                                'number' => 3,
                                'sort_column' => 'menu_order',
                                'sort_order' => 'ASC'
                            ));
                            if ($related_pages) :
                            ?>
                                <div class="mt-12 pt-8 border-t border-gray-200">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                        <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                        </svg>
                                        関連ページ
                                    </h2>
                                    <div class="grid gap-6 md:grid-cols-3">
                                        <?php foreach ($related_pages as $page) : ?>
                                            <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" 
                                               class="group block bg-gradient-to-br from-white to-gray-50 border border-gray-200 
                                                      rounded-xl p-6 hover:shadow-lg hover:border-primary-300 
                                                      transform hover:-translate-y-1 transition-all duration-300">
                                                <h3 class="font-bold text-gray-900 group-hover:text-primary-600 
                                                         transition-colors duration-200 mb-2">
                                                    <?php echo esc_html($page->post_title); ?>
                                                </h3>
                                                <?php if ($page->post_excerpt) : ?>
                                                    <p class="text-gray-600 text-sm line-clamp-3">
                                                        <?php echo esc_html($page->post_excerpt); ?>
                                                    </p>
                                                <?php endif; ?>
                                                <div class="mt-4 flex items-center text-primary-600 text-sm font-medium">
                                                    <span>詳しく見る</span>
                                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- コメントセクション -->
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="mt-12">
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-12">
                                <?php comments_template(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </main>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center animate-fade-in">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">ページが見つかりません</h2>
                <p class="text-gray-600 mb-8">申し訳ございませんが、お探しのページは存在しません。</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium 
                          rounded-lg hover:bg-primary-700 transform hover:scale-105 
                          transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 001.414 1.414L8 5.414V17a1 1 0 102 0V5.414l6.293 6.293a1 1 0 001.414-1.414l-9-9z" />
                    </svg>
                    ホームに戻る
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // スムーズスクロール
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // 画像の遅延読み込みとフェードイン効果
    const images = document.querySelectorAll('img[loading="lazy"]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.style.opacity = '0';
                img.addEventListener('load', () => {
                    img.style.transition = 'opacity 0.5s ease-in-out';
                    img.style.opacity = '1';
                });
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // プログレッシブエンハンスメント
    const cards = document.querySelectorAll('[class*="hover:"]');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

<?php get_footer(); ?>
