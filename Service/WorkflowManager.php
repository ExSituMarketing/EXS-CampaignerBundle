<?php

namespace EXS\CampaignerBundle\Service;

/**
 * Class WorkflowManager
 *
 * @package EXS\CampaignerBundle\Service
 */
class WorkflowManager extends AbstractSoapClient
{
    /**
     * This web method returns information about each workflow, including the name, identifier, and status.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 177
     *
     * @param bool $onlyApiTriggered
     * @param bool $onlyActiveAndTest
     *
     * @return array|bool|null
     */
    public function ListWorkflows($onlyApiTriggered, $onlyActiveAndTest)
    {
        return $this->callMethod(__FUNCTION__, [
            'onlyApiTriggered' => (bool)$onlyApiTriggered,
            'onlyActiveAndTest' => (bool)$onlyActiveAndTest,
        ]);
    }

    /**
     * This web method triggers a specified workflow for selected contacts.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 180
     *
     * @param int    $workflowId
     * @param string $xmlContactQuery
     *
     * @return array|bool|null
     */
    public function TriggerWorkflow($workflowId, $xmlContactQuery)
    {
        if (false === $this->isValidXmlContactQuery($xmlContactQuery)) {
            return false;
        }

        return $this->callMethod(__FUNCTION__, [
            'workflowId' => (int)$workflowId,
            'xmlContactQuery' => (string)$xmlContactQuery,
        ]);
    }
}
