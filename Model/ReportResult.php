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
     * @var int|null
     */
    public $AccountId;

    /**
     * @var int|null
     */
    public $ContactId;

    /**
     * @var string|null
     */
    public $ContactUniqueIdentifier;

    /**
     * @var string|null
     */
    public $FirstName;

    /**
     * @var string|null
     */
    public $LastName;

    /**
     * @var string|null
     */
    public $Email;

    /**
     * @var string|null
     */
    public $Status;

    /**
     * @var string|null
     */
    public $creationMethod;

    /**
     * @var string|null
     */
    public $EmailFormat;

    /**
     * @var \DateTime|null
     */
    public $DateCreatedUTC;

    /**
     * @var bool|null
     */
    public $hbOnUpload;

    /**
     * @var bool|null
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
