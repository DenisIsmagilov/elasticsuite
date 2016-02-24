<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile_ElasticSuite________
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\ElasticSuiteCore\Controller\Adminhtml\Relevance\Config;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker;
use Magento\Config\Model\Config\Structure;
use Smile\ElasticSuiteCore\Model\Relevance\Config;

/**
 * Relevance configuration edit action
 *
 * @category Smile
 * @package  Smile_ElasticSuiteCore
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Edit extends AbstractScopeConfig
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Class constructor
     *
     * @param Context              $context           Action context
     * @param Structure            $configStructure   Relevance configuration Structure
     * @param ConfigSectionChecker $sectionChecker    Configuration Section Checker
     * @param Config               $backendConfig     Configuration model
     * @param PageFactory          $resultPageFactory Magento Page Factory
     */
    public function __construct(
        Context $context,
        Structure $configStructure,
        ConfigSectionChecker $sectionChecker,
        Config $backendConfig,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context, $configStructure, $sectionChecker, $backendConfig);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Edit configuration section
     *
     * @return \Magento\Framework\App\ResponseInterface|void
     */
    public function execute()
    {
        $current = $this->getRequest()->getParam('section');
        $container = $this->getRequest()->getParam('container');
        $store = $this->getRequest()->getParam('store');

        /** @var $section \Magento\Config\Model\Config\Structure\Element\Section */
        $section = $this->configStructure->getElement($current);

        if ($current && !$section->isVisible($container, $store)) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $redirectResult */
            $redirectResult = $this->resultRedirectFactory->create();

            return $redirectResult->setPath('adminhtml/*/', ['container' => $container, 'store' => $store]);
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Smile_ElasticSuiteCore::manage_relevance');
        $resultPage->getLayout()->getBlock('menu')->setAdditionalCacheKeyInfo([$current]);
        $resultPage->addBreadcrumb(__('Search Engine'), __('Relevance'));
        $resultPage->getConfig()->getTitle()->prepend(__('Relevance configuration'));

        return $resultPage;
    }
}

