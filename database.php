<?php
function save($table = null, $data = null)
{

	$database = open_database();

	$columns = null;
	$values = null;

	//print_r($data);

	foreach ($data as $key => $value) {
		$columns .= trim($key, "'") . ","; //.= -> concatena
		$values .= "'$value',";
		/*
			'$value' -> mesmo que o campo seja passado entre apostrofo, o bd interpreta corretamente caso o campo seja do tipo int ou outros
		*/
	}

	// remove a ultima virgula
	$columns = rtrim($columns, ',');
	$values = rtrim($values, ',');

	$sql = "INSERT INTO $table ($columns) VALUES ($values);";

	try {
		$database->query($sql); // executa a query

		$_SESSION["message"] = "Registro cadastrado com sucesso.";
		$_SESSION["type"] = "success";

	} catch (Exception $e) {

		$_SESSION["message"] = "Nao foi possivel realizar a operacao.";
		$_SESSION["type"] = "danger";
	}

	close_database($database);
}
?>