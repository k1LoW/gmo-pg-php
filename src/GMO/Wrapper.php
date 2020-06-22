<?php

namespace GMO;

use Exception;
use GMO\Payment\ShopApi;
use GMO\Payment\SiteApi;

class Wrapper
{
  protected $shop;
  protected $site;

  private $shopId = '';
  private $shopPass = '';
  private $siteId = '';
  private $sitePass = '';
  private $host;
  private $development = true;

  public function __construct()
  {
    $this->shop = new ShopApi($this->host, $this->shopId, $this->shopPass);
    $this->site = new SiteApi($this->host, $this->siteId, $this->sitePass);
    return;
  }

  public function entryTran($purchase_id)
  {
    if (empty($purchase_id)) {
      $response['status'] = 'false';
      $response['data']['error'] = 'purchase id can\'t empty';
      echo json_encode($response, JSON_PRETTY_PRINT);
      exit();
    }

    $order_id = $purchase_id;
    $job_cd = 'AUTH';
    $amount = '';

    // additional parameter
    $data['item_code'] = '';
    $data['tax'] = '';

    try {
      $response = $this->shop->entryTran($order_id, $job_cd, $amount, $data);
      print_r($response);
    } catch (exception $e) {
      $response['status'] = 'false';
      $response['data']['error'] = $e->getMessage();
      echo json_encode($response, JSON_PRETTY_PRINT);
    }
  }

  public function execTran($purchase_id)
  {
    if (empty($purchase_id)) {
      $response['status'] = 'false';
      $response['data']['error'] = 'purchase id can\'t empty';
      echo json_encode($response, JSON_PRETTY_PRINT);
      exit();
    }

    $data['method'] = '';
    $data['pay_times'] = '';
    $data['token'] = '';
    $data['pay_times'] = '';

    try {
      $response = $this->shop->execTran('access_id', 'access_pass', 'order_id', $data);
      print_r($response);
    } catch (exception $e) {
      $response['status'] = 'false';
      $response['data']['error'] = $e->getMessage();
      echo json_encode($response, JSON_PRETTY_PRINT);
    }
  }

  public function checkDevel()
  {
    if ($this->development) {
      $this->host = 'https://pt01.mul-pay.jp/payment/';
    } else {
      $this->host = 'https://pt01.mul-pay.jp/payment/';
    }
  }
}
