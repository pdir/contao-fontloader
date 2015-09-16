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
$dc = &$GLOBALS['TL_DCA']['tl_layout'];
/**
 * fields
 */
$dc['fields']['fontgroup'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['fontgroup'],
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_font_group.title',
    'eval'                    => array('multiple'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''",
);

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] 	= str_replace('stylesheet','stylesheet,fontgroup',$GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);
