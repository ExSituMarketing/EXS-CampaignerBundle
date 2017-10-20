<?php

namespace EXS\CampaignerBundle\Service;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class ListManager
 *
 * @package EXS\CampaignerBundle\Service
 */
class ListManager extends AbstractSoapClient
{
    /**
     * This web method creates a new mailing list, or renames an existing mailing list or segment.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 146
     *
     * @param string $contactGroupType
     * @param int    $contactGroupId
     * @param string $name
     * @param string $description
     * @param string $xmlContactQuery
     * @param string $samplingType
     * @param int    $sampleSize
     * @param bool   $isGroupVisible
     * @param bool   $isTempGroup
     *
     * @return array|bool|null
     */
    public function CreateUpdateContactGroups(
        $contactGroupType,
        $contactGroupId,
        $sampleSize,
        $isGroupVisible,
        $isTempGroup,
        $name = null,
        $description = null,
        $xmlContactQuery = null,
        $samplingType = null
    ) {
        $parameters = [
            'contactGroupType' => $this->validateContactGroupType($contactGroupType),
            'contactGroupId' => $contactGroupId,
            'sampleSize' => $sampleSize,
            'isGroupVisible' => $isGroupVisible,
            'isTempGroup' => $isTempGroup,
        ];

        if (null !== $name) {
            $parameters['name'] = $name;
        }

        if (null !== $description) {
            $parameters['description'] = $description;
        }

        if (
            (null !== $xmlContactQuery)
            && (true === $this->isValidXmlContactQuery($xmlContactQuery))
        ) {
            $parameters['xmlContactQuery'] = $xmlContactQuery;
        }

        if (null !== $samplingType) {
            $parameters['samplingType'] = $this->validateSamplingType($samplingType);
        }

        return $this->callMethod(__FUNCTION__, $parameters);
    }

    /**
     * This web method deletes a mailing list or static segment.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 150
     *
     * @param int $contactGroupIds
     *
     * @return array|bool|null
     */
    public function DeleteContactGroups($contactGroupIds)
    {
        return $this->callMethod(__FUNCTION__, [
            'contactGroupIds' => $contactGroupIds,
        ]);
    }

    /**
     * This web method obtains a list of descriptions for mailings, dynamic segments, or static segments.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 152
     *
     * @return array|bool|null
     */
    public function ListContactGroups()
    {
        return $this->callMethod(__FUNCTION__);
    }

    /**
     * Validates "ContactGroupType" value.
     *
     * @param string $contactGroupType
     *
     * @return string
     */
    private function validateContactGroupType($contactGroupType)
    {
        $contactGroupTypes = [
            'MailingList',
            'StaticSegment',
            'DynamicSegment',
            'CustomSegment',
        ];

        if (false === in_array($contactGroupType, $contactGroupTypes)) {
            throw new InvalidConfigurationException(sprintf('Invalid ContactGroupType "%s".', $contactGroupType));
        }

        return $contactGroupType;
    }

    /**
     * Validates "samplingType" value.
     *
     * @param string $samplingType
     *
     * @return string
     */
    private function validateSamplingType($samplingType)
    {
        $samplingTypes = [
            'None',
            'ByPercentage',
            'ByNumberOfContacts',
        ];

        if (false === in_array($samplingType, $samplingTypes)) {
            throw new InvalidConfigurationException(sprintf('Invalid samplingType "%s".', $samplingType));
        }

        return $samplingType;
    }
}
