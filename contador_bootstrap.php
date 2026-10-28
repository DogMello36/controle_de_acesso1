<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Contador de Visitas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white">
    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">
        <div class="card bg-secondary text-white shadow-lg p-4">
            <div class="card-body text-center">
                <?php 
                // Obtém o número de visitas atual
                $arquivo = 'arq.in';
                $numero_visitas_atual = file_exists($arquivo) ? file_get_contents($arquivo) : 0;
                
                // Incrementa e salva o novo número de visitas
                file_put_contents($arquivo, ++$numero_visitas_atual);
                ?>
                <h1 class="display-4">Nº de Visitas:</h1>
                <h2 class="badge badge-light p-3"> <?php echo $numero_visitas_atual; ?> </h2>
                <br><br>
                <a href="site_informatica.php" class="btn btn-primary mt-3">Acesse o site de Informática</a>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
