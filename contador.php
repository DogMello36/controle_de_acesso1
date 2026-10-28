<?php 
// Obtêm o número de visitas Atual --> lê o arquivo
$numero_visitas_atual = file_get_contents('arq.in');//lê o conteúdo do arquivo arq.in

 
file_put_contents('arq.in', ++$numero_visitas_atual); //gravar o conteúdo do arquivo arq.in

// Mostra o número de visitas
echo '<h1>Nº de Visitas:'.file_get_contents('arq.in').'</h1>';
?>