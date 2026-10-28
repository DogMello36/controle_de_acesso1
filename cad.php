<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
require_once __DIR__ . "/config.php";
 
/* Quem já está logado não precisa se cadastrar */
 
if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}
 
$erro = "";
$nome = "";
$birthdate = "";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome      = trim($_POST["nome"] ?? "");
    $birthdate = trim($_POST["birthdate"] ?? "");
    $senha     = $_POST["senha"] ?? "";
    $confirma  = $_POST["confirma"] ?? "";
    $token     = $_POST["csrf"] ?? "";
 
    $data = DateTime::createFromFormat("Y-m-d", $birthdate);
    $dataValida = $data && $data->format("Y-m-d") === $birthdate && $data <= new DateTime("today");
 
    if (!hash_equals($_SESSION["csrf"], $token)) {
        $erro = "Sessão expirada. Recarregue a página e tente de novo.";
    } elseif ($nome === "" || $birthdate === "" || $senha === "" || $confirma === "") {
        $erro = "Preencha todos os campos.";
    } elseif (mb_strlen($nome) > 50) {
        $erro = "O usuário pode ter no máximo 50 caracteres.";
    } elseif (!$dataValida) {
        $erro = "Informe uma data de nascimento válida.";
    } elseif (strlen($senha) < 6 || strlen($senha) > 20) {
        $erro = "A senha deve ter de 6 a 20 caracteres.";
    } elseif ($senha !== $confirma) {
        $erro = "As senhas não são iguais.";
    } else {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
 
            $existe = $pdo->prepare("SELECT 1 FROM usuarios WHERE nome = ? LIMIT 1");
            $existe->execute([$nome]);
 
            if ($existe->fetch()) {
                $erro = "Já existe um usuário com esse nome. Escolha outro.";
            } else {
                // Senha salva como digitada (cabe em varchar(20))
                $ins = $pdo->prepare("INSERT INTO usuarios (nome, birthdate, senha) VALUES (?, ?, ?)");
                $ins->execute([$nome, $birthdate, $senha]);
 
                $_SESSION["flash_ok"] = "Cadastro realizado. Entre com seu usuário e senha.";
                header("Location: " . BASEURL . "login.php");
                exit;
            }
        } catch (PDOException $e) {
            $erro = "Não foi possível salvar o cadastro. Verifique o banco de dados.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar conta</title>
    <link rel="stylesheet" href="<?= BASEURL ?>css/cad.css">
</head>
<body class="login-page">
    <main class="login-card">
        <h1>Criar conta</h1>
        <p class="login-sub">Preencha os dados para acessar o sistema.</p>
 
        <?php if ($erro): ?>
            <div class="login-erro" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>
 
        <form method="post" action="<?= BASEURL ?>cad.php" novalidate>
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"]) ?>">
 
            <div class="campo">
                <label for="nome">Usuário</label>
                <input type="text" id="nome" name="nome" maxlength="50"
                       value="<?= htmlspecialchars($nome, ENT_QUOTES, "UTF-8") ?>"
                       autocomplete="username" autofocus required>
            </div>
 
            <div class="campo">
                <label for="birthdate">Data de nascimento</label>
                <input type="date" id="birthdate" name="birthdate"
                       max="<?= date("Y-m-d") ?>"
                       value="<?= htmlspecialchars($birthdate, ENT_QUOTES, "UTF-8") ?>"
                       autocomplete="bday" required>
            </div>
 
            <div class="campo">
                <label for="senha">Senha</label>
                <div class="campo-senha">
                    <input type="password" id="senha" name="senha" minlength="6" maxlength="20"
                           autocomplete="new-password" required>
                    <button type="button" class="ver-senha" data-alvo="senha" aria-label="Mostrar senha">Mostrar</button>
                </div>
                <small class="campo-dica">De 6 a 20 caracteres.</small>
            </div>
 
            <div class="campo">
                <label for="confirma">Repita a senha</label>
                <div class="campo-senha">
                    <input type="password" id="confirma" name="confirma" maxlength="20"
                           autocomplete="new-password" required>
                    <button type="button" class="ver-senha" data-alvo="confirma" aria-label="Mostrar senha">Mostrar</button>
                </div>
            </div>
 
            <button type="submit" class="btn-entrar">Criar conta</button>
        </form>
 
        <p class="login-link">Já tem conta? <a href="<?= BASEURL ?>login.php">Entrar</a></p>
    </main>
 
    <script>
        document.querySelectorAll('.ver-senha').forEach(function (botao) {
            botao.addEventListener('click', function () {
                var campo = document.getElementById(botao.dataset.alvo);
                var oculto = campo.type === 'password';
                campo.type = oculto ? 'text' : 'password';
                botao.textContent = oculto ? 'Ocultar' : 'Mostrar';
                botao.setAttribute('aria-label', oculto ? 'Ocultar senha' : 'Mostrar senha');
            });
        });
    </script>
</body>
</html>