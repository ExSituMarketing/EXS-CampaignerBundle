<?php

namespace EXS\CampaignerBundle\Service;

/**
 * Class SmtpManager
 *
 * @package EXS\CampaignerBundle\Service
 */
class SmtpManager extends AbstractSoapClient
{
    /**
     * This web method returns various untyped reports based on the contacts obtained using the RunReport web method.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 183
     *
     * @param string $reportTicketId
     * @param int    $fromRow
     * @param int    $toRow
     * @param string $reportType
     *
     * @return mixed|null
     */
    public function DownloadReport($reportTicketId, $fromRow = 1, $toRow = 100, $reportType = null)
    {
        $parameters = [
            'reportTicketId' => $reportTicketId,
            'fromRow' => $fromRow,
            'toRow' => $toRow,
        ];

        if (null !== $reportType) {
            $parameters['reportType'] = $this->validateReportType($reportType);
        }

        return $this->callMethod(__FUNCTION__, $parameters);
    }

    /**
     * This web method returns a report with details about activity for SMTP emails that have been sent, such as the emails that were sent and their recipients.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 183
     *
     * @param array     $reportGroupIds
     * @param array     $reportGroupNames
     * @param array     $userNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array|bool|null
     */
    public function GetDetailSmtpStatus(
        array $reportGroupIds = null,
        array $reportGroupNames = null,
        array $userNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        return $this->callMethod(__FUNCTION__, $this->getReportParameters(
            $reportGroupIds,
            $reportGroupNames,
            $userNames,
            $fromDate,
            $toDate
        ));
    }

    /**
     * This web method returns a report with details about email activity for SMTP emails that have been sent, such as the opens, click, or replies.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 190
     *
     * @param array     $reportGroupIds
     * @param array     $reportGroupNames
     * @param array     $userNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array|bool|null
     */
    public function GetSmtpActivityReport(
        array $reportGroupIds = null,
        array $reportGroupNames = null,
        array $userNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        return $this->callMethod(__FUNCTION__, $this->getReportParameters(
            $reportGroupIds,
            $reportGroupNames,
            $userNames,
            $fromDate,
            $toDate
        ));
    }

    /**
     * This web method returns a report about which SMTP emails were sent, but bounced.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 195
     *
     * @param array     $reportGroupIds
     * @param array     $reportGroupNames
     * @param array     $userNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array|bool|null
     */
    public function GetSmtpBounceReport(
        array $reportGroupIds = null,
        array $reportGroupNames = null,
        array $userNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        return $this->callMethod(__FUNCTION__, $this->getReportParameters(
            $reportGroupIds,
            $reportGroupNames,
            $userNames,
            $fromDate,
            $toDate
        ));
    }

    /**
     * This web method returns a report with summary information about activity and delivery for
     * SMTP emails that have been sent in a specified SMTP report groups, and optionally, by SMTP users.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 200
     *
     * @param array     $reportGroupIds
     * @param array     $reportGroupNames
     * @param array     $userNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array|bool|null
     */
    public function GetSmtpReportGroupSummary(
        array $reportGroupIds = null,
        array $reportGroupNames = null,
        array $userNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        return $this->callMethod(__FUNCTION__, $this->getReportParameters(
            $reportGroupIds,
            $reportGroupNames,
            $userNames,
            $fromDate,
            $toDate
        ));
    }

    /**
     * This web method processes an XML query string to obtain rows of contact information, which are then stored on Campaigner®.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 205
     *
     * @param string $xmlContactQuery
     *
     * @return array|bool|null
     */
    public function RunReport($xmlContactQuery)
    {
        if (false === $this->isValidXmlContactQuery($xmlContactQuery)) {
            return false;
        }

        return $this->callMethod(__FUNCTION__, [
            'xmlContactQuery' => $xmlContactQuery,
        ]);
    }

    /**
     * Formats parameters' array for report queries.
     *
     * @param array     $reportGroupIds
     * @param array     $reportGroupNames
     * @param array     $userNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array
     */
    private function getReportParameters(
        array $reportGroupIds = null,
        array $reportGroupNames = null,
        array $userNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        $parameters = [];

        $campaignFilterSmtp = [];

        if (null !== $reportGroupIds) {
            $campaignFilterSmtp['ReportGroupIds'] = $reportGroupIds;
        }

        if (null !== $reportGroupNames) {
            $campaignFilterSmtp['ReportGroupNames'] = $reportGroupNames;
        }

        if (null !== $userNames) {
            $campaignFilterSmtp['UserNames'] = $userNames;
        }

        if (false === empty($campaignFilterSmtp)) {
            $parameters['campaignFilterSmtp'] = $campaignFilterSmtp;
        }

        $dateTimeFilter = [];

        if (null !== $fromDate) {
            $dateTimeFilter['FromDate'] = $this->getUtcDatetime($fromDate);
        }

        if (null !== $toDate) {
            $dateTimeFilter['ToDate'] = $this->getUtcDatetime($toDate);
        }

        if (false === empty($dateTimeFilter)) {
            $parameters['dateTimeFilter'] = $dateTimeFilter;
        }

        return $parameters;
    }
}
