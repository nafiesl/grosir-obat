<?php

function format_no($number)
{
    return number_format($number, 0, ',', '.');
}

function format_rp($number)
{
    if ($number == 0) {
        return 0;
    }

    return 'Rp. '.format_no($number);
}

/**
 * Overide Laravel Collective  link_to_route helper function.
 *
 * @param string $name       Name of route
 * @param string $title      Text that displayed on view
 * @param array  $parameters URL Parameter
 * @param array  $attributes The anchor tag atributes
 */
function html_link_to_route($name, $title = null, $parameters = [], $attributes = [])
{
    if (array_key_exists('icon', $attributes)) {
        $title = '<i class="fa fa-'.$attributes['icon'].'"></i> '.$title;
    }

    return app('html')->decode(link_to_route($name, $title, $parameters, $attributes));
}

function format_size_units($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
