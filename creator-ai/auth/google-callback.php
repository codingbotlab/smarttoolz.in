<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

if (isset($_GET['error'])) exit('Google sign-in was cancelled or failed: ' . htmlspecialchars((string)$_GET['error'], ENT_QUOTES, 'UTF-8'));
if (empty($_GET['state']) || empty($_SESSION['oauth_state']) || !hash_equals((string)$_SESSION['oauth_state'], (string)$_GET['state'])) { http_response_code(400); exit('Invalid OAuth state.'); }
unset($_SESSION['oauth_state']);
if (empty($_GET['code'])) { http_response_code(400); exit('Missing Google authorization code.'); }

try {
    $tokenResponse = http_post('https://oauth2.googleapis.com/token', ['code'=>(string)$_GET['code'],'client_id'=>GOOGLE_CLIENT_ID,'client_secret'=>GOOGLE_CLIENT_SECRET,'redirect_uri'=>GOOGLE_REDIRECT_URI,'grant_type'=>'authorization_code']);
    $token = json_decode($tokenResponse, true);
    if (!is_array($token) || empty($token['access_token'])) throw new RuntimeException('Unable to obtain Google access token.');
    $userResponse = http_get('https://openidconnect.googleapis.com/v1/userinfo', ['Authorization: Bearer ' . (string)$token['access_token']]);
    $googleUser = json_decode($userResponse, true);
    if (!is_array($googleUser) || empty($googleUser['sub']) || empty($googleUser['email']) || empty($googleUser['email_verified'])) { http_response_code(400); exit('Google account information could not be verified.'); }
} catch (Throwable $e) {
    error_log('Creator Google OAuth Error: ' . $e->getMessage()); http_response_code(500); exit('Unable to connect to Google.');
}

$googleId=trim((string)$googleUser['sub']); $email=strtolower(trim((string)$googleUser['email'])); $name=trim((string)($googleUser['name']??'Creator User')); $avatar=trim((string)($googleUser['picture']??'')); if($name==='')$name='Creator User';
$pdo=db();
try {
    $pdo->beginTransaction();
    $stmt=$pdo->prepare("SELECT * FROM users WHERE google_id=? OR email=? LIMIT 1 FOR UPDATE"); $stmt->execute([$googleId,$email]); $mainUser=$stmt->fetch();
    if($mainUser){$mainUserId=(int)$mainUser['id'];$update=$pdo->prepare("UPDATE users SET google_id=?,name=?,avatar=?,updated_at=CURRENT_TIMESTAMP WHERE id=?");$update->execute([$googleId,$name,$avatar!==''?$avatar:null,$mainUserId]);}
    else{$insert=$pdo->prepare("INSERT INTO users (google_id,name,email,avatar) VALUES (?,?,?,?)");$insert->execute([$googleId,$name,$email,$avatar!==''?$avatar:null]);$mainUserId=(int)$pdo->lastInsertId();}

    // Creator AI authentication has no credits dependency.
    $stmt=$pdo->prepare("SELECT id,google_id,name,email,avatar FROM creator_users WHERE google_id=? OR email=? LIMIT 1 FOR UPDATE"); $stmt->execute([$googleId,$email]); $creatorUser=$stmt->fetch();
    if($creatorUser){$creatorUserId=(int)$creatorUser['id'];$updateCreator=$pdo->prepare("UPDATE creator_users SET google_id=?,name=?,email=?,avatar=? WHERE id=?");$updateCreator->execute([$googleId,$name,$email,$avatar,$creatorUserId]);}
    else{$insertCreator=$pdo->prepare("INSERT INTO creator_users (google_id,name,email,avatar) VALUES (?,?,?,?)");$insertCreator->execute([$googleId,$name,$email,$avatar]);$creatorUserId=(int)$pdo->lastInsertId();}
    $pdo->commit();
} catch(Throwable $e) { if($pdo->inTransaction())$pdo->rollBack(); error_log('Creator Google Login Database Error: '.$e->getMessage()); http_response_code(500); exit('Unable to create Creator AI account.'); }

session_regenerate_id(true); $_SESSION['user_id']=$creatorUserId; $_SESSION['user_name']=$name; $_SESSION['user_email']=$email; $_SESSION['user_avatar']=$avatar; $_SESSION['main_user_id']=$mainUserId;
header('Location: /smart-toolz/'); exit;

function http_post(string $url,array $data):string{$ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>http_build_query($data),CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/x-www-form-urlencoded'],CURLOPT_TIMEOUT=>20]);$result=curl_exec($ch);if($result===false){$error=curl_error($ch);curl_close($ch);throw new RuntimeException('OAuth request failed: '.$error);} $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);if($httpCode<200||$httpCode>=300)throw new RuntimeException('Google token request returned HTTP '.$httpCode);return $result;}
function http_get(string $url,array $headers=[]):string{$ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>$headers,CURLOPT_TIMEOUT=>20]);$result=curl_exec($ch);if($result===false){$error=curl_error($ch);curl_close($ch);throw new RuntimeException('Google userinfo request failed: '.$error);} $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);if($httpCode<200||$httpCode>=300)throw new RuntimeException('Google userinfo returned HTTP '.$httpCode);return $result;}
