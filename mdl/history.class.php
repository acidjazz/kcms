<?

namespace mdl;

class history extends \klib\kcol {

  // restrict types of fields
  protected $_types = [
    'created' => 'date'
  ];

  protected $_ols = [];

  public function save($data=false, $options=[]) {

    if (!$this->exists()) {
      $this->created = new \MongoDate();
    }

    parent::save($data,$options);

  }


}


