<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Teste Notificação Chamado</title>
</head>
<body>
    <h2>Teste de Notificações de Chamados</h2>

    <!-- Tipo 0 - Novo chamado para setor -->
    <form method="get" action="/GRNacoes/views/ajax/create_notificacao_chamado.php">
        <h3>Tipo 0 - Novo chamado para setor</h3>
        <input type="hidden" name="tipo" value="0">
        <label>ID Chamado:</label>
        <input type="number" name="id_chamado" required><br><br>
        <label>ID Setor:</label>
        <input type="number" name="id_setor" required><br><br>
        <button type="submit">Enviar Notificação</button>
    </form>
    <hr>

    <!-- Tipo 1 - Nova mensagem -->
    <form method="get" action="/GRNacoes/views/ajax/create_notificacao_chamado.php">
        <h3>Tipo 1 - Nova mensagem</h3>
        <input type="hidden" name="tipo" value="1">
        <label>ID Chamado:</label>
        <input type="number" name="id_chamado" required><br><br>
        <label>ID Usuário:</label>
        <input type="number" name="id_usuario"><br><br>
        <label>ID Setor:</label>
        <input type="number" name="id_setor"><br><br>
        <button type="submit">Enviar Notificação</button>
    </form>
    <hr>

    <!-- Tipo 2 - Status alterado -->
    <form method="get" action="/GRNacoes/views/ajax/create_notificacao_chamado.php">
        <h3>Tipo 2 - Alteração de Status</h3>
        <input type="hidden" name="tipo" value="2">
        <label>ID Chamado:</label>
        <input type="number" name="id_chamado" required><br><br>
        <label>ID Usuário:</label>
        <input type="number" name="id_usuario" required><br><br>
        <label>Status:</label>
        <input type="text" name="status" required><br><br>
        <button type="submit">Enviar Notificação</button>
    </form>
</body>
</html>
