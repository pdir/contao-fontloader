<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   Contao Font Loader
 * @author    develop@pdir.de
 * @license   GNU/LGPL
 * @copyright pdir / digital agentur
 */
/**
 * Namespace
 */
namespace Pdir;

/**
 * Class FontSrcModel
 */
class FontSrcModel extends \Model
{
    protected static $strTable = 'tl_font_src';

    /**
     * Find multiple font sources by their pid
     *
     * @param array $arrIds     An array of group IDs
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of font sources or null if there are no font sources
     */
    public static function findMultipleByPids(array $arrPids = array(), array $arrOptions=array())
    {
        $t = static::$strTable;
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }
        $arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");
        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.sorting";
        }
        return static::findBy($arrColumns, null, $arrOptions);
    }
}