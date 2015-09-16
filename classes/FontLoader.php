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
 * Class FontLoader
 *
 * @copyright  pdir / digital agentur
 * @author     develop@pdir.de
 * @package    Contao Font Loader
 */
class FontLoader extends \Frontend
{

    /**
     * Singleton
     */
    private static $instance = null;
    /**
     * Get the singleton instance.
     *
     * @return \Pdir\FontLoader
     */
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new FontLoader();
            // remember cookie FE_PREVIEW state
            $fePreview = \Input::cookie('FE_PREVIEW');
            // set into preview mode
            \Input::setCookie('FE_PREVIEW', true);
            // request the BE_USER_AUTH login status
            static::setDesignerMode(self::$instance->getLoginStatus('BE_USER_AUTH'));
            // restore previous FE_PREVIEW state
            \Input::setCookie('FE_PREVIEW', $fePreview);
        }
        return self::$instance;
    }

    /**
     * Set designer mode.
     */
    public static function setDesignerMode($designerMode = true)
    {
        static::getInstance()->blnLiveMode = !$designerMode;
    }

    /**
     * Add font tags
     * @param PageModel $objPage
     * @param LayoutModel $objLayout
     * @param PageRegular $objThis
     */
    public function hookGetPageLayout($objPage, &$objLayout, $objThis)
    {
        global $objPage;
        if(!$objPage) return $strBuffer;
        $objLayout = \LayoutModel::findByPk($objPage->layout);
        if(!$objLayout) return $strBuffer;
        // the dynamic script replacement array
        $arrReplace = array();
        $this->parseFontGroup($objLayout, $arrReplace);
        return $strBuffer;
    }

    protected function parseFontGroup($objLayout, &$arrReplace)
    {
        // $arrGroup = array();
        $objGroup = FontGroupModel::findMultipleByIds(deserialize($objLayout->fontgroup));

        $arrWebfontkit = array();
        while($objGroup->next())
        {
            $objFonts = FontSrcModel::findMultipleByPids(array($objGroup->id));
            while($objFonts->next())
            {
                // typekit
                if($objFonts->sourceType == 'typekit') {
                    if (!$objFonts->typekitCode) {
                        $t = new \FrontendTemplate('font_typekit');
                        $t->timeout = 3000;
                    } else {
                        $t = new \FrontendTemplate('font_typekit_adv');
                    }
                    $t->typekitID = $objFonts->typekitID;
                    $t->async = $objGroup->loadAsync;

                    if(!$objGroup->includePosition)
                        $GLOBALS['TL_HEAD'][] = $t->parse();
                    else
                        $GLOBALS['TL_BODY'][] = $t->parse();
                }
                // gfonts
                if($objFonts->sourceType == 'gfonts') {

                    if($objGroup->loadAsync) {
                        $arrWebfontkit['google'][] = str_replace("&subset=", ":", $objFonts->gfontsParam);
                    } else {
                        // @todo add support for font collection // <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto|Lato' rel='stylesheet' type='text/css'>
                        $html = '<link rel="stylesheet" href="//fonts.googleapis.com/css?' . $objFonts->gfontsParam . '">';
                        if (!$objGroup->includePosition)
                            $GLOBALS['TL_HEAD'][] = $html;
                        else
                            $GLOBALS['TL_BODY'][] = $html;
                    }
                }
            }
        }

        // webfontkit
        // @todo add support for font collection // 'Open+Sans:400,700:latin', 'Roboto::latin', 'Lato::latin'
        if(count($arrWebfontkit)) {
            $t = new \FrontendTemplate('font_webfont');

            $strParam = '';
            foreach ($arrWebfontkit as $op => $fonts) {
                $strParam .= $op . ': { families: [ \'';
                $strParam .= implode("', '", $fonts);
                $strParam .= '\' ] }';
            }
            $strParam = str_replace("&subset=", ":", $strParam);
            $t->config = $strParam;
            $html = $t->parse();

            if (!$objGroup->includePosition)
                $GLOBALS['TL_HEAD'][] = $html;
            else
                $GLOBALS['TL_BODY'][] = $html;
        }

        $objGroup->reset();
    }

    protected static function addCssFileToGroup($path, $groupId)
    {
        // create Files Model
        $objFile = new \File($path);
        if (!in_array(strtolower($objFile->extension), array('css', 'less'))) return false;
        $objFile->close();
        $objFileModel = new ExtCssFileModel();
        $objFileModel->pid = $groupId;
        $objFileModel->tstamp = time();
        $objNextSorting = \Database::getInstance()->prepare("SELECT MAX(sorting) AS sorting FROM tl_extcss_file WHERE pid=?")
            ->execute($groupId);
        $objFileModel->sorting = (intval($objNextSorting->sorting) + 64);
        $objFileModel->src = $objFile->getModel()->uuid;
        $objFileModel->save();

        return $objFileModel;
    }
}