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
