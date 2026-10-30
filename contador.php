<?php
// contador.php (UTF-8 sem BOM)
function numero_visitas(): int
{
    $arq = __DIR__ . '/../arq.in';   // ajuste: mesmo arquivo para todas as páginas
    $fp  = fopen($arq, 'c+');
    flock($fp, LOCK_EX);

    $n = (int) stream_get_contents($fp) + 1;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, (string) $n);

    flock($fp, LOCK_UN);
    fclose($fp);
    return $n;
}