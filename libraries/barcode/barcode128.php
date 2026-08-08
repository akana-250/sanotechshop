<?php

class Barcode128
{
    public static function render($code)
    {
        return '
        <div style="text-align:center;margin:20px 0;">

            <div style="
                font-family:monospace;
                font-size:42px;
                letter-spacing:3px;
                font-weight:bold;
                line-height:1;
            ">
                ||| || |||| || ||||| ||| || ||||
            </div>

            <div style="
                font-size:18px;
                margin-top:8px;
                letter-spacing:2px;
                font-family:Arial;
            ">
                '.htmlspecialchars($code).'
            </div>

        </div>';
    }
}