<?php
declare(strict_types=1);

use Common\CsvToArray;
use Invoice\CurrencyList;
use Invoice\Currency;
use Invoice\Invoice;

require 'includes/Common/Autoloader.php';
\Common\Autoloader::registerAutoload();

ob_start();

$currencies = CurrencyList::getCurrencyList();
$currentCurrency = '';

$results = [];

try {
    
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
        $currentCurrency = filter_input(INPUT_POST, 'currency');
        //import file
        $file = $_FILES['csvFile']['tmp_name'];
        $extension = pathinfo($_FILES["csvFile"]["name"], PATHINFO_EXTENSION);
        if(file_exists($file) && strtolower($extension) == 'csv' ) {
           $results = new CsvToArray($file);
           if(!empty($results)) {
                $instance = Invoice::instance();
                $instance->setDefaultCurrency($currentCurrency);
                $instance->setData($results);
                $instance->setCurrencies([
                    new Currency('EUR', 1),
                    new Currency('USD', 0.987),
                    new Currency('GBP', 0.878),
                ]);
                $results = $instance->getTotals();
            }
        } else {
            throw new \Exception('Upload Failed: Your file wasn\'t uploaded successfully');
        }
    }
   
} catch (\Exception $e) {
    echo $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>CSV Uploader</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="veselata" />
    <meta name="robots" content="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Invoices</h1>
    <form name="invoiceForm" method="post" role="form" action="" enctype="multipart/form-data" >
    <?php if($currencies): ?>
    <div class="form-group">    
        <label for="formFileMd" class="form-label">Select Currency</label>
        <select name="currency" class="btn btn-outline-secondary" >
            <?php foreach ($currencies as $currency) : ?>
            <option name="<?php echo $currency; ?>" value="<?php echo $currency; ?>" <?php echo ($currentCurrency === $currency)? 'selected="selected"' : ''; ?>><?php echo $currency; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?> 
    <div class="form-group">
        <label for="csvFile" class="form-label">Upload CSV File</label>
        <input class="form-control" id="csvFile" name="csvFile" type="file" />
    </div>           
    <div class="form-group float-right ">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
    
    <?php if($results): ?>    
    <h2>Results</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th>Total</th>
                    <th>Currency</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $item => $result) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item); ?></td>
                        <td><?php echo $result['Total']; ?></td>
                        <td><?php echo htmlspecialchars($result['Currency']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>    
</div>
</body>
</html>

<?php

ob_end_flush();