<?php
// submit.php - spracovanie formulára s validáciou, CSRF ochranou a uložením do CSV
session_start();
header('Content-Type: application/json; charset=utf-8');

function json_error($msg){ echo json_encode(['success'=>false,'error'=>$msg]); exit; }

// CSRF
$token = $_POST['csrf_token'] ?? '';
if(!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)){
    json_error('Neplatný CSRF token.');
}

// helper sanitize
function sanitize($s){ return trim($s); }

$full_name = sanitize($_POST['full_name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone = preg_replace('/[^0-9+]/','', $_POST['phone'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$aircraft_model = $_POST['aircraft_model'] ?? '';
$visit_date = $_POST['visit_date'] ?? '';
$message = sanitize($_POST['message'] ?? '');
$consent = isset($_POST['consent_terms']) ? 'yes' : 'no';

// basic validation
if(!$full_name || !$email || !$subject || !$message || $consent !== 'yes'){
    json_error('Povinné polia nie sú vyplnené správne.');
}
if(strlen($full_name) > 100 || strlen($subject) > 120 || strlen($message) > 5000){
    json_error('Príliš dlhé vstupné údaje.');
}

// whitelist model
$allowed_models = ['A380','Eurofighter_Typhoon','F15_Eagle'];
if(!in_array($aircraft_model, $allowed_models)) $aircraft_model = 'unknown';

// visit_date validation
if($visit_date){ $d = DateTime::createFromFormat('Y-m-d', $visit_date); if(!$d) $visit_date = ''; }

// file upload handling (optional)
$uploaded_name = '';
if(!empty($_FILES['file_upload']) && $_FILES['file_upload']['error'] !== UPLOAD_ERR_NO_FILE){
    $f = $_FILES['file_upload'];
    if($f['error'] !== UPLOAD_ERR_OK) json_error('Chyba pri nahrávaní súboru.');
    if($f['size'] > 2*1024*1024) json_error('Súbor je príliš veľký.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($f['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'];
    if(!array_key_exists($mime,$allowed)) json_error('Nepovolený typ súboru.');
    $ext = $allowed[$mime];
    $base = bin2hex(random_bytes(8));
    $uploaded_name = $base . '.' . $ext;
    $dest = __DIR__ . '/../uploads/' . $uploaded_name;
    if(!move_uploaded_file($f['tmp_name'], $dest)) json_error('Nepodarilo sa uložiť súbor.');
    chmod($dest, 0640);
}

// prepare CSV line
$id = time() . '_' . bin2hex(random_bytes(4));
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$now = date('c');
$csv_line = [
    $id,
    $now,
    $ip,
    str_replace(["\r","\n"],[' ',' '], $full_name),
    $email,
    $phone,
    $subject,
    $aircraft_model,
    $visit_date,
    str_replace(["\r","\n"],[' ',' '], $message),
    $uploaded_name
];
$csv_path = __DIR__ . '/../data/submissions.csv';
$fp = fopen($csv_path, 'a');
if(!$fp) json_error('Nemožno otvoriť súbor pre zápis.');
if(!flock($fp, LOCK_EX)) json_error('Nemožno získať zámok súboru.');
$ok = fputcsv($fp, $csv_line);
flock($fp, LOCK_UN);
fclose($fp);
if(!$ok) json_error('Nepodarilo sa zapísať dáta.');

// success
echo json_encode(['success'=>true,'id'=>$id]);
