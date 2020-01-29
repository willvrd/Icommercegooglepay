<?php

namespace Modules\Icommercegooglepay\Repositories\Cache;

use Modules\Icommercegooglepay\Repositories\GooglepayConfigRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheGooglepayconfigDecorator extends BaseCacheDecorator implements GooglepayConfigRepository
{
    public function __construct(GooglepayConfigRepository $googlepayconfig)
    {
        parent::__construct();
        $this->entityName = 'icommercegooglepay.googlepayconfigs';
        $this->repository = $googlepayconfig;
    }
}
