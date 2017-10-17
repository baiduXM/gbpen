<?php

    include('phpqr/lib/full/qrlib.php');
    
    // outputs image directly into browser, as PNG stream
    //QRcode::png('PHP QR Code :)');
QRcode::png($_REQUEST["url"]);