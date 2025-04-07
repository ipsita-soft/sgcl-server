<!DOCTYPE html>
<html>
<head>
    <title>Payment Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .content {
            margin-bottom: 20px;
        }
        .content p {
            margin: 10px 0;
            color: #666;
        }
        .content strong {
            color: #000;
        }
        .billing-summary {
            margin-top: 20px;
        }
        .billing-summary h2 {
            text-align: center;
            color: #333;
        }
        .billing-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .billing-summary th, .billing-summary td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            text-align: left;
        }
        .billing-summary th {
            background-color: #f4f4f4;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
        }
        @media (max-width: 600px) {
            .header h1 {
                font-size: 24px;
            }
            .content p, .billing-summary th, .billing-summary td {
                font-size: 14px;
                padding: 6px;
            }
            .billing-summary h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Sundarban Gas Company Limited</h1>
        <p>218, M. A. Bari Sarok, Sonadanga, Khulna-9100</p>
        <p>Phone: +8802-477721299 | Fax: +8802-477723252 | Email:  md@sgcl.org.bd</p>
    </div>
    <div class="content">
        <p><strong>Date:</strong> {{$reminder->date}}</p>
        <p>Dear Member,</p>
        <p>{{$reminder->message}}</p>
        <p>Please process this invoice and prompt payment will be greatly appreciated. A self-addressed envelope is also included for your convenience.</p>
    </div>
    <div class="billing-summary">
        <h2>Billing Summary</h2>
        <p>Payment received after {{$reminder->date}} are not reflected in this statement.</p>
        <table>
            <tr>
                <th> Amount</th>
                <td>{{$reminder->amount}}Tk</td>
            </tr>
        </table>
    </div>
    <div class="footer">
        <p>If you have any questions, please contact me at +8802-477721299.</p>
        <p>Sincerely,</p>
        <p>Accounts Department</p>
    </div>
</div>
</body>
</html>
