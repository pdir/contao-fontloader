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
 * Table tl_font_src
 */
$GLOBALS['TL_DCA']['tl_font_src'] = array(
    // Config
    'config'   => array(
        'dataContainer'    => 'Table',
        'ptable'           => 'tl_font_group',
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id'  => 'primary',
                'pid' => 'index'
            )
        )
    ),
    // List
    'list'     => array(
        'sorting'           => array(
            'mode'                  => 4,
            'fields'                => array(
                'sorting'
            ),
            'headerFields'          => array(
                'title',
                'tstamp'
            ),
            'panelLayout'           => 'filter;sort,search,limit',
            'child_record_callback' => array('tl_font_src','listFontSources'),
            'child_record_class'    => 'no_padding',
            'disableGrouping'       => true
        )
    ,
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations'        => array(
            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_font_src']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'copy'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_font_src']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ),
            'cut'    => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_font_src']['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_font_src']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_font_src']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        )
    ),
    // Palettes
    'palettes' => array(
        '__selector__' => array('sourceType'),
        'default' => '{src_legend},title,sourceType;{fontbrowser_legend},fontBrowser',
        'typekit'   => '{src_legend},title,sourceType;{typekit_legend},typekitID,typekitCode;{fontbrowser_legend},fontBrowser',
        'gfonts'     => '{src_legend},title,sourceType;{gfonts_legend},gfontsParam;{fontbrowser_legend},fontBrowser',
    ),
    // Fields
    'fields' => array(
        'id' => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array(
            'foreignKey' => 'tl_font_group.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => array(
                'type'   => 'belongsTo',
                'load'   => 'eager'
            )
        ),
        'sorting' => array(
            'sorting' => true,
            'flag'    => 2,
            'sql'     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_font_src']['title'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255,'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'sourceType' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_font_src']['sourceType'],
            'inputType' => 'select',
            'exclude'   => true,
            'flag'      => 1,
            'options'   => array('typekit','gfonts','fonts','fontdeck','wlif'),
            'reference' => &$GLOBALS['TL_LANG']['tl_font_src'],
            'eval'      => array(
                'includeBlankOption'    => true,
                'submitOnChange'        => true,
                'mandatory'             => true,
                'maxlength'             => 100,
                'tl_class'              => 'w50 wizard',
            ),
            'sql'       => "varchar(100) NOT NULL default ''"
        ),
        'fontBrowser' => array
        (
            'input_field_callback'  => array('tl_font_src', 'generateFontBrowser'),
            'eval'                  => array('doNotShow'=>true),
        ),
        // typekit
        'typekitID' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_font_src']['typekitID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'typekitCode' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_font_src']['typekitCode'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => array('tl_class'=>'w50'),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        // gfonts
        'gfontsParam' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_font_src']['gfontsParam'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_font_src extends \Backend
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generate the font browser and return it as HTML string
     * @param array
     * @return string
     */
    public function generateFontBrowser($dc, $xlabel)
    {
        $t = new FrontendTemplate('font_browser');
        return $t->parse();
    }

    /**
     * Generate a font row and return it as HTML string
     * @param array
     * @return string
     */
    public function listFontSources($arrRow)
    {
        return '<div>' . $arrRow['title'] . ' <span style="padding-left:3px;color:#b3b3b3;">[' . $arrRow['sourceType'] . ']</span></div>';
    }
}