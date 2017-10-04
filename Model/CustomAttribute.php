<?php

namespace EXS\CampaignerBundle\Model;

/**
 * Class CustomAttribute
 *
 * @package EXS\CampaignerBundle\Model
 */
class CustomAttribute
{
    /**
     * @var int
     */
    public $Id;

    /**
     * @var bool
     */
    public $IsNull;

    /**
     * @var string
     */
    public $value;

    /**
     * CustomAttribute constructor.
     *
     * @param int    $Id
     * @param bool   $IsNull
     * @param string $value
     */
    public function __construct($Id, $IsNull, $value)
    {
        $this->Id = $Id;
        $this->IsNull = $IsNull;
        $this->value = $value;
    }
}
