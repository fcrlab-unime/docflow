<?php


namespace OCA\Docflow\AppInfo;


use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

use OCP\Util;

class Application extends App implements IBootstrap {

    public const APP_ID = 'docflow';

    public function __construct(array $urlParams = []) {
        parent::__construct(self::APP_ID, $urlParams);
        Util::addScript('docflow', '../static/js/texteditorpatch');
        Util::addScript('docflow', '../static/js/filescontextmenuplugin');
        Util::addStyle('docflow', '../static/css/texteditorpatch-style');
    }

    public function register(IRegistrationContext $context): void
    {
         //$context->registerDashboardWidget(ProjectassignerWidget::class);
    }

    public function boot(IBootContext $context): void
    {
        // TODO: Implement boot() method.
    }
}