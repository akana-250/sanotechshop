<?php
/*
=====================================
SANO TECH SHOP ERP
GLOBAL FUNCTIONS
=====================================
*/


// Prevent duplicate loading protection
if(!function_exists('createActivityLog')){


function createActivityLog($conn, $user_id, $activity)
{

    $stmt = $conn->prepare(
        "INSERT INTO activity_logs 
        (user_id, activity)
        VALUES (?, ?)"
    );


    $stmt->execute([
        $user_id,
        $activity
    ]);

}


}




if(!function_exists('cleanInput')){


function cleanInput($data)
{

    return htmlspecialchars(
        trim($data),
        ENT_QUOTES,
        'UTF-8'
    );

}


}




if(!function_exists('redirect')){


function redirect($url)
{

    header("Location: ".$url);

    exit();

}


}





if(!function_exists('formatMoney')){


function formatMoney($amount)
{

    return number_format(
        $amount,
        2
    );

}


}





if(!function_exists('generateInvoiceNumber')){


function generateInvoiceNumber()
{

    return "INV-".date("YmdHis");

}


}





if(!function_exists('asset')){


function asset($path)
{

    return "assets/".$path;

}


}





if(!function_exists('currentYear')){


function currentYear()
{

    return date("Y");

}


}



?>