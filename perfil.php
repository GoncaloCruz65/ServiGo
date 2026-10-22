<?php

session_start();

// Verificar se o utilizador tem sessão iniciada
if (!isset($_SESSION["id_utilizador"])) {
    $autenticado = false;
} else {
    $autenticado = true;
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | ServiGo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <a href="index.html" class="logo">
            <img src="images/logo.png" alt="Logo ServiGo">
        </a>

        <nav class="acoes">
            <a class="botao" href="servicos.html">Serviços</a>
            <a class="botao" href="publicar_anuncio.html">Publicar anúncio</a>
            <a class="botao" href="perfil.php">Perfil</a>
            <a class="botao" href="logout.php">Terminar sessão</a>
        </nav>
    </header>

    <main>
        <?php if ($autenticado): ?>

            <h1>Olá, <?= htmlspecialchars($_SESSION["nome"]) ?>!</h1>

            <p>Bem-vindo ao teu perfil ServiGo.</p>

            <p>
                Email:
                <?= htmlspecialchars($_SESSION["email"]) ?>
            </p>

            <a href="logout.php">Terminar sessão</a>

        <?php else: ?>

            <h1>Bem-vindo ao ServiGo!</h1>

            <p>Inicia sessão para acederes ao teu perfil.</p>

            <a class="botao" href="login.html">Iniciar sessão</a>

            <a class="botao" href="registo.html">Criar conta</a>

        <?php endif; ?>
    </main>

    <footer>
        <p>2026 ServiGo</p>
        <a href="sobreNos.html">Sobre nós</a>
    </footer>

</body>
</html>