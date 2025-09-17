<?php
/**
 * The template for displaying the footer
 * プレミアム・スタイリッシュフッター（機能完全保持版）
 */

// 既存ヘルパー関数（完全保持）
if (!function_exists('gi_get_sns_urls')) {
    function gi_get_sns_urls() {
        return [
            'twitter' => get_theme_mod('sns_twitter_url', ''),
            'facebook' => get_theme_mod('sns_facebook_url', ''),
            'linkedin' => get_theme_mod('sns_linkedin_url', ''),
            'instagram' => get_theme_mod('sns_instagram_url', ''),
            'youtube' => get_theme_mod('sns_youtube_url', '')
        ];
    }
}

if (!function_exists('gi_get_option')) {
    function gi_get_option($option_name, $default = '') {
        return get_theme_mod($option_name, $default);
    }
}
?>

<!-- Tailwind CSS Play CDN（既存設定保持＋プレミアム拡張） -->
<?php if (!wp_script_is('tailwind-cdn', 'enqueued')): ?>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                    'slide-up': 'slideUp 0.8s ease-out forwards',
                    'fade-in': 'fadeIn 1s ease-out forwards',
                    'scale-in': 'scaleIn 0.6s ease-out forwards',
                    'shimmer': 'shimmer 3s linear infinite',
                    'glow': 'glow 3s ease-in-out infinite alternate',
                    'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                    'pulse-soft': 'pulseSoft 3s ease-in-out infinite',
                    'gradient-shift': 'gradientShift 8s ease-in-out infinite',
                    'morph': 'morph 12s ease-in-out infinite',
                    'sparkle': 'sparkle 2s ease-in-out infinite',
                    'slide-in-left': 'slideInLeft 0.8s ease-out forwards',
                    'slide-in-right': 'slideInRight 0.8s ease-out forwards'
                },
                keyframes: {
                    float: {
                        '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                        '25%': { transform: 'translateY(-6px) rotate(1deg)' },
                        '50%': { transform: 'translateY(-12px) rotate(0deg)' },
                        '75%': { transform: 'translateY(-6px) rotate(-1deg)' }
                    },
                    slideUp: {
                        '0%': { opacity: '0', transform: 'translateY(40px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    fadeIn: {
                        '0%': { opacity: '0' },
                        '100%': { opacity: '1' }
                    },
                    scaleIn: {
                        '0%': { opacity: '0', transform: 'scale(0.9) rotate(-3deg)' },
                        '100%': { opacity: '1', transform: 'scale(1) rotate(0deg)' }
                    },
                    shimmer: {
                        '0%': { backgroundPosition: '-200% 0' },
                        '100%': { backgroundPosition: '200% 0' }
                    },
                    glow: {
                        '0%': { 
                            boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)',
                            filter: 'brightness(1)'
                        },
                        '100%': { 
                            boxShadow: '0 0 40px rgba(99, 102, 241, 0.6)',
                            filter: 'brightness(1.1)'
                        }
                    },
                    bounceGentle: {
                        '0%, 100%': { transform: 'translateY(-5%)' },
                        '50%': { transform: 'translateY(0)' }
                    },
                    pulseSoft: {
                        '0%, 100%': { opacity: 1 },
                        '50%': { opacity: .8 }
                    },
                    gradientShift: {
                        '0%, 100%': { backgroundPosition: '0% 50%' },
                        '50%': { backgroundPosition: '100% 50%' }
                    },
                    morph: {
                        '0%, 100%': { borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%' },
                        '50%': { borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%' }
                    },
                    sparkle: {
                        '0%, 100%': { opacity: '0', transform: 'scale(0)' },
                        '50%': { opacity: '1', transform: 'scale(1)' }
                    },
                    slideInLeft: {
                        '0%': { opacity: '0', transform: 'translateX(-60px)' },
                        '100%': { opacity: '1', transform: 'translateX(0)' }
                    },
                    slideInRight: {
                        '0%': { opacity: '0', transform: 'translateX(60px)' },
                        '100%': { opacity: '1', transform: 'translateX(0)' }
                    }
                },
                backdropBlur: {
                    'xs': '2px',
                },
                boxShadow: {
                    'glow': '0 0 20px rgba(59, 130, 246, 0.3)',
                    'glow-lg': '0 0 40px rgba(59, 130, 246, 0.4)',
                    'premium': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                    'premium-dark': '0 25px 50px -12px rgba(0, 0, 0, 0.6)',
                },
                borderRadius: {
                    '4xl': '2rem',
                    '5xl': '2.5rem',
                }
            }
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php endif; ?>

    </main>

    <!-- プレミアム・スタイリッシュフッター -->
    <footer class="site-footer relative overflow-hidden bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50 dark:from-gray-900 dark:via-slate-900 dark:to-blue-950 transition-all duration-700 font-inter">
        
        <!-- 複層装飾背景システム -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- メッシュグリッド -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,theme(colors.slate.200)_1px,transparent_1px),linear-gradient(to_bottom,theme(colors.slate.200)_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,theme(colors.slate.800)_1px,transparent_1px),linear-gradient(to_bottom,theme(colors.slate.800)_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-30 animate-pulse-soft"></div>
            
            <!-- 動的グラデーションオーブ -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-conic from-blue-400/20 via-purple-500/15 to-pink-400/20 dark:from-blue-600/15 dark:via-purple-700/10 dark:to-pink-600/15 rounded-full -translate-y-48 translate-x-48 blur-3xl animate-morph"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-radial from-emerald-400/20 via-teal-500/15 to-cyan-400/20 dark:from-emerald-600/15 dark:via-teal-700/10 dark:to-cyan-600/15 rounded-full translate-y-40 -translate-x-40 blur-3xl animate-morph" style="animation-delay: 6s;"></div>
            
            <!-- 光の粒子効果 -->
            <div class="absolute top-20 left-20 w-2 h-2 bg-blue-400 dark:bg-blue-300 rounded-full animate-sparkle"></div>
            <div class="absolute top-40 right-32 w-1 h-1 bg-purple-400 dark:bg-purple-300 rounded-full animate-sparkle" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-32 left-40 w-1.5 h-1.5 bg-emerald-400 dark:bg-emerald-300 rounded-full animate-sparkle" style="animation-delay: 2s;"></div>
        </div>

        <!-- ダークモード切り替えボタン -->
        <div class="fixed top-6 right-6 z-50">
            <button id="dark-mode-toggle" class="w-12 h-12 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl shadow-premium hover:shadow-premium-dark transition-all duration-300 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:scale-110 border border-gray-200/50 dark:border-gray-700/50 group">
                <i class="fas fa-sun dark:hidden text-lg group-hover:animate-bounce-gentle"></i>
                <i class="fas fa-moon hidden dark:block text-lg group-hover:animate-bounce-gentle"></i>
            </button>
        </div>

        <div class="relative z-10 py-20 lg:py-24">
            <div class="container mx-auto px-4">
                
                <!-- プレミアムブランドセクション -->
                <div class="text-center mb-20 animate-fade-in">
                    <div class="inline-flex items-center space-x-6 mb-12 group">
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-r from-blue-400/20 to-purple-400/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-700 animate-glow"></div>
                            <img src="http://joseikin-insight.com/wp-content/uploads/2025/09/名称未設定のデザイン.png" 
                                 alt="助成金・補助金インサイト" 
                                 class="relative h-20 w-auto drop-shadow-2xl group-hover:drop-shadow-[0_35px_35px_rgba(0,0,0,0.25)] transition-all duration-700 group-hover:scale-110 animate-float">
                        </div>
                        
                        <div class="text-left">
                            <h2 class="text-4xl lg:text-5xl font-black text-gray-800 dark:text-gray-100 leading-tight font-space">
                                助成金・補助金
                                <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent animate-gradient-shift bg-[length:200%_200%]">インサイト</span>
                            </h2>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Powered by Next-Gen AI Technology</span>
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-lg opacity-50 animate-glow"></div>
                                    <div class="relative bg-gradient-to-r from-blue-500 to-purple-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg animate-bounce-gentle">
                                        <i class="fas fa-robot mr-1"></i>AI
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-4xl mx-auto leading-relaxed font-light">
                        <i class="fas fa-sparkles mr-3 text-yellow-500 animate-sparkle"></i>
                        最先端AIテクノロジーが実現する、革新的な補助金・助成金プラットフォーム。<br class="hidden md:block">
                        あなたのビジネスに最適化された情報を瞬時に発見し、<span class="font-semibold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">成長を劇的に加速</span>させます。
                    </p>
                </div>

                <!-- メインカードシステム（デスクトップ表示） -->
                <div class="hidden lg:grid lg:grid-cols-3 gap-8 mb-20">
                    
                    <!-- 補助金検索カード -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-4xl p-8 shadow-premium hover:shadow-premium-dark transition-all duration-700 border border-white/60 dark:border-gray-700/50 hover:border-blue-200/50 dark:hover:border-blue-600/30 group animate-slide-in-left relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 dark:from-blue-600/10 dark:to-purple-600/10 rounded-4xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="text-center mb-8">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl blur-lg opacity-30 group-hover:opacity-60 transition-opacity duration-500 animate-glow"></div>
                                    <div class="relative w-18 h-18 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-premium group-hover:shadow-glow-lg transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                        <i class="fas fa-search text-white text-2xl animate-float"></i>
                                    </div>
                                </div>
                                <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 mb-3">補助金を探す</h3>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">AIが最適な補助金を瞬時に発見</p>
                            </div>
                            
                            <div class="space-y-3">
                                <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-blue-50/80 dark:hover:bg-blue-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-blue-300/50 dark:hover:border-blue-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-blue-200 dark:group-hover/item:bg-blue-800/70 transition-colors duration-200">
                                            <i class="fas fa-list text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-blue-700 dark:group-hover/item:text-blue-300 transition-colors">助成金一覧</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-blue-500 dark:group-hover/item:text-blue-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/grants/?category=it')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-indigo-50/80 dark:hover:bg-indigo-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-indigo-300/50 dark:hover:border-indigo-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-indigo-200 dark:group-hover/item:bg-indigo-800/70 transition-colors duration-200">
                                            <i class="fas fa-laptop-code text-indigo-600 dark:text-indigo-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-indigo-700 dark:group-hover/item:text-indigo-300 transition-colors">IT・デジタル化</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-indigo-500 dark:group-hover/item:text-indigo-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/grants/?category=manufacturing')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-purple-50/80 dark:hover:bg-purple-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-purple-300/50 dark:hover:border-purple-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-purple-200 dark:group-hover/item:bg-purple-800/70 transition-colors duration-200">
                                            <i class="fas fa-industry text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-purple-700 dark:group-hover/item:text-purple-300 transition-colors">ものづくり・製造業</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-purple-500 dark:group-hover/item:text-purple-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/grants/?category=startup')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-emerald-50/80 dark:hover:bg-emerald-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-emerald-300/50 dark:hover:border-emerald-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-emerald-200 dark:group-hover/item:bg-emerald-800/70 transition-colors duration-200">
                                            <i class="fas fa-rocket text-emerald-600 dark:text-emerald-400 animate-bounce-gentle"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-emerald-700 dark:group-hover/item:text-emerald-300 transition-colors">創業・起業</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-emerald-500 dark:group-hover/item:text-emerald-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>

                                <a href="<?php echo esc_url(home_url('/grants/?category=employment')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-yellow-50/80 dark:hover:bg-yellow-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-yellow-300/50 dark:hover:border-yellow-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-yellow-200 dark:group-hover/item:bg-yellow-800/70 transition-colors duration-200">
                                            <i class="fas fa-users text-yellow-600 dark:text-yellow-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-yellow-700 dark:group-hover/item:text-yellow-300 transition-colors">雇用・人材育成</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-yellow-500 dark:group-hover/item:text-yellow-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>

                                <a href="<?php echo esc_url(home_url('/grants/?category=environment')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-green-50/80 dark:hover:bg-green-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-green-300/50 dark:hover:border-green-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-green-200 dark:group-hover/item:bg-green-800/70 transition-colors duration-200">
                                            <i class="fas fa-leaf text-green-600 dark:text-green-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-green-700 dark:group-hover/item:text-green-300 transition-colors">環境・省エネ</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-green-500 dark:group-hover/item:text-green-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ツール・サービスカード -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-4xl p-8 shadow-premium hover:shadow-premium-dark transition-all duration-700 border border-white/60 dark:border-gray-700/50 hover:border-emerald-200/50 dark:hover:border-emerald-600/30 group animate-scale-in relative overflow-hidden" style="animation-delay: 0.2s;">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 dark:from-emerald-600/10 dark:to-teal-600/10 rounded-4xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="text-center mb-8">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl blur-lg opacity-30 group-hover:opacity-60 transition-opacity duration-500 animate-glow"></div>
                                    <div class="relative w-18 h-18 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-premium group-hover:shadow-glow-lg transition-all duration-500 group-hover:scale-110 group-hover:-rotate-3">
                                        <i class="fas fa-tools text-white text-2xl animate-float"></i>
                                    </div>
                                </div>
                                <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 mb-3">ツール・サービス</h3>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">AI診断ツールと専門家サポート</p>
                            </div>
                            
                            <div class="space-y-3">
                                <a href="<?php echo esc_url(home_url('/tools/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-emerald-50/80 dark:hover:bg-emerald-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-emerald-300/50 dark:hover:border-emerald-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-emerald-200 dark:group-hover/item:bg-emerald-800/70 transition-colors duration-200">
                                            <i class="fas fa-stethoscope text-emerald-600 dark:text-emerald-400"></i>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-emerald-700 dark:group-hover/item:text-emerald-300 transition-colors">診断ツール</span>
                                            <div class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white text-xs px-2.5 py-1 rounded-full font-bold shadow-lg animate-pulse-soft">無料</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-emerald-500 dark:group-hover/item:text-emerald-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-teal-50/80 dark:hover:bg-teal-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-teal-300/50 dark:hover:border-teal-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-teal-200 dark:group-hover/item:bg-teal-800/70 transition-colors duration-200">
                                            <i class="fas fa-trophy text-teal-600 dark:text-teal-400 animate-bounce-gentle"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-teal-700 dark:group-hover/item:text-teal-300 transition-colors">成功事例</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-teal-500 dark:group-hover/item:text-teal-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/grant-tips/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-cyan-50/80 dark:hover:bg-cyan-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-cyan-300/50 dark:hover:border-cyan-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-cyan-200 dark:group-hover/item:bg-cyan-800/70 transition-colors duration-200">
                                            <i class="fas fa-lightbulb text-cyan-600 dark:text-cyan-400 animate-sparkle"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-cyan-700 dark:group-hover/item:text-cyan-300 transition-colors">申請のコツ</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-cyan-500 dark:group-hover/item:text-cyan-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/ai/chat/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-purple-50/80 dark:hover:bg-purple-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-purple-300/50 dark:hover:border-purple-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-purple-200 dark:group-hover/item:bg-purple-800/70 transition-colors duration-200">
                                            <i class="fas fa-robot text-purple-600 dark:text-purple-400 animate-float"></i>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-purple-700 dark:group-hover/item:text-purple-300 transition-colors">AIチャット</span>
                                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs px-2.5 py-1 rounded-full font-bold shadow-lg animate-shimmer bg-[length:200%_100%]">HOT</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-purple-500 dark:group-hover/item:text-purple-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/experts/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-orange-50/80 dark:hover:bg-orange-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-orange-300/50 dark:hover:border-orange-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-orange-200 dark:group-hover/item:bg-orange-800/70 transition-colors duration-200">
                                            <i class="fas fa-user-tie text-orange-600 dark:text-orange-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-orange-700 dark:group-hover/item:text-orange-300 transition-colors">専門家相談</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-orange-500 dark:group-hover/item:text-orange-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- サポートカード -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-4xl p-8 shadow-premium hover:shadow-premium-dark transition-all duration-700 border border-white/60 dark:border-gray-700/50 hover:border-purple-200/50 dark:hover:border-purple-600/30 group animate-slide-in-right relative overflow-hidden" style="animation-delay: 0.4s;">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 dark:from-purple-600/10 dark:to-pink-600/10 rounded-4xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="text-center mb-8">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl blur-lg opacity-30 group-hover:opacity-60 transition-opacity duration-500 animate-glow"></div>
                                    <div class="relative w-18 h-18 bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-premium group-hover:shadow-glow-lg transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                        <i class="fas fa-headset text-white text-2xl animate-float"></i>
                                    </div>
                                </div>
                                <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 mb-3">サポート</h3>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">お困りごとはこちらから解決</p>
                            </div>
                            
                            <div class="space-y-3">
                                <a href="<?php echo esc_url(home_url('/about/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-purple-50/80 dark:hover:bg-purple-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-purple-300/50 dark:hover:border-purple-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-purple-200 dark:group-hover/item:bg-purple-800/70 transition-colors duration-200">
                                            <i class="fas fa-info-circle text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-purple-700 dark:group-hover/item:text-purple-300 transition-colors">Grant Insightとは</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-purple-500 dark:group-hover/item:text-purple-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/faq/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-pink-50/80 dark:hover:bg-pink-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-pink-300/50 dark:hover:border-pink-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-pink-100 dark:bg-pink-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-pink-200 dark:group-hover/item:bg-pink-800/70 transition-colors duration-200">
                                            <i class="fas fa-question-circle text-pink-600 dark:text-pink-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-pink-700 dark:group-hover/item:text-pink-300 transition-colors">よくある質問</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-pink-500 dark:group-hover/item:text-pink-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-rose-50/80 dark:hover:bg-rose-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-rose-300/50 dark:hover:border-rose-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-rose-200 dark:group-hover/item:bg-rose-800/70 transition-colors duration-200">
                                            <i class="fas fa-envelope text-rose-600 dark:text-rose-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-rose-700 dark:group-hover/item:text-rose-300 transition-colors">お問い合わせ</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-rose-500 dark:group-hover/item:text-rose-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/privacy/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-indigo-50/80 dark:hover:bg-indigo-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-indigo-300/50 dark:hover:border-indigo-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-indigo-200 dark:group-hover/item:bg-indigo-800/70 transition-colors duration-200">
                                            <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400 animate-bounce-gentle"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-indigo-700 dark:group-hover/item:text-indigo-300 transition-colors">プライバシーポリシー</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-indigo-500 dark:group-hover/item:text-indigo-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/terms/')); ?>" 
                                   class="flex items-center justify-between p-4 bg-gray-50/80 dark:bg-gray-700/60 backdrop-blur-sm rounded-2xl hover:bg-blue-50/80 dark:hover:bg-blue-900/30 transition-all duration-300 group/item border border-gray-200/30 dark:border-gray-600/30 hover:border-blue-300/50 dark:hover:border-blue-600/50 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center group-hover/item:bg-blue-200 dark:group-hover/item:bg-blue-800/70 transition-colors duration-200">
                                            <i class="fas fa-file-contract text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover/item:text-blue-700 dark:group-hover/item:text-blue-300 transition-colors">利用規約</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 group-hover/item:text-blue-500 dark:group-hover/item:text-blue-400 transition-all duration-200 group-hover/item:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- モバイル開閉式ナビゲーション -->
                <div class="lg:hidden mb-8">
                    <button id="mobile-footer-toggle" class="w-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-4xl p-6 shadow-premium border border-gray-200/50 dark:border-gray-700/50 flex items-center justify-between text-gray-800 dark:text-gray-200 hover:bg-white/90 dark:hover:bg-gray-800/90 transition-all duration-300 group">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-bars text-white text-xl animate-bounce-gentle"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-bold text-xl">メニューを開く</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">すべてのサービスにアクセス</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-2xl transition-transform duration-300" id="mobile-toggle-icon"></i>
                    </button>
                </div>

                <!-- モバイル専用コンテンツ -->
                <div id="mobile-footer-content" class="lg:hidden space-y-6 hidden overflow-hidden" style="max-height: 0; transition: max-height 0.3s ease-out;">
                    
                    <!-- 補助金を探す（モバイル） -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl p-6 shadow-premium border border-gray-200/50 dark:border-gray-700/50">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                            <i class="fas fa-search mr-3 text-blue-600 text-2xl"></i>補助金を探す
                        </h3>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="<?php echo esc_url(home_url('/grants/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                                <i class="fas fa-list mr-3 text-blue-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">助成金一覧</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/grants/?category=it')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                <i class="fas fa-laptop-code mr-3 text-indigo-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">IT・デジタル化</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/grants/?category=manufacturing')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors">
                                <i class="fas fa-industry mr-3 text-purple-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">ものづくり・製造業</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/grants/?category=startup')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors">
                                <i class="fas fa-rocket mr-3 text-emerald-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">創業・起業</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/grants/?category=employment')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors">
                                <i class="fas fa-users mr-3 text-yellow-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">雇用・人材育成</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/grants/?category=environment')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/30 transition-colors">
                                <i class="fas fa-leaf mr-3 text-green-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">環境・省エネ</span>
                            </a>
                        </div>
                    </div>

                    <!-- ツール・サービス（モバイル） -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl p-6 shadow-premium border border-gray-200/50 dark:border-gray-700/50">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                            <i class="fas fa-tools mr-3 text-emerald-600 text-2xl"></i>ツール・サービス
                        </h3>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="<?php echo esc_url(home_url('/tools/')); ?>" class="flex items-center justify-between p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-stethoscope mr-3 text-emerald-600"></i>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">診断ツール</span>
                                </div>
                                <span class="bg-emerald-500 text-white text-xs px-2 py-1 rounded-full">無料</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/ai/chat/')); ?>" class="flex items-center justify-between p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-robot mr-3 text-purple-600"></i>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">AIチャット</span>
                                </div>
                                <span class="bg-purple-500 text-white text-xs px-2 py-1 rounded-full">HOT</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">
                                <i class="fas fa-trophy mr-3 text-teal-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">成功事例</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/grant-tips/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-cyan-50 dark:hover:bg-cyan-900/30 transition-colors">
                                <i class="fas fa-lightbulb mr-3 text-cyan-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">申請のコツ</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/experts/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-orange-50 dark:hover:bg-orange-900/30 transition-colors">
                                <i class="fas fa-user-tie mr-3 text-orange-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">専門家相談</span>
                            </a>
                        </div>
                    </div>

                    <!-- サポート（モバイル） -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl p-6 shadow-premium border border-gray-200/50 dark:border-gray-700/50">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                            <i class="fas fa-headset mr-3 text-purple-600 text-2xl"></i>サポート
                        </h3>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="<?php echo esc_url(home_url('/about/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors">
                                <i class="fas fa-info-circle mr-3 text-purple-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">Grant Insightとは</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors">
                                <i class="fas fa-envelope mr-3 text-rose-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">お問い合わせ</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/faq/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/30 transition-colors">
                                <i class="fas fa-question-circle mr-3 text-pink-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">よくある質問</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/privacy/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                <i class="fas fa-shield-alt mr-3 text-indigo-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">プライバシーポリシー</span>
                            </a>
                            <a href="<?php echo esc_url(home_url('/terms/')); ?>" class="flex items-center p-3 bg-gray-50/80 dark:bg-gray-700/60 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                                <i class="fas fa-file-contract mr-3 text-blue-600"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">利用規約</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- プレミアムCTAセクション -->
                <div class="mb-20 animate-scale-in" style="animation-delay: 0.6s;">
                    <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 dark:from-blue-700 dark:via-purple-700 dark:to-indigo-800 rounded-5xl p-12 lg:p-16 text-center shadow-premium overflow-hidden group">
                        <!-- 動的背景エフェクト -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/95 via-purple-600/95 to-indigo-700/95 dark:from-blue-700/95 dark:via-purple-700/95 dark:to-indigo-800/95 animate-gradient-shift bg-[length:200%_200%]"></div>
                        
                        <!-- 装飾的要素 -->
                        <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -translate-y-40 translate-x-40 animate-morph"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-32 -translate-x-32 animate-morph" style="animation-delay: 4s;"></div>
                        
                        <div class="relative z-10">
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-sm rounded-3xl mb-8 group-hover:scale-110 transition-transform duration-500 animate-glow">
                                <i class="fas fa-rocket text-white text-4xl animate-float"></i>
                            </div>
                            
                            <h3 class="text-4xl lg:text-5xl font-black text-white mb-6">
                                今すぐ助成金診断を始めよう
                            </h3>
                            <p class="text-blue-100 dark:text-blue-200 text-xl lg:text-2xl mb-10 max-w-3xl mx-auto leading-relaxed font-light">
                                最先端AIが最適な助成金・補助金を瞬時に発見し、<br class="hidden md:block">
                                あなたの事業成長を<span class="font-semibold">劇的に加速</span>させます
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                                <a href="<?php echo esc_url(home_url('/tools/diagnosis/')); ?>" 
                                   class="inline-flex items-center justify-center bg-white text-blue-700 py-5 px-10 rounded-3xl font-bold text-xl shadow-premium hover:shadow-premium-dark transition-all duration-500 transform hover:-translate-y-3 hover:scale-105 group/btn relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white to-blue-50 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                    <div class="relative flex items-center">
                                        <i class="fas fa-search mr-4 group-hover/btn:animate-bounce-gentle text-2xl"></i>
                                        無料診断スタート
                                        <i class="fas fa-arrow-right ml-4 group-hover/btn:translate-x-2 transition-transform duration-300 text-2xl"></i>
                                    </div>
                                </a>
                                <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                                   class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm text-white py-5 px-10 rounded-3xl font-bold text-xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 group/btn relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-blue-100/10 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                    <div class="relative flex items-center">
                                        <i class="fas fa-comments mr-4 group-hover/btn:animate-bounce-gentle text-2xl"></i>
                                        専門家に相談
                                        <i class="fas fa-user-tie ml-4 text-2xl"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SNS & フッター下部セクション -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center animate-fade-in" style="animation-delay: 0.8s;">
                    <!-- SNSセクション -->
                    <div class="text-center lg:text-left">
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">フォローして最新情報をチェック</h4>
                        <div class="flex justify-center lg:justify-start space-x-6 mb-8">
                            <?php
                            $sns_urls = gi_get_sns_urls();
                            $sns_data = [
                                'twitter' => ['icon' => 'fab fa-twitter', 'color' => 'from-blue-400 to-blue-600'],
                                'facebook' => ['icon' => 'fab fa-facebook-f', 'color' => 'from-blue-500 to-blue-700'], 
                                'linkedin' => ['icon' => 'fab fa-linkedin-in', 'color' => 'from-blue-600 to-blue-800'],
                                'instagram' => ['icon' => 'fab fa-instagram', 'color' => 'from-pink-400 to-purple-600'],
                                'youtube' => ['icon' => 'fab fa-youtube', 'color' => 'from-red-500 to-red-700']
                            ];
                            ?>
                            <?php foreach ($sns_urls as $platform => $url): ?>
                                <?php if (!empty($url)): ?>
                                    <a href="<?php echo esc_url($url); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="relative w-16 h-16 bg-gradient-to-br <?php echo $sns_data[$platform]['color']; ?> rounded-3xl flex items-center justify-center text-white shadow-premium hover:shadow-premium-dark transition-all duration-500 transform hover:-translate-y-3 hover:scale-110 group overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-br <?php echo $sns_data[$platform]['color']; ?> opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-glow"></div>
                                        <i class="<?php echo $sns_data[$platform]['icon']; ?> text-xl group-hover:animate-bounce-gentle relative z-10"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- プレミアム特徴バッジ -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                            <span class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 dark:from-green-600/30 dark:to-emerald-600/30 text-green-700 dark:text-green-300 px-5 py-3 rounded-2xl text-sm font-semibold backdrop-blur-sm border border-green-500/30 dark:border-green-600/40 hover:scale-105 transition-transform duration-300 cursor-default">
                                <i class="fas fa-check-circle mr-2 animate-bounce-gentle"></i>無料診断
                            </span>
                            <span class="bg-gradient-to-r from-blue-500/20 to-indigo-500/20 dark:from-blue-600/30 dark:to-indigo-600/30 text-blue-700 dark:text-blue-300 px-5 py-3 rounded-2xl text-sm font-semibold backdrop-blur-sm border border-blue-500/30 dark:border-blue-600/40 hover:scale-105 transition-transform duration-300 cursor-default">
                                <i class="fas fa-robot mr-2 animate-float"></i>AI支援
                            </span>
                            <span class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 dark:from-purple-600/30 dark:to-pink-600/30 text-purple-700 dark:text-purple-300 px-5 py-3 rounded-2xl text-sm font-semibold backdrop-blur-sm border border-purple-500/30 dark:border-purple-600/40 hover:scale-105 transition-transform duration-300 cursor-default">
                                <i class="fas fa-users mr-2"></i>専門家サポート
                            </span>
                        </div>
                    </div>

                    <!-- 信頼バッジ & コピーライト -->
                    <div class="text-center lg:text-right">
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="flex items-center justify-center text-emerald-600 dark:text-emerald-400 group hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-2xl flex items-center justify-center mr-3 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/70 transition-colors shadow-lg">
                                    <i class="fas fa-shield-alt text-emerald-600 dark:text-emerald-400 text-xl"></i>
                                </div>
                                <span class="font-semibold">SSL暗号化通信</span>
                            </div>
                            <div class="flex items-center justify-center text-blue-600 dark:text-blue-400 group hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-2xl flex items-center justify-center mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/70 transition-colors shadow-lg">
                                    <i class="fas fa-lock text-blue-600 dark:text-blue-400 text-xl"></i>
                                </div>
                                <span class="font-semibold">個人情報保護</span>
                            </div>
                            <div class="flex items-center justify-center text-purple-600 dark:text-purple-400 group hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-2xl flex items-center justify-center mr-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/70 transition-colors shadow-lg">
                                    <i class="fas fa-award text-purple-600 dark:text-purple-400 text-xl animate-sparkle"></i>
                                </div>
                                <span class="font-semibold">専門家監修</span>
                            </div>
                            <div class="flex items-center justify-center text-yellow-600 dark:text-yellow-400 group hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-2xl flex items-center justify-center mr-3 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/70 transition-colors shadow-lg">
                                    <i class="fas fa-robot text-yellow-600 dark:text-yellow-400 text-xl animate-float"></i>
                                </div>
                                <span class="font-semibold">AI技術活用</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200/50 dark:border-gray-700/50 pt-8">
                            <p class="text-gray-600 dark:text-gray-400 mb-3 text-lg font-medium">
                                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
                            </p>
                            <p class="text-gray-500 dark:text-gray-500 font-light">
                                Powered by Next-Generation AI Technology & Expert Knowledge
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- プレミアムトップに戻るボタン（既存機能保持） -->
    <div id="back-to-top" class="fixed bottom-8 right-8 z-50 opacity-0 pointer-events-none transition-all duration-500">
        <button class="relative w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-3xl shadow-premium hover:shadow-premium-dark transition-all duration-500 transform hover:-translate-y-3 hover:scale-110 group overflow-hidden" onclick="scrollToTop()">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-glow"></div>
            <i class="fas fa-arrow-up text-2xl group-hover:animate-bounce-gentle relative z-10"></i>
        </button>
    </div>

    <script>
    // ダークモード制御システム（既存機能完全保持）
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const html = document.documentElement;
        
        // ダークモード状態の初期化
        const isDarkMode = localStorage.getItem('gi-darkMode') === 'true' || 
                          (!localStorage.getItem('gi-darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        if (isDarkMode) {
            html.classList.add('dark');
        }
        
        // ダークモード切り替え
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function() {
                html.classList.toggle('dark');
                const isDark = html.classList.contains('dark');
                localStorage.setItem('gi-darkMode', isDark);
                
                // スムーズな切り替えアニメーション
                darkModeToggle.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    darkModeToggle.style.transform = 'scale(1)';
                }, 150);
            });
        }

        // モバイルフッター開閉制御
        const mobileToggle = document.getElementById('mobile-footer-toggle');
        const mobileContent = document.getElementById('mobile-footer-content');
        const mobileIcon = document.getElementById('mobile-toggle-icon');
        let isOpen = false;

        if (mobileToggle && mobileContent) {
            mobileToggle.addEventListener('click', function() {
                isOpen = !isOpen;
                
                if (isOpen) {
                    mobileContent.classList.remove('hidden');
                    mobileContent.style.maxHeight = mobileContent.scrollHeight + 'px';
                    mobileIcon.style.transform = 'rotate(180deg)';
                    mobileToggle.querySelector('h3').textContent = 'メニューを閉じる';
                    mobileToggle.querySelector('p').textContent = 'タップして折りたたみ';
                } else {
                    mobileContent.style.maxHeight = '0px';
                    mobileIcon.style.transform = 'rotate(0deg)';
                    mobileToggle.querySelector('h3').textContent = 'メニューを開く';
                    mobileToggle.querySelector('p').textContent = 'すべてのサービスにアクセス';
                    
                    setTimeout(() => {
                        mobileContent.classList.add('hidden');
                    }, 300);
                }
            });
        }

        // トップに戻るボタン制御（既存機能完全保持）
        window.addEventListener('scroll', function() {
            const backToTopButton = document.getElementById('back-to-top');
            const scrolled = window.pageYOffset;
            
            if (scrolled > 500) {
                backToTopButton.classList.remove('opacity-0', 'pointer-events-none');
                backToTopButton.classList.add('opacity-100', 'pointer-events-auto');
            } else {
                backToTopButton.classList.add('opacity-0', 'pointer-events-none');
                backToTopButton.classList.remove('opacity-100', 'pointer-events-auto');
            }
        });

        // 高度なインターセクションオブザーバー（既存機能保持）
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    
                    // 要素に応じた異なるアニメーション
                    if (element.classList.contains('animate-slide-in-left')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateX(0)';
                    } else if (element.classList.contains('animate-slide-in-right')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateX(0)';
                    } else if (element.classList.contains('animate-scale-in')) {
                        element.style.opacity = '1';
                        element.style.transform = 'scale(1) rotate(0deg)';
                    } else if (element.classList.contains('animate-fade-in')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                    
                    observer.unobserve(element);
                }
            });
        }, observerOptions);

        // 全てのアニメーション要素を監視
        document.querySelectorAll('.site-footer [class*="animate-"]').forEach(el => {
            // 初期状態を設定
            if (el.classList.contains('animate-slide-in-left')) {
                el.style.opacity = '0';
                el.style.transform = 'translateX(-60px)';
                el.style.transition = 'all 0.8s ease-out';
            } else if (el.classList.contains('animate-slide-in-right')) {
                el.style.opacity = '0';
                el.style.transform = 'translateX(60px)';
                el.style.transition = 'all 0.8s ease-out';
            } else if (el.classList.contains('animate-scale-in')) {
                el.style.opacity = '0';
                el.style.transform = 'scale(0.9) rotate(-3deg)';
                el.style.transition = 'all 0.6s ease-out';
            } else if (el.classList.contains('animate-fade-in')) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(40px)';
                el.style.transition = 'all 1s ease-out';
            }
            
            observer.observe(el);
        });

        // レスポンシブ対応
        window.addEventListener('resize', function() {
            const mobileContent = document.getElementById('mobile-footer-content');
            if (window.innerWidth >= 1024 && mobileContent && !mobileContent.classList.contains('hidden')) {
                mobileContent.classList.add('hidden');
                mobileContent.style.maxHeight = '0px';
                const mobileIcon = document.getElementById('mobile-toggle-icon');
                if (mobileIcon) {
                    mobileIcon.style.transform = 'rotate(0deg)';
                }
                isOpen = false;
            }
        });
    });

    // スムーズスクロール（既存機能保持）
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    </script>

    <?php wp_footer(); ?>

</body>
</html>
