<?php

namespace EXS\CampaignerBundle\Model;

/**
 * Class NullableString
 *
 * @package EXS\CampaignerBundle\Model
 */
class NullableString
{
    /**
     * @var bool
     */
    public $IsNull;

    /**
     * @var string
     */
    public $value;

    /**
     * NullableString constructor.
     *
     * @param bool   $IsNull
     * @param string $value
     */
    public function __construct($IsNull, $value)
    {
        $this->IsNull = (bool)$IsNull;
        $this->value = (string)$value;
    }
}
