<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>

    body {
        font-size:13px;
    }
    table {
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    .title {
        text-align: center;
    }

    .inline { 
    display: inline-block; 
    /* border: 1px solid red;  */
    }

    .underline {
        /* text-decoration: underline; */
        border-bottom: 1px solid black;

    }

    .heads {
        width: 300px;
        min-width: 300px;
        border-bottom: 1px solid black;
    }

    </style>
    <title>Document</title>
</head>
<body>
    <div class="title">
        <img src="/images/phapcares_logo.png">
        <h2>NOTICE OF PRODUCT DONATION</h2>
        <p>PLEASE SEND VIA EMAIL: secretariat@phapcares.org</p>
        <!-- <p>{{ asset('images/phapcares_logo.png') }}</p> -->
    </div>
    <div>
        <div class="heads">
        TO: <span class="">Sanofi Pharmaceuticals</span>
        </div>
    </div>

    <div>
        FROM: <span class="underline">phapadmin@phap.ph.com</span>
    </div>

    <div>
        CC: <span class="underline">phapadmin@phap.ph.com</span>
    </div>

    <div>
        DATE: <span class="underline">September 20,2022</span>
    </div>

    <p>We wish to inform you that the following medicines are available for donation to PHAPCares Foundation, Inc.</p>
    <table>
        <tr>
            <th>Brand Name</th>
            <th>Generic Name</th>
            <th>Strength</th>
            <th>Dosage Form</th>
            <th>Package Size</th>
            <th>Qty.</th>
            <th>Lot / Batch No.</th>
            <th>Expiry Date</th>
            <th>Trade Price</th>
            <th>Total</th>
        </tr>
        <tr>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
            <td>Alfreds Futterkiste</td>
        </tr>
    </table>

    <p>TOTAL AMOUNT OF DONATIONS</p>

    <p>Status of Medicines (Indicate Location, Schedule of Pick-up and contact details from Principal)</p>

    <p>We wish to remind you that these medicines should not be dispensed to the patients after their expiry dates.  
    Kindly affix your signature below the dotted line to acknowledge receipt of the aforementioned donations in kind.
    </p>

    <a href="/invoice-download">Download PDF</a>

</body>
</html>