<?

namespace ctl;

class index {

  public function index() {

    $kcms = new \lib\kcms();

    $data = $kcms->getConfig('talko');

    $flat = $kcms->flatten($data);

    \lib\jade::c('index', ['flat' => $flat, 'adds' => $kcms->adds]);

  }

}
