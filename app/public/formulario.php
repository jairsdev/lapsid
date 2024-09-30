<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Simples</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<script>
    $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "/usuario?action=" + encodeURIComponent("login"), 
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#registerResponse').text('Usuário cadastrado com sucesso! Redirecionando para login...');
                        } else {
                            $('#registerResponse').text(response.message);
                        }
                    }
                });
            });
        });
    </script>
<body>
    <h1>Formulário de Cadastro</h1>
    <form id='registerForm'>
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="login">Login:</label><br>
        <input type="text" id="login" name="login" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

        <p id="registerResponse"></p>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>