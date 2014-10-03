<?

namespace ctl;

class diff extends \lib\api {

  public function index() {
    $kcms = new \lib\kcms('talko');
    $diff = $kcms->diff($kcms->flatten($kcms->getConfig()), $_REQUEST);
    $html = \lib\jade::c('_diff_body', ['diff' => $diff], true);
    $this->result(true, 'success', ['html' => $html, 'diff' => $diff]);
  }

}
