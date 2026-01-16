<?php
// result.php - zobrazí uložené dáta z CSV. Ak je zadané ?id= zobrazí konkrétny záznam, inak posledný.

function e($s){ return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
$csv = __DIR__ . '/../data/submissions.csv';
$rows = [];
if(file_exists($csv)){
    $f = fopen($csv,'r');
    while(($r = fgetcsv($f)) !== false){ $rows[] = $r; }
    fclose($f);
}
$target_id = $_GET['id'] ?? '';
$found = null;
if($target_id){
    foreach($rows as $r){ if(isset($r[0]) && $r[0] === $target_id){ $found = $r; break; } }
} else {
    if(count($rows) > 0) $found = $rows[count($rows)-1];
}
?><!doctype html>
<html lang="sk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Výsledok - Obľúbené lietadlá</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <main class="inner">
    <h1>Výsledok spracovania formulára</h1>
    <?php if(!$found): ?>
      <p>Neboli nájdené žiadne záznamy.</p>
    <?php else: ?>
      <dl>
        <dt>ID</dt><dd><?php echo e($found[0]); ?></dd>
        <dt>Čas</dt><dd><?php echo e($found[1]); ?></dd>
        <dt>IP</dt><dd><?php echo e($found[2]); ?></dd>
        <dt>Meno</dt><dd><?php echo e($found[3]); ?></dd>
        <dt>Email</dt><dd><?php echo e($found[4]); ?></dd>
        <dt>Telefón</dt><dd><?php echo e($found[5]); ?></dd>
        <dt>Predmet</dt><dd><?php echo e($found[6]); ?></dd>
        <dt>Model lietadla</dt><dd><?php echo e($found[7]); ?></dd>
        <dt>Dátum návštevy</dt><dd><?php echo e($found[8]); ?></dd>
        <dt>Správa</dt><dd><?php echo nl2br(e($found[9])); ?></dd>
        <dt>Priložený súbor</dt><dd><?php if($found[10]){ echo '<a href="../uploads/'.e($found[10]).'" target="_blank">Stiahnuť</a>'; } else echo 'Žiadny'; ?></dd>
      </dl>
    <?php endif; ?>
    <p><a href="../index.html">Späť na domov</a></p>
  </main>
</body>
</html>

