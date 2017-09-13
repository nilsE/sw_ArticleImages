<?php
/* 
 * Autor: netzperfekt, Nils Ehnert <ehnert@netzperfekt.de>
 *
 * Changelog:
 *
 * 1.0.0    24.02.2017  initiale Version
 * 1.0.1    22.03.2017  Bildanzeige auch in Bestellungen / Positionen
 * 1.0.2    22.03.2017  Bugfix ;-)
 * 1.0.3    27.03.2017  Bildanzeige auch in der ausklappbaren Batch-Anzeige der Bestellungen
*/

require_once __DIR__ . '/Components/CSRFWhitelistAware.php'; // Abwärtskompatibilität mit SW < 5.2, s. https://developers.shopware.com/developers-guide/csrf-protection/

class Shopware_Plugins_Backend_NetzpArticleImages_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getVersion()
    {
        return '1.0.3';
    }
    
    public function getLabel()
    {
        return 'Artikelbilder im Backend anzeigen';
    }
    
    public function install()
    {
        if (!$this->assertVersionGreaterThen('5.1.0')) {
            throw new Exception('Dieses Plugin benötigt Shopware ab Version 5.1.0');
        }

        try {
            $this->subscribeEvents();
            $this->createConfiguration();
        } 
        catch (Exception $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }

        return $this->successfulInstallation();
    }

    private function successfulInstallation() 
    {
        return array(
            'success' => true,
            'invalidateCache' => array(
                'backend',
            )
        );
    }

    public function update($oldVersion)
    {
        if (version_compare($oldVersion, '1.0.1', '<=')) {
            // next upcoming release
        }
        if (version_compare($oldVersion, '1.0.2', '<=')) {
            // next upcoming release
        }

        return $this->successfulInstallation();
    }

    public function uninstall()
    {
        return true;
    }

    public function afterInit()
    {
        Shopware()->Template()->addTemplateDir($this->Path() . 'Views/');
    }

    private function subscribeEvents() 
    {
        $this->registerController('Backend', 'NetzpArticleImages');
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Backend_ArticleList', 'onPostDispatchArticleList');
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Backend_Category', 'onPostDispatchCategoryList');
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Backend_Order', 'onPostDispatchOrder');
    }

    private function createConfiguration() 
    {
        $form = $this->Form();

        $form->setElement('number', 'netzparticleimages_height_articlelist', array(
            'label'         => 'Bildhöhe für die Artikelliste',
            'description'   => '0 = keine Anzeige des Artikel-Thumbnails (nach Änderung bitte den Cache löschen + das Backend neuladen)',
            'value'         => 100,
            'minValue'      => 0,
            'maxValue'      => 140
        ));

        $form->setElement('number', 'netzparticleimages_height_categorylist', array(
            'label'         => 'Bildhöhe für die Kategoriezuordnung',
            'description'   => '0 = keine Anzeige des Artikel-Thumbnails (nach Änderung bitte den Cache löschen + das Backend neuladen)',
            'value'         => 100,
            'minValue'      => 0,
            'maxValue'      => 140
        ));
        $form->save();
    }

    public function onPostDispatchArticleList(Enlight_Event_EventArgs $args)
    {
        $config = Shopware()->Plugins()->Backend()->NetzpArticleImages()->Config();

        if ((int)$config->netzparticleimages_height_articlelist > 0 && 
            $args->getRequest()->getActionName() === 'load') {
            Shopware()->Template()->addTemplateDir($this->Path() . 'Views/');
            $args->getSubject()->View()->extendsTemplate(
                'backend/netzp_article_images/article_list/view/main/grid.js'
            );
        }
    }

    public function onPostDispatchCategoryList(Enlight_Event_EventArgs $args)
    {
        $config = Shopware()->Plugins()->Backend()->NetzpArticleImages()->Config();

        if ((int)$config->netzparticleimages_height_categorylist > 0 && 
            $args->getRequest()->getActionName() === 'load') {
            Shopware()->Template()->addTemplateDir($this->Path() . 'Views/');
            $args->getSubject()->View()->extendsTemplate(
                'backend/netzp_article_images/category/view/category/tabs/article_mapping.js'
            );
        }
    }

    public function onPostDispatchOrder(Enlight_Event_EventArgs $args)
    {
        $config = Shopware()->Plugins()->Backend()->NetzpArticleImages()->Config();

        if ((int)$config->netzparticleimages_height_articlelist > 0 && 
            $args->getRequest()->getActionName() === 'load') {
            Shopware()->Template()->addTemplateDir($this->Path() . 'Views/');
            $args->getSubject()->View()->extendsTemplate(
                'backend/netzp_article_images/order/view/detail/position.js'
            );
        }
    }

    public function getCapabilities()
    {
        return array(
            'install'           => true,
            'update'            => true,
            'enable'            => true,
            'secureUninstall'   => false
        );
    }

    public function getInfo()
    {
        return array(
            'version'       => $this->getVersion(),
            'label'         => $this->getLabel(),
            'author'        => 'netzperfekt',
            'copyright'     => 'netzperfekt',
            'description'   => 'Artikelbilder im Backend anzeigen',
            'support'       => 'support@netzperfekt.de',
            'link'          => 'http://netzperfekt.de'
        );
    } 
}
