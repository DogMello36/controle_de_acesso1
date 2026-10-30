<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Usa as constantes do seu config.php (DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, BASEURL) */
require_once __DIR__ . "/config.php";


/* Token CSRF */
if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

$erro = "";
$nome = "";
$ok = $_SESSION["flash_ok"] ?? "";
unset($_SESSION["flash_ok"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome  = trim($_POST["nome"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $token = $_POST["csrf"] ?? "";

    if (!hash_equals($_SESSION["csrf"], $token)) {
        $erro = "Sessão expirada. Recarregue a página e tente de novo.";
    } elseif ($nome === "" || $senha === "") {
        $erro = "Preencha o usuário e a senha.";
    } else {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE nome = ?");
            $stmt->execute([$nome]);

            $logado = null;
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $u) {
                $guardada = (string) $u["senha"];
                // Aceita senha com hash (recomendado) e texto puro (tabela atual)
                $confere = (strlen($guardada) >= 60 && str_starts_with($guardada, '$2y$'))
                    ? password_verify($senha, $guardada)
                    : hash_equals($guardada, $senha);

                if ($confere) {
                    $logado = $u;
                    break;
                }
            }

            if ($logado) {
                session_regenerate_id(true);
                $_SESSION["usuario_id"]   = (int) $logado["id"];
                $_SESSION["usuario_nome"] = $logado["nome"];
                header("Location: " . BASEURL . "custumer/index.php");
                exit;
            }

            usleep(400000); // freia tentativas em sequência
            $erro = "Usuário ou senha incorretos.";
        } catch (PDOException $e) {
            $erro = "Não foi possível conectar ao banco de dados.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLTUR</title>
    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/all.min.css">
</head>

   
<body class="login-page">
    <main class="login-card">
        <h1>Entrar</h1>
        <p class="login-sub">Use seu usuário e senha para acessar o sistema.</p>

        <?php if ($ok): ?>
            <div class="login-ok" role="status"><?= htmlspecialchars($ok, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="login-erro" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASEURL ?>index.php" autocomplete="on" novalidate>
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"]) ?>">

            <div class="campo">
                <label for="nome">Usuário</label>
                <input type="text" id="nome" name="nome" maxlength="50"
                       value="<?= htmlspecialchars($nome, ENT_QUOTES, "UTF-8") ?>"
                       autocomplete="username" autofocus required>
            </div>

            <div class="campo">
                <label for="senha">Senha</label>
                <div class="campo-senha">
                    <input type="password" id="senha" name="senha" maxlength="20"
                           autocomplete="current-password" required>
                    <button type="button" class="ver-senha" id="ver-senha" aria-label="Mostrar senha">Mostrar</button>
                </div>
            </div>

            <button type="submit" class="btn-entrar">Entrar</button>
        </form>

        <p class="login-link">Não tem conta? <a href="<?= BASEURL ?>cad.php">Criar conta</a></p>
    </main>

    <script>
        var campo = document.getElementById('senha');
        var botao = document.getElementById('ver-senha');
        botao.addEventListener('click', function () {
            var oculto = campo.type === 'password';
            campo.type = oculto ? 'text' : 'password';
            botao.textContent = oculto ? 'Ocultar' : 'Mostrar';
            botao.setAttribute('aria-label', oculto ? 'Ocultar senha' : 'Mostrar senha');
        });
    </script>
</body>
<script src="<?php echo BASEURL; ?>js/all.js"></script>
<script src="<?php echo BASEURL; ?>js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASEURL; ?>js/jquery-3.6.0.min.js"></script>
</html>