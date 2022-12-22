<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class CategoryDescOnProductPage extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'categorydesconproductpage';
        $this->version = '1.0.0';
        $this->author = 'Piotr Chmielowiec';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Category description on product page', [], 'Modules.JedenKlucz.Admin');
        $this->ps_versions_compliancy = ['min' => '1.7.4.0', 'max' => _PS_VERSION_];

        $this->templateFile = 'module:categorydesconproductpage/categorydesconproductpage.tpl';
    }


    public function install()
    {
        return (parent::install()
            && $this->registerHook('displayProductAdditionalInfo')
        );
    }


    public function uninstall()
    {
        return parent::uninstall();
    }


    public function renderWidget($hookName, array $configuration)
    {
        $variables = $this->getWidgetVariables($hookName, $configuration);

        if(empty($variables['description'])) {
            return;
        }

        $this->smarty->assign($variables);
        
        return $this->fetch($this->templateFile);
    }


    public function getWidgetVariables($hookName, array $configuration)
    {
        $product = $configuration['product'];
        $product_id_default_category = $product->id_category_default;

        $id_lang = $this->context->language->id;
        $category = new Category($product_id_default_category, $id_lang);

        return [
            'description' => $category->description
        ];
    }
}
