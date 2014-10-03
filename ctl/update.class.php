<?

namespace ctl;

class update extends \lib\api {

  public function index() {

    $kcms = new \lib\kcms();
    $result = $kcms->update($_REQUEST);

    $this->result(true, 'success', ['diff' => $result['diff'], 'new' => $result['new']]);

  }

}
