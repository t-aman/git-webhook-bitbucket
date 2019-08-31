<?php

/*!-----------------------------------------------------------------
 * BitBucketからの自動反映
 * ------------------------------------------------------------------
 */

// SECRET設定、ログ設定
define("WEBHOOK_HOST", '【ホスト名】');
define("WEBHOOK_LOGFILE", __DIR__ . '/webhook_bitbucket.log');

// ブランチごとのコマンド
$command = array(
    'master'	=> 'git pull --rebase origin master',
    'develop'	=> 'cd ./develop;git pull --rebase origin develop',
);

// 関数定義（ログ出力）
function putLog($message)
{
    echo date("Y-m-d H:i:s")  . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . $message . "<br/>";
    error_log(date("Y-m-d H:i:s") . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . $message . "\r\n", 3, WEBHOOK_LOGFILE);
}


/*!------------------------------------------------
 * 自動反映処理
 * ------------------------------------------------
 */
$header    = getallheaders();
if (
    isset($header['Host']) && $header['Host'] === WEBHOOK_HOST &&
    isset($header['User-Agent']) && $header['User-Agent'] === 'Bitbucket-Webhooks/2.0' &&
    isset($header['X-Event-Key']) && $header['X-Event-Key'] === 'repo:push'
) {
    $payload = json_decode(file_get_contents('php://input'), true);
    // putLog('【DEBUG】' . "\t" . var_export( $header , true) . "\t" . var_export($payload, true));
    foreach ($command as $branch => $cmd) {
        if (
            $payload['push']['changes'][0]['new']['name'] === $branch &&
            $payload['push']['changes'][0]['new']['type'] === 'branch'
        ) {
            if ($cmd !== '') {
                exec($cmd, $opt, $ret);
                putLog('【PULL】' . "\t" . "{$branch}" . "\t" . $cmd . "\t" . $ret);
            } else {
                putLog('【NULL】' . "\t" . "{$branch}" . "\t" . ""   . "\t");
            }
        }
    }
} else {
    putLog('【ERR】' . "\t" . var_export($header, true) . "\t" . ""  . "\t");
}
