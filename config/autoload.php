<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
    'Pdir',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    // Models
    'Pdir\FontGroupModel'   => 'system/modules/fontloader/models/FontGroupModel.php',
    'Pdir\FontSrcModel'     => 'system/modules/fontloader/models/FontSrcModel.php',
    // Classes
    'Pdir\FontLoader'       => 'system/modules/fontloader/classes/FontLoader.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'font_browser' => 'system/modules/fontloader/templates',
    'font_webfont' => 'system/modules/fontloader/templates/src',
    'font_typekit' => 'system/modules/fontloader/templates/src',
    'font_typekit_adv' => 'system/modules/fontloader/templates/src',
));