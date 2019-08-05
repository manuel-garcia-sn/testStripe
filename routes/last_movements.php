<?php

session_start();

require_once('../vendor/autoload.php');

use Carbon\Carbon;

\Stripe\Stripe::setApiKey("sk_test_kM45oaKwNSFt3DxZ88Snwar0");

?>

<html>
<head>
    <title>IBAN Element Example</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
<div class="wrapper">
    <h3>Last movements for customer `<?= $_SESSION['customer'] ?>`</h3>

    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Amount</th>
                <th>Receipt url</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody id="movements-table">
            <?php
            foreach (\Stripe\Charge::all(['customer' => $_SESSION['customer']]) as $charge) {
                echo '<tr>';
                echo '<td>' . $charge->amount . ' ' . $charge->currency .  '</td>';
                echo '<td><a href="' . $charge->receipt_url .'" target="_blank">Receipt</a></td>';
                echo '<td>' . Carbon::createFromTimestamp($charge->created)->diffForHumans() . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <hr>

    <h3>Make a payment (Turn up the sound)</h3>
    <form class="payment-form">
        <div class="row">
            <div class="col-xs-8">
                <label for="amount">Amount</label>
                <input type="number" id="amount" required>
            </div>
            <div class="col-xs-4">
                <label for="currency">Currency</label>
                <select name="currency" id="currency" required>
                    <option value="">Select currency</option>
                    <option value="usd">Usd</option>
                    <option value="eur">Eur</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button id="payment" type="button" class="btn btn-block btn-info" onclick="makePayment()">Payment</button>
            </div>
        </div>
    </form>

    <hr>

    <a role="button" class="btn btn-default" href="logout.php">Salir</a>
</div>

<audio controls id="linkAudio" style="display: none">
    <source src="../caja-registradora%20dinero.mp3" type="audio/mpeg">
</audio>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js" integrity="sha256-S1J4GVHHDMiirir9qsXWc8ZWw74PHHafpsHp5PXtjTs=" crossorigin="anonymous"></script>
<script>
        function makePayment() {
            axios.get('payment2.php',{
                    params : {
                        'amount' : document.getElementById('amount').value,
                        'currency' : document.getElementById('currency').value
                    }
                })
                .then(response => {
                    console.log(response);

                    // Find a <table> element with id="myTable":
                    var table = document.getElementById("movements-table");

                    var row = table.insertRow(0);

                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);

                    cell1.innerHTML = response.data.amount;
                    cell2.innerHTML = '<a href="' + response.data.receipt_url + '">Receipt</a>';
                    cell3.innerHTML = 'Just now';

                    document.getElementById("linkAudio").play();
                })
                .catch(e => {
                    // Capturamos los errores
                });
        }
</script>
</body>
</html>