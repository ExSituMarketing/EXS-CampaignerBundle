<?php

namespace EXS\CampaignerBundle\Service;

/**
 * Class SoapClient
 *
 * @package EXS\CampaignerBundle\Service
 */
class ContactManager extends AbstractSoapClient
{
    /**
     * This web method adds a custom contact attribute, or updates a default or custom contact attribute for all contacts.
     *
     * @param int    $attributeId
     * @param string $attributeName
     * @param string $attributeType
     * @param string $defaultValue
     * @param bool   $clearDefault
     *
     * @return mixed|null
     */
    public function CreateUpdateAttribute(
        $attributeId,
        $attributeName,
        $attributeType,
        $defaultValue = '',
        $clearDefault = true
    ) {
        $parameters = [
            'attributeId' => $attributeId,
            'attributeName' => $attributeName,
            'attributeType' => $attributeType,
            'clearDefault' => $clearDefault,
        ];

        if (false === $clearDefault) {
            $parameters['defaultValue'] = (string)$defaultValue;
        }

        return $this->callMethod(__FUNCTION__, $parameters);
    }

    /**
     * This web method lists all contact attributes and their properties (such as the identifier and type).
     *
     * @param bool $includeAllDefaultAttributes
     * @param bool $includeAllCustomAttributes
     * @param bool $includeAllSystemAttributes
     *
     * @return mixed|null
     */
    public function ListAttributes(
        $includeAllDefaultAttributes = true,
        $includeAllCustomAttributes = true,
        $includeAllSystemAttributes = true
    ) {
        return $this->callMethod(__FUNCTION__, [
            'IncludeAllDefaultAttributes' => $includeAllDefaultAttributes,
            'IncludeAllCustomAttributes' => $includeAllCustomAttributes,
            'IncludeAllSystemAttributes' => $includeAllSystemAttributes,
        ]);
    }

    /**
     * This web method deletes an existing custom attribute.
     *
     * @param int $id
     *
     * @return mixed|null
     */
    public function DeleteAttribute($id)
    {
        return $this->callMethod(__FUNCTION__, [
            'id' => $id,
        ]);
    }

    /**
     * This web method deletes one or more specified contacts.
     *
     * @param array $contactKeys
     *
     * @return mixed|null
     */
    public function DeleteContacts(array $contactKeys)
    {
        $contactKeys = $this->validateContactKeys($contactKeys);

        if (true === empty($contactKeys)) {
            return null;
        }

        return $this->callMethod(__FUNCTION__, [
            'contactKeys' => $contactKeys,
        ]);
    }

    /**
     * This web method returns information about attributes for up to 1000 specified contacts.
     *
     * @param array $contactKeys
     * @param bool  $includeStaticAttributes
     * @param bool  $includeCustomAttributes
     * @param bool  $includeSystemAttributes
     * @param bool  $includeGroupMembershipsAttributes
     *
     * @return mixed|null
     */
    public function GetContacts(
        array $contactKeys,
        $includeStaticAttributes = false,
        $includeCustomAttributes = false,
        $includeSystemAttributes = false,
        $includeGroupMembershipsAttributes = false
    ) {
        $contactKeys = $this->validateContactKeys($contactKeys);

        if (true === empty($contactKeys)) {
            return null;
        }

        return $this->callMethod(__FUNCTION__, [
            'contactKeys' => $contactKeys,
            'contactInformationFilter' => [
                'IncludeStaticAttributes' => (bool)$includeStaticAttributes,
                'IncludeCustomAttributes' => (bool)$includeCustomAttributes,
                'IncludeSystemAttributes' => (bool)$includeSystemAttributes,
                'IncludeGroupMembershipsAttributes' => (bool)$includeGroupMembershipsAttributes,
            ]
        ]);
    }

    /**
     * This web method lists all contact fields and their properties (such as the identifier and type).
     *
     * @param bool $includeAllDefaultAttributes
     * @param bool $includeAllCustomAttributes
     * @param bool $includeAllSystemAttributes
     *
     * @return mixed|null
     */
    public function ListContactFields(
        $includeAllDefaultAttributes = true,
        $includeAllCustomAttributes = true,
        $includeAllSystemAttributes = true
    ) {
        return $this->callMethod(__FUNCTION__, [
            'IncludeAllDefaultAttributes' => $includeAllDefaultAttributes,
            'IncludeAllCustomAttributes' => $includeAllCustomAttributes,
            'IncludeAllSystemAttributes' => $includeAllSystemAttributes,
        ]);
    }

    /**
     * @param array $contactKeys
     *
     * @return array
     */
    private function validateContactKeys(array $contactKeys)
    {
        $contactKeys = array_map(function ($record) {
            if (isset($record['ContactUniqueIdentifier'])) {
                if (isset($record['ContactId'])) {
                    return [
                        'ContactId' => (int)$record['ContactId'],
                        'ContactUniqueIdentifier' => (string)$record['ContactUniqueIdentifier'],
                    ];
                } else {
                    return [
                        'ContactId' => 0,
                        'ContactUniqueIdentifier' => (string)$record['ContactUniqueIdentifier'],
                    ];
                }
            }

            return null;
        }, $contactKeys);

        $contactKeys = array_filter($contactKeys, function ($record) {
            return (null !== $record);
        });

        return $contactKeys;
    }
}
