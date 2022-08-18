<?php

	$emailTo ='prashobkthamban@gmail.com';
    $fileName = "/var/www/asterconnect_scripts/DAILY/2022-07-16_3_CDR-REPORT.csv";

    $data = [
        'to' => $emailTo,
        'subject' => "Sample Mail from Dev",
        'view' => 'emails.test',
        'heading' => 'Test Mail',
        'content' => 'Dear, Please find attached report.',
        'attachment' => $fileName,
        'fileType' => 'text/csv'
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://127.0.0.1:6002/send-mail");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);

    curl_close($ch);

    if (trim($output) == "OK") {
        echo("Message successfully sent!\n");
    } else {
        echo("Message not sent!\n");
    }
?>