<?php
/**
 * Template for displaying single grant posts
 * 
 * @package Grant_Insight_Perfect
 * @version 6.2
 */

get_header(); ?>

<div class="min-h-screen bg-gray-50">
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- ヒーローセクション -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <!-- パンくずリスト -->
                    <nav class="text-sm mb-6 opacity-90">
                        <a href="<?php echo home_url(); ?>" class="hover:underline">ホーム</a>
                        <span class="mx-2">›</span>
                        <a href="<?php echo get_post_type_archive_link('grant'); ?>" class="hover:underline">助成金一覧</a>
                        <span class="mx-2">›</span>
                        <span class="text-blue-200"><?php the_title(); ?></span>
                    </nav>
                    
                    <!-- タイトル -->
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- メタ情報 -->
                    <div class="flex flex-wrap gap-4 mb-8">
                        <?php 
                        // Use canonical ACF helpers and taxonomies
                        $grant_amount = function_exists('gi_get_formatted_grant_amount') ? gi_get_formatted_grant_amount(get_the_ID()) : get_post_meta(get_the_ID(), 'max_amount', true);
                        $application_deadline = function_exists('gi_get_formatted_deadline') ? gi_get_formatted_deadline(get_the_ID()) : get_post_meta(get_the_ID(), 'deadline_text', true);
                        // Taxonomies
                        $grant_category_terms = get_the_terms(get_the_ID(), 'grant_category');
                        $grant_category = ($grant_category_terms && !is_wp_error($grant_category_terms)) ? $grant_category_terms[0]->name : '';
                        $prefecture_terms = get_the_terms(get_the_ID(), 'grant_prefecture');
                        $prefecture = ($prefecture_terms && !is_wp_error($prefecture_terms)) ? $prefecture_terms[0]->name : '';
                        ?>
                        
                        <?php if ($grant_amount): ?>
                        <div class="bg-white/20 px-4 py-2 rounded-full">
                            <span class="text-sm font-medium">💰 <?php echo esc_html($grant_amount); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($application_deadline): ?>
                        <div class="bg-white/20 px-4 py-2 rounded-full">
                            <span class="text-sm font-medium">📅 <?php echo esc_html($application_deadline); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($grant_category): ?>
                        <div class="bg-white/20 px-4 py-2 rounded-full">
                            <span class="text-sm font-medium">🏷️ <?php echo esc_html($grant_category); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($prefecture): ?>
                        <div class="bg-white/20 px-4 py-2 rounded-full">
                            <span class="text-sm font-medium">📍 <?php echo esc_html($prefecture); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="flex flex-wrap gap-4">
                        <button id="favorite-btn" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition-colors duration-200" data-post-id="<?php echo get_the_ID(); ?>">
                            <span id="favorite-icon">❤️</span>
                            <span id="favorite-text">お気に入りに追加</span>
                        </button>
                        
                        <?php 
                        $application_url = get_field('official_url');
                        if ($application_url): ?>
                        <a href="<?php echo esc_url($application_url); ?>" target="_blank" class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                            🚀 申請サイトへ
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- メインコンテンツ -->
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-3 gap-8">
                    
                    <!-- 左側：詳細情報 -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- 概要 -->
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                📋 助成金概要
                            </h2>
                            <div class="prose prose-lg max-w-none">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                        <!-- 詳細情報 -->
                        <?php 
                        $grant_details = get_field('grant_details');
                        if ($grant_details): ?>
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                📖 詳細情報
                            </h2>
                            <div class="prose prose-lg max-w-none">
                                <?php echo wp_kses_post($grant_details); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- 対象者・条件 -->
                        <?php 
                        $eligibility_criteria = get_field('eligibility_criteria');
                        if ($eligibility_criteria): ?>
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                👥 対象者・条件
                            </h2>
                            <div class="prose prose-lg max-w-none">
                                <?php echo wp_kses_post($eligibility_criteria); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- 申請方法 -->
                        <?php 
                        $application_process = get_field('application_process');
                        if ($application_process): ?>
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                📝 申請方法
                            </h2>
                            <div class="prose prose-lg max-w-none">
                                <?php echo wp_kses_post($application_process); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- 必要書類 -->
                        <?php 
                        $required_documents = get_field('required_documents');
                        if ($required_documents): ?>
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                📄 必要書類
                            </h2>
                            <div class="prose prose-lg max-w-none">
                                <?php echo wp_kses_post($required_documents); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- 右側：サイドバー -->
                    <div class="space-y-6">
                        
                        <!-- 重要情報カード -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6 border border-blue-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                ⚡ 重要情報
                            </h3>
                            <div class="space-y-4">
                                <?php if ($grant_amount): ?>
                                <div>
                                    <span class="text-sm text-gray-600 block">助成金額</span>
                                    <span class="text-lg font-bold text-blue-600"><?php echo esc_html($grant_amount); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($application_deadline): ?>
                                <div>
                                    <span class="text-sm text-gray-600 block">申請締切</span>
                                    <span class="text-lg font-bold text-red-600"><?php echo esc_html($application_deadline); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php 
                                $contact_info = get_field('contact_info');
                                if ($contact_info): ?>
                                <div>
                                    <span class="text-sm text-gray-600 block">お問い合わせ</span>
                                    <div class="text-sm mt-1">
                                        <?php echo wp_kses_post($contact_info); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- 関連リンク -->
                        <?php 
                        $related_links = get_field('related_links');
                        if ($related_links): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                🔗 関連リンク
                            </h3>
                            <div class="prose prose-sm max-w-none">
                                <?php echo wp_kses_post($related_links); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- 関連助成金 -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                🎯 関連助成金
                            </h3>
                            <div id="related-grants" class="space-y-4">
                                <!-- AJAXで読み込み -->
                            </div>
                        </div>
                        
                        <!-- シェアボタン -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                📢 シェア
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                    🐦 Twitter
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                    📘 Facebook
                                </a>
                                <button onclick="copyToClipboard('<?php echo get_permalink(); ?>')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                    📋 コピー
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    <?php endwhile; ?>
</div>

<!-- AJAX & JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // お気に入り機能
    const favoriteBtn = document.getElementById('favorite-btn');
    const favoriteIcon = document.getElementById('favorite-icon');
    const favoriteText = document.getElementById('favorite-text');
    
    if (favoriteBtn) {
        // 初期状態をチェック
        checkFavoriteStatus();
        
        favoriteBtn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            toggleFavorite(postId);
        });
    }
    
    // 関連助成金を読み込み
    loadRelatedGrants();
    
    function checkFavoriteStatus() {
        const postId = favoriteBtn.dataset.postId;
        const favorites = JSON.parse(localStorage.getItem('grant_favorites') || '[]');
        
        if (favorites.includes(postId)) {
            favoriteIcon.textContent = '💖';
            favoriteText.textContent = 'お気に入り済み';
            favoriteBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
            favoriteBtn.classList.add('bg-pink-500', 'hover:bg-pink-600');
        }
    }
    
    function toggleFavorite(postId) {
        let favorites = JSON.parse(localStorage.getItem('grant_favorites') || '[]');
        
        if (favorites.includes(postId)) {
            // 削除
            favorites = favorites.filter(id => id !== postId);
            favoriteIcon.textContent = '❤️';
            favoriteText.textContent = 'お気に入りに追加';
            favoriteBtn.classList.remove('bg-pink-500', 'hover:bg-pink-600');
            favoriteBtn.classList.add('bg-red-500', 'hover:bg-red-600');
        } else {
            // 追加
            favorites.push(postId);
            favoriteIcon.textContent = '💖';
            favoriteText.textContent = 'お気に入り済み';
            favoriteBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
            favoriteBtn.classList.add('bg-pink-500', 'hover:bg-pink-600');
        }
        
        localStorage.setItem('grant_favorites', JSON.stringify(favorites));
        
        // カスタムイベントを発火
        window.dispatchEvent(new CustomEvent('favoriteUpdated', {
            detail: { postId: postId, favorites: favorites }
        }));
    }
    
    function loadRelatedGrants() {
        const postId = <?php echo get_the_ID(); ?>;
        const category = '<?php echo esc_js($grant_category); ?>';
        const prefecture = '<?php echo esc_js($prefecture); ?>';
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_related_grants',
                post_id: postId,
                category: category,
                prefecture: prefecture,
                nonce: '<?php echo wp_create_nonce('get_related_grants_nonce'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html) {
                document.getElementById('related-grants').innerHTML = data.data.html;
            } else {
                document.getElementById('related-grants').innerHTML = '<p class="text-gray-500 text-sm">関連する助成金が見つかりませんでした。</p>';
            }
        })
        .catch(error => {
            console.error('関連助成金の読み込みに失敗しました:', error);
            document.getElementById('related-grants').innerHTML = '<p class="text-red-500 text-sm">読み込みに失敗しました。</p>';
        });
    }
});

// URLコピー機能
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // 成功時の処理
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = '✅ コピー済み';
        btn.classList.add('bg-green-500', 'hover:bg-green-600');
        btn.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        
        setTimeout(() => {
            btn.textContent = originalText;
            btn.classList.remove('bg-green-500', 'hover:bg-green-600');
            btn.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    }, function(err) {
        console.error('コピーに失敗しました: ', err);
        alert('コピーに失敗しました。手動でURLをコピーしてください。');
    });
}
</script>

<style>
/* カスタムスタイル */
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #1f2937;
    font-weight: 700;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}

.prose p {
    margin-bottom: 1em;
    line-height: 1.7;
}

.prose ul, .prose ol {
    margin: 1em 0;
    padding-left: 1.5em;
}

.prose li {
    margin-bottom: 0.5em;
}

.prose a {
    color: #3b82f6;
    text-decoration: underline;
}

.prose a:hover {
    color: #1d4ed8;
}

.prose blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1em;
    margin: 1.5em 0;
    font-style: italic;
    color: #6b7280;
}

.prose code {
    background-color: #f3f4f6;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.prose pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 1em;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5em 0;
}

/* レスポンシブ調整 */
@media (max-width: 768px) {
    .prose {
        font-size: 0.875rem;
    }
    
    .prose h1 {
        font-size: 1.5rem;
    }
    
    .prose h2 {
        font-size: 1.25rem;
    }
    
    .prose h3 {
        font-size: 1.125rem;
    }
}
</style>

<?php get_footer(); ?>