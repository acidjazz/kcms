<?

namespace ctl;

class index {

  public function index() {

    $kcms = new \lib\kcms('talko');

    $data = $kcms->getConfig();

    $flat = $kcms->flatten($data);

    \lib\jade::c('index', ['flat' => $flat, 'adds' => $kcms->adds]);

  }

}
