<?php

function formatNo($number)
{
    return number_format($number, 0, ',', '.');
}

function formatRp($number)
{
    if ($number == 0) {
        return '-';
    }

    return 'Rp. '.formatNo($number);
}
