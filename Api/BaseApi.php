<?php

class BaseApi extends Api
{
    public $apiName = 'base';


    public function getAction()
    {
        $result = Base::getList();
        return $this->response($result['content'], 200);
    }
}