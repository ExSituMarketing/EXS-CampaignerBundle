<?php

namespace EXS\CampaignerBundle\Model;

/**
 * Class ReportResult
 *
 * @package EXS\CampaignerBundle\Model
 */
class ReportResult
{
    /**
     * @var int
     */
    public $AccountId;

    /**
     * @var int
     */
    public $ContactId;

    /**
     * @var string
     */
    public $ContactUniqueIdentifier;

    /**
     * @var string
     */
    public $FirstName;

    /**
     * @var string
     */
    public $LastName;

    /**
     * @var string
     */
    public $Email;

    /**
     * @var string
     */
    public $Status;

    /**
     * @var string
     */
    public $creationMethod;

    /**
     * @var string
     */
    public $EmailFormat;

    /**
     * @var \DateTime
     */
    public $DateCreatedUTC;

    /**
     * @var bool
     */
    public $hbOnUpload;

    /**
     * @var bool
     */
    public $IsTestContact;

    /**
     * @var array
     */
    public $attributes;

    /**
     * ReportResult constructor.
     *
     * @param array $values
     * @param array $attributes
     */
    public function __construct(array $values, array $attributes)
    {
        $this->AccountId = isset($values['AccountId']) ? (int)$values['AccountId'] : null;
        $this->ContactId = isset($values['ContactId']) ? (int)$values['ContactId'] : null;
        $this->ContactUniqueIdentifier = isset($values['ContactUniqueIdentifier']) ? (string)$values['ContactUniqueIdentifier'] : null;
        $this->FirstName = isset($values['FirstName']) ? (string)$values['FirstName'] : null;
        $this->LastName = isset($values['LastName']) ? (string)$values['LastName'] : null;
        $this->Email = isset($values['Email']) ? (string)$values['Email'] : null;
        $this->Status = isset($values['Status']) ? (string)$values['Status'] : null;
        $this->creationMethod = isset($values['creationMethod']) ? (string)$values['creationMethod'] : null;
        $this->EmailFormat = isset($values['EmailFormat']) ? (string)$values['EmailFormat'] : null;
        $this->DateCreatedUTC = isset($values['DateCreatedUTC']) ? new \DateTime($values['DateCreatedUTC'], new \DateTimeZone('UTC')) : null;
        $this->hbOnUpload = isset($values['hbOnUpload']) ? filter_var($values['hbOnUpload'], FILTER_VALIDATE_BOOLEAN) : null;
        $this->IsTestContact = isset($values['IsTestContact']) ? filter_var($values['IsTestContact'], FILTER_VALIDATE_BOOLEAN) : null;
        $this->attributes = $attributes;
    }
}
