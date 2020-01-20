<?php

namespace Payroll\Factories;

/**
 * Class HTMLElementsFactory
 *
 * @category PHP
 * @package  Payroll\Factories
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

class HTMLElementsFactory
{
    const
        TEXT = 'textbox',
        CHECKBOX = 'checkbox',
        DATE = 'date',
        NUMERIC = 'numeric',
        SELECT = 'select';

    public static function get($type, $id, $name)
    {
        return self::$type($id, $name);
    }

    private static function checkbox($itemId, $name, $text = 'Assign')
    {
        return view('smodav.HTMLElements.checkbox')
            ->withName($name)
            ->withId($itemId)
            ->withText($text);
    }

    private static function textbox($itemId, $name)
    {
        return view('smodav.HTMLElements.text')
            ->withName($name)
            ->withId($itemId);
    }
    
    private static function numeric($itemId, $name)
    {
        return view('smodav.HTMLElements.numeric')
            ->withName($name)
            ->withId($itemId);
    }

}
