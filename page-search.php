<?php
/**
 * Template Name: 検索ページ
 * 
 * 統合検索システム専用の固定ページテンプレート
 * 
 * @package Grant_Insight
 * @version 4.0.0
 */

get_header();

// template-partsから検索セクションを読み込み
get_template_part('template-parts/front-page/section', 'search');

get_footer();
?>