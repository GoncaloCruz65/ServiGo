<?php

require_once "ligacao.php";

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Receber os dados do formulário
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $palavra_passe = $_POST["palavra_passe"] ?? "";
    $confirmar_palavra_passe = $_POST["confirmar_palavra_passe"] ?? "";

    // Validar os campos
    if (
        $nome === "" ||
        $email === "" ||
        $telefone === "" ||
        $palavra_passe === "" ||
        $confirmar_palavra_passe === ""
    ) {
        $erro = "Preenche todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Introduz um email válido.";

    } elseif ($palavra_passe !== $confirmar_palavra_passe) {
        $erro = "As palavras-passe não coincidem.";

    } elseif (strlen($palavra_passe) < 8) {
        $erro = "A palavra-passe deve ter pelo menos 8 caracteres.";

    } else {
        try {
            // Verificar se o email já existe
            $sql = "SELECT id_utilizador
                    FROM utilizadores
                    WHERE email = :email";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(["email" => $email]);

            if ($stmt->fetch()) {
                $erro = "Este email já está registado.";

            } else {
                // Proteger a palavra-passe
                $palavra_passe_hash = password_hash(
                    $palavra_passe,
                    PASSWORD_DEFAULT
                );

                // Inserir o utilizador na base de dados
                $sql = "INSERT INTO utilizadores
                        (nome, email, telefone, palavra_passe)
                        VALUES
                        (:nome, :email, :telefone, :palavra_passe)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    "nome" => $nome,
                    "email" => $email,
                    "telefone" => $telefone,
                    "palavra_passe" => $palavra_passe_hash
                ]);

                $sucesso = "Conta criada com sucesso!";
            }

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erro = "Ocorreu um erro ao criar a conta. Tenta novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta | ServiGo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main>
        <h1>Criar conta</h1>
        <p>Junta-te ao ServiGo!</p>

        <?php if ($erro !== ""): ?>
            <p style="color: red;">
                <?= htmlspecialchars($erro) ?>
            </p>
        <?php endif; ?>

        <?php if ($sucesso !== ""): ?>
            <p style="color: green;">
                <?= htmlspecialchars($sucesso) ?>
            </p>
        <?php endif; ?>

        <form action="registo.php" method="POST">

            <label for="nome">Nome completo</label>
            <input
                type="text"
                id="nome"
                name="nome"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                required
            >

            <label for="telefone">Telefone</label>
            <input
                type="tel"
                id="telefone"
                name="telefone"
                required
            >

            <label for="palavra_passe">Palavra-passe</label>
            <input
                type="password"
                id="palavra_passe"
                name="palavra_passe"
                minlength="8"
                required
            >

            <label for="confirmar_palavra_passe">
                Confirmar palavra-passe
            </label>
            <input
                type="password"
                id="confirmar_palavra_passe"
                name="confirmar_palavra_passe"
                minlength="8"
                required
            >

            <button type="submit">Criar conta</button>

        </form>

        <p>Já tens conta? <a href="login.php">Iniciar sessão</a></p>
    </main>

</body>
</html>