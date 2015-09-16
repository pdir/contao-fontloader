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
 * Class FontGroupModel
 */
class FontGroupModel extends \Model
{
    protected static $strTable = 'tl_font_group';

    /**
     * Find multiple font groups by their IDs
     *
     * @param array $arrIds     An array of group IDs
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of font groups or null if there are no font groups
     */
    public static function findMultipleByIds($arrIds, array $arrOptions=array())
    {
        if (!is_array($arrIds) || empty($arrIds))
        {
            return null;
        }
        $t = static::$strTable;
        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = \Database::getInstance()->findInSet("$t.id", $arrIds);
        }
        return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
    }
}