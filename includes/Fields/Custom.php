<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_CustomFieldValidation_Fields_Custom extends NF_Abstracts_Input
{
    protected $_name = 'custom';

    protected $_nicename = 'Custom';

    protected $_section = 'misc';

    protected $_type = 'hidden';

    protected $_templates = array( 'custom' );

    protected $_wrap_template = 'wrap-no-label';

    protected $_settings_only = array(
        'key', 'required'
    );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Custom', 'ninja-forms' );
    }
}
