<?php

namespace LumengPHP\Command;

use \LumengPHP\Kernel\AppContext;
use \LumengPHP\Kernel\HttpRequest;
use \LumengPHP\Kernel\Result;

/**
 * 
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class CommandBase implements Command {

    /**
     * @var AppContext
     */
    protected $appContext;

    /**
     * @var HttpRequest 
     */
    protected $req;

    public function setAppContext(AppContext $appContext) {
        $this->appContext = $appContext;
    }

    public function setRequest(HttpRequest $request) {
        $this->req = $request;
    }

    public function init() {
        
    }

    /**
     * 
     * @param string $msg
     * @param array $more
     * @return Result
     */
    protected function makeSuccessResult($msg = '', array $more = array()) {
        return new Result(Result::SUCCESS, $msg, $more);
    }

    /**
     * 
     * @param string $msg
     * @param array $more
     * @return Result
     */
    protected function makeFailedResult($msg = '', array $more = array()) {
        return new Result(Result::FAILURE, $msg, $more);
    }

}
