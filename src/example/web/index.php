<?php

error_reporting( E_ALL);
ini_set('display_errors', 1);

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
date_default_timezone_set('Europe/London'); 

session_start();

require_once '../src/controller/app.php';