<?php
// ヒトノママ ─ Scavendawn ランキングAPI（JSONファイル保存）。このフォルダに書き込み権限が必要。
// 不正対策：開始時に署名付きトークンを発行し、「開始からの実経過時間」を超える記録・同じトークンの再利用・連投を拒否する。
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$file = __DIR__ . '/rank_data.json';
$secFile = __DIR__ . '/rank_secret.php';
if (!is_file($secFile)) file_put_contents($secFile, '<?php return ' . var_export(bin2hex(random_bytes(32)), true) . ';', LOCK_EX);
$secret = require $secFile;
$diffs = ['easy', 'normal', 'hard', 'easy_q', 'normal_q', 'hard_q'];   // _q = クイックモード
$jobs = ['cop', 'nurse', 'mech', 'ath'];
$sign = fn($ts) => $ts . '.' . hash_hmac('sha256', (string)$ts, $secret);
function fail($c, $m) { http_response_code($c); exit(json_encode(['ok' => false, 'err' => $m])); }

if (isset($_GET['start'])) { exit(json_encode(['tok' => $sign(time())])); }

$fp = fopen($file, 'c+');
if (!$fp) fail(500, 'storage');
flock($fp, LOCK_EX);
$data = json_decode(stream_get_contents($fp), true);
if (!is_array($data)) $data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $in = json_decode(file_get_contents('php://input'), true);
    if (!is_array($in)) fail(400, 'json');
    $d = $in['d'] ?? '';
    $t = (int)($in['t'] ?? -1);
    $k = (int)($in['k'] ?? 0);
    $sc = (int)($in['s'] ?? $t * 10);
    $clear = !empty($in['c']) ? 1 : 0;
    $tok = (string)($in['tok'] ?? '');
    $ts = (int)strtok($tok, '.');
    $now = time();
    if (!in_array($d, $diffs, true) || $t < 0) fail(400, 'param');
    if (!hash_equals($sign($ts), $tok)) fail(403, 'token');
    if ($t > $now - $ts + 10 || $now - $ts > 6 * 3600) fail(403, 'time');   // 実時間より長い生存は不可
    if ($k < 0 || $k > $t * 2 + 20) fail(403, 'kills');                      // あり得ない撃破数
    if ($clear && $t < (substr($d, -2) === '_q' ? 300 : 1200)) fail(403, 'clear');                                // 20分未満の脱出は不可
    if ($sc < 0 || $sc > ($t * 10 + $k * 200) * 1.5 + ($clear ? 5000 : 0) + 1) fail(403, 'score'); // 理論上限を超えるスコア
    $used = $data['_used'] ?? [];
    if (in_array($tok, $used, true)) fail(403, 'reused');
    $ip = hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . $secret);
    $last = $data['_ip'][$ip] ?? 0;
    if ($now - $last < 15) fail(429, 'rate');
    $used[] = $tok; $data['_used'] = array_slice($used, -500);
    $data['_ip'][$ip] = $now;
    $data['_ip'] = array_filter($data['_ip'], fn($v) => $now - $v < 3600);
    $nick = mb_substr(trim(preg_replace('/[\x00-\x1f<>]/u', '', (string)($in['nick'] ?? ''))), 0, 12);
    $job = in_array($in['job'] ?? '', $jobs, true) ? $in['job'] : '';
    $data[$d][] = ['nick' => $nick === '' ? '名無しの生存者' : $nick, 's' => $sc, 'c' => $clear, 't' => $t, 'k' => $k, 'job' => $job, 'at' => $now];
    usort($data[$d], fn($a, $b) => ($b['s'] ?? $b['t'] * 10) <=> ($a['s'] ?? $a['t'] * 10));
    $data[$d] = array_slice($data[$d], 0, 200);
    ftruncate($fp, 0); rewind($fp);
    fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE));
    flock($fp, LOCK_UN);
    exit('{"ok":true}');
}
flock($fp, LOCK_UN);
$d = $_GET['d'] ?? 'normal';
if (!in_array($d, $diffs, true)) $d = 'normal';
echo json_encode(array_slice($data[$d] ?? [], 0, 50), JSON_UNESCAPED_UNICODE);
