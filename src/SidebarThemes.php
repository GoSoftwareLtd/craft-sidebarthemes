<?php
/**
 * Sidebar Themes plugin for Craft CMS 3.x
 *
 * Customise the look of the CP sidebar
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\sidebarthemes;

use lukeyouell\sidebarthemes\models\Settings;
use lukeyouell\sidebarthemes\assetbundles\sidebarthemes\ThemesAsset;

use Craft;
use craft\base\Plugin;
use craft\events\TemplateEvent;
use craft\web\View;

use yii\base\Event;

/**
 * Class SidebarThemes
 *
 * @author    Luke Youell
 * @package   SidebarThemes
 * @since     1.0.0
 *
 */
class SidebarThemes extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var SidebarThemes
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Fetch settings
        $settings = $this->getSettings();
        // Check theme isn't set to Craft
        $theme = $settings->theme !== 'craft' ? $settings->theme : null;

        // Require CP request
        if ((Craft::$app->getRequest()->getIsCpRequest()) and ($theme)) {
            // Load theme css before template is rendered
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    // Get view
                    $view = Craft::$app->getView();
                    // Load CSS file
                    $view->registerAssetBundle(ThemesAsset::class);
                }
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getThemes()
    {
        $themes = [
            ['value' => 'craft', 'label' => Craft::t('sidebar-themes', 'Craft (default)')],
            ['value' => 'afterglow', 'label' => Craft::t('sidebar-themes', 'Afterglow')],
            ['value' => 'arc', 'label' => Craft::t('sidebar-themes', 'Arc')],
            ['value' => 'aubergine', 'label' => Craft::t('sidebar-themes', 'Aubergine')],
            ['value' => 'autumn', 'label' => Craft::t('sidebar-themes', 'Autumn')],
            ['value' => 'bigred', 'label' => Craft::t('sidebar-themes', 'Big Red')],
            ['value' => 'bolket', 'label' => Craft::t('sidebar-themes', 'Bolket')],
            ['value' => 'chocomint', 'label' => Craft::t('sidebar-themes', 'Choco Mint')],
            ['value' => 'citrus', 'label' => Craft::t('sidebar-themes', 'Citrus')],
            ['value' => 'cobalt', 'label' => Craft::t('sidebar-themes', 'Cobalt')],
            ['value' => 'codemash', 'label' => Craft::t('sidebar-themes', 'CodeMash')],
            ['value' => 'deepblue', 'label' => Craft::t('sidebar-themes', 'Deep Blue')],
            ['value' => 'dracula', 'label' => Craft::t('sidebar-themes', 'Dracula')],
            ['value' => 'facebook', 'label' => Craft::t('sidebar-themes', 'Facebook')],
            ['value' => 'hibari', 'label' => Craft::t('sidebar-themes', 'Hibari')],
            ['value' => 'laravel', 'label' => Craft::t('sidebar-themes', 'Laravel')],
            ['value' => 'linux', 'label' => Craft::t('sidebar-themes', 'Linux')],
            ['value' => 'material', 'label' => Craft::t('sidebar-themes', 'Material')],
            ['value' => 'monument', 'label' => Craft::t('sidebar-themes', 'Monument')],
            ['value' => 'ochin', 'label' => Craft::t('sidebar-themes', 'Ochin')],
            ['value' => 'workhard', 'label' => Craft::t('sidebar-themes', 'Work Hard')],
        ];

        return $themes;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        // Get and pre-validate the settings
        $settings = $this->getSettings();
        $settings->validate();

        // Get the settings that are being defined by the config file
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->handle));

        return Craft::$app->view->renderTemplate(
            'sidebar-themes/settings',
            [
                'settings' => $this->getSettings(),
                'overrides' => array_keys($overrides),
                'themeOptions' => $this->getThemes(),
            ]
        );
    }
}