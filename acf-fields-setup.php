<?php
/**
 * ACF (Advanced Custom Fields) 設定
 * 
 * このファイルは、WordPressの管理画面でACFのフィールドグループを設定する際の
 * 参考情報として使用してください。
 * 
 * 実際の設定は、WordPress管理画面の「カスタムフィールド」メニューから
 * GUIを使用して行ってください。
 */

// ACFプラグインが有効でない場合のフォールバック
if (!function_exists('acf_add_local_field_group')) {
    function acf_get_field($selector, $post_id = false) {
        return get_post_meta($post_id ?: get_the_ID(), $selector, true);
    }
}

/**
 * 助成金投稿タイプ用のフィールドグループ設定例
 * 
 * 以下の設定を参考に、WordPress管理画面から設定してください：
 * 
 * フィールドグループ名: 助成金詳細情報
 * 場所: 投稿タイプが「grant」と等しい
 * 
 * フィールド一覧:
 * 1. 最大金額 (max_amount) - テキスト
 * 2. 締切日 (deadline_date) - 日付ピッカー
 * 3. 締切テキスト (deadline_text) - テキスト
 * 4. 申請ステータス (application_status) - 選択 (open/closed)
 * 5. 公式URL (official_url) - URL
 * 6. AI要約 (ai_summary) - テキストエリア
 * 7. 注目フラグ (is_featured) - 真/偽
 * 8. 閲覧数 (view_count) - 数値
 */

/**
 * ツール投稿タイプ用のフィールドグループ設定例
 * 
 * フィールドグループ名: ツール詳細情報
 * 場所: 投稿タイプが「tool」と等しい
 * 
 * フィールド一覧:
 * 1. 料金 (price) - テキスト
 * 2. おすすめ度 (rating) - 選択 (★1〜★5)
 * 3. アフィリエイトリンク (affiliate_link) - URL
 * 4. 特徴 (features) - リピーター
 *    - 特徴項目 (feature_item) - テキスト
 */

/**
 * 成功事例投稿タイプ用のフィールドグループ設定例
 * 
 * フィールドグループ名: 成功事例詳細情報
 * 場所: 投稿タイプが「case_study」と等しい
 * 
 * フィールド一覧:
 * 1. 関連助成金 (related_grant) - 投稿オブジェクト (助成金)
 * 2. 企業名 (company_name) - テキスト
 * 3. 業種 (industry) - テキスト
 * 4. 従業員数 (employee_count) - テキスト
 * 5. 獲得金額 (grant_amount) - テキスト
 * 6. 成果 (results) - テキストエリア
 */

/**
 * 設定手順:
 * 
 * 1. WordPress管理画面にログイン
 * 2. 「カスタムフィールド」メニューをクリック
 * 3. 「フィールドグループ」→「新規追加」をクリック
 * 4. 上記の設定例を参考にフィールドを作成
 * 5. 「場所」タブで適用する投稿タイプを設定
 * 6. 「公開」ボタンをクリックして保存
 * 
 * 注意事項:
 * - フィールド名（Field Name）は英語で設定してください
 * - フィールドラベル（Field Label）は日本語で設定可能です
 * - 必須フィールドには「必須？」にチェックを入れてください
 */

// ACFフィールドの値を安全に取得するヘルパー関数
function gi_get_field($field_name, $post_id = null) {
    if (function_exists('get_field')) {
        return get_field($field_name, $post_id);
    }
    return get_post_meta($post_id ?: get_the_ID(), $field_name, true);
}

// ACFフィールドの値を表示するヘルパー関数
function gi_the_field($field_name, $post_id = null) {
    echo gi_safe_escape(gi_get_field($field_name, $post_id));
}

// 助成金の最大金額をフォーマットして取得
function gi_get_formatted_grant_amount($post_id = null) {
    $amount = gi_get_field('max_amount', $post_id);
    if (empty($amount)) {
        return '要問い合わせ';
    }
    
    // 数値の場合はフォーマット
    if (is_numeric($amount)) {
        return gi_safe_number_format($amount) . '万円';
    }
    
    return $amount;
}

// 【削除】gi_get_formatted_deadline() 関数は functions.php で定義済みのため削除

// 助成金の申請ステータスを取得
function gi_get_application_status($post_id = null) {
    $status = gi_get_field('application_status', $post_id);
    
    $statuses = [
        'open'   => ['label' => '募集中', 'class' => 'text-green-600 bg-green-100'],
        'closed' => ['label' => '募集終了', 'class' => 'text-red-600 bg-red-100'],
        'soon'   => ['label' => '募集予定', 'class' => 'text-blue-600 bg-blue-100'],
    ];
    
    return isset($statuses[$status]) ? $statuses[$status] : $statuses['open'];
}

// ツールの評価を星で表示
function gi_display_tool_rating($post_id = null) {
    $rating = (int) gi_get_field('rating', $post_id);
    $output = '';
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $output .= '<i class="fas fa-star text-yellow-500"></i>';
        } else {
            $output .= '<i class="far fa-star text-gray-300"></i>';
        }
    }
    
    return $output;
}
?>