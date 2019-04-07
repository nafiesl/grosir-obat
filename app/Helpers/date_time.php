<?php

function date_id($date)
{
    if (is_null($date) || $date == '0000-00-00') {
        return '-';
    }

    $explodedDate = explode('-', $date);

    if (count($explodedDate) == 3 && checkdate($explodedDate[1], $explodedDate[2], $explodedDate[0])) {
        $months = get_months();

        return $explodedDate[2].' '.$months[$explodedDate[1]].' '.$explodedDate[0];
    }

    throw new App\Exceptions\InvalidDateException('Invalid date format.');
}

function month_number($number)
{
    return str_pad($number, 2, '0', STR_PAD_LEFT);
}

function month_id($month_number)
{
    if (is_null($month_number)) {
        return $month_number;
    }

    $months = get_months();
    $month_number = month_number($month_number);

    return $months[$month_number];
}

function get_months()
{
    return [
        '01' => __('time.months.01'),
        '02' => __('time.months.02'),
        '03' => __('time.months.03'),
        '04' => __('time.months.04'),
        '05' => __('time.months.05'),
        '06' => __('time.months.06'),
        '07' => __('time.months.07'),
        '08' => __('time.months.08'),
        '09' => __('time.months.09'),
        '10' => __('time.months.10'),
        '11' => __('time.months.11'),
        '12' => __('time.months.12'),
    ];
}

function getYears()
{
    $yearRange = range(2017, date('Y'));
    foreach ($yearRange as $year) {
        $years[$year] = $year;
    }

    return $years;
}

function monthDateArray($year, $month)
{
    $dateCount = Carbon\Carbon::parse($year.'-'.$month)->format('t');
    $dates = [];
    foreach (range(1, $dateCount) as $dateNumber) {
        $dates[] = str_pad($dateNumber, 2, '0', STR_PAD_LEFT);
    }

    return $dates;
}
