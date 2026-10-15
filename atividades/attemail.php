
<!DOCTYPE html>
<html lang="Pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
</head>
<body>
    <h1>Formulario de Contato</h1>

    <form action="#" method="POST">
        <label for="nome">Informe seu nome:</label><br>
        <input type="text" name="nome" id="nome" required>
        <br><br>

        <label for="email">Informe seu email:</label><br>
        <input type="email" name="email" id="email" required>
        <br><br>

        <label for="mensagem">Mensagem:</label><br>
        <textarea name="mensagem" id="mensagem" required></textarea>
        <br><br>

        <button type="submit" name="enviar">Enviar</button>
    </form>

</body>
</html>

<?php
    if (isset($_POST["enviar"])) {

        require "vendor/autoload.php";

        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $mensagem = $_POST["mensagem"];

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "lucas.rvogel@unidavi.edu.br";
            $mail->Password = "YXN8CTLSC4RA";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->CharSet = "UTF-8";

            $mail->setFrom("lucas.rvogel@unidavi.edu.br");
            $mail->addAddress("lucas.rvogel@unidavi.edu.br");

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }

            $mail->addReplyTo($email, $nome);

            $mail->Subject = "Formulario de Contato";
            $mail->Body = "Nome: " . $nome .
                          "\nEmail: " . $email .
                          "\nMensagem: " . $mensagem;

            $mail->send();

            echo "Email enviado com sucesso!";
        }
        catch (Exception $e) {
            echo "Erro ao enviar o email!";
        }
    }
?>
