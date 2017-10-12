<?php

namespace EXS\CampaignerBundle\Service;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class CampaignManager
 *
 * @package EXS\CampaignerBundle\Service
 */
class CampaignManager extends AbstractSoapClient
{
    /**
     * This web method creates an email campaign with the information provided, such as the email addresses used in the email header,
     * the message content, and options for unsubscribing and viewing in a browser.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 91
     *
     * @param int       $id
     * @param string    $campaignName
     * @param string    $campaignSubject
     * @param string    $campaignFormat
     * @param string    $campaignStatus
     * @param string    $campaignType
     * @param string    $htmlContent
     * @param string    $txtContent
     * @param string    $fromName
     * @param int       $fromEmailId
     * @param int       $replyEmailId
     * @param bool      $trackReplies
     * @param int       $autoReplyMessageId
     * @param int       $projectId
     * @param bool      $isWelcomeCampaign
     * @param \DateTime $dateModified
     * @param array     $subscriptionSettings
     * @param array     $mailingAddressSettings
     * @param array     $socialSharingSettings
     * @param array     $viewOnlineSettings
     * @param string    $encoding
     *
     * @return mixed|null
     */
    public function CreateUpdateCampaign(
        $id,
        $campaignName,
        $campaignSubject,
        $campaignFormat,
        $campaignStatus,
        $campaignType,
        $htmlContent,
        $txtContent,
        $fromName,
        $fromEmailId,
        $replyEmailId,
        $trackReplies,
        $autoReplyMessageId,
        $projectId,
        $isWelcomeCampaign,
        \DateTime $dateModified,
        array $subscriptionSettings = null,
        array $mailingAddressSettings = null,
        array $socialSharingSettings = null,
        array $viewOnlineSettings = null,
        $encoding = 'Unspecified'
    ) {
        $campaignData = [
            'Id' => (int)$id,
            'CampaignName' => (string)$campaignName,
            'CampaignStatus' => $this->validateCampaignStatus($campaignStatus),
            'CampaignType' => $this->validateCampaignType($campaignType),
            'FromEmailId' => (int)$fromEmailId,
            'ReplyEmailId' => (int)$replyEmailId,
            'TrackReplies' => (bool)$trackReplies,
            'AutoReplyMessageId' => (int)$autoReplyMessageId,
            'ProjectId' => (int)$projectId,
            'IsWelcomeCampaign' => (bool)$isWelcomeCampaign,
            'DateModified' => $this->getUtcDatetime($dateModified),
            'Encoding' => $this->validateEncoding($encoding),
        ];

        if (false === empty($campaignSubject)) {
            $campaignData['CampaignSubject'] = (string)$campaignSubject;
        }

        if (false === empty($campaignFormat)) {
            $campaignData['CampaignFormat'] = $this->validateFormat($campaignFormat);
        }

        if (false === empty($htmlContent)) {
            $campaignData['HtmlContent'] = (string)$htmlContent;
        }

        if (false === empty($txtContent)) {
            $campaignData['TxtContent'] = (string)$txtContent;
        }

        if (false === empty($fromName)) {
            $campaignData['FromName'] = (string)$fromName;
        }

        if (null !== $subscriptionSettings) {
            $campaignData['SubscriptionSettings'] = [
                'SmfGroupId' => isset($subscriptionSettings['SmfGroupId']) ? (int)$subscriptionSettings['SmfGroupId'] : '',
                'UnsubscribeFormId' => isset($subscriptionSettings['UnsubscribeFormId']) ? (int)$subscriptionSettings['UnsubscribeFormId'] : '',
                'UnsuscribeMessageId' => isset($subscriptionSettings['UnsuscribeMessageId']) ? (int)$subscriptionSettings['UnsuscribeMessageId'] : '',
            ];
        }

        if (null !== $mailingAddressSettings) {
            $campaignData['MailingAddressSettings'] = [
                'IncludeMailingAddress' => isset($mailingAddressSettings['IncludeMailingAddress']) ? (bool)$mailingAddressSettings['IncludeMailingAddress'] : '',
                'MailingAddress' => isset($mailingAddressSettings['MailingAddress']) ? (string)$mailingAddressSettings['MailingAddress'] : '',
            ];
        }

        if (null !== $socialSharingSettings) {
            $campaignData['SocialSharingSettings'] = [
                'AllowSocialCampaign' => isset($socialSharingSettings['AllowSocialCampaign']) ? (bool)$socialSharingSettings['AllowSocialCampaign'] : '',
                'ButtonText' => isset($socialSharingSettings['ButtonText']) ? (string)$socialSharingSettings['ButtonText'] : '',
                'FormId' => isset($socialSharingSettings['FormId']) ? (int)$socialSharingSettings['FormId'] : '',
            ];
        }

        if (null !== $viewOnlineSettings) {
            $campaignData['ViewOnlineSettings'] = [
                'TextBefore' => isset($viewOnlineSettings['TextBefore']) ? (string)$viewOnlineSettings['TextBefore'] : '',
                'LinkText' => isset($viewOnlineSettings['LinkText']) ? (string)$viewOnlineSettings['LinkText'] : '',
                'TextAfter' => isset($viewOnlineSettings['TextAfter']) ? (string)$viewOnlineSettings['TextAfter'] : '',
            ];
        }

        return $this->callMethod(__FUNCTION__, [
            'campaignData' => $campaignData,
        ]);
    }

    /**
     * This web method deletes a campaign. You can choose to delete associated reports at the same time.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 98
     *
     * @param int  $campaignId
     * @param bool $deleteReports
     *
     * @return mixed|null
     */
    public function DeleteCampaign($campaignId, $deleteReports)
    {
        return $this->callMethod(__FUNCTION__, [
            'campaignId' => (int)$campaignId,
            'deleteReports' => (bool)$deleteReports,
        ]);
    }

    /**
     * This web method deletes an email address that was previously validated for use as a From email address in the email header of email campaigns.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 100
     *
     * @param string $email
     *
     * @return mixed|null
     */
    public function DeleteFromEmail($email)
    {
        return $this->callMethod(__FUNCTION__, [
            'Email' => (string)$email,
        ]);
    }

    /**
     * This web method provides information about instances of runs for specified email campaigns, optionally grouped by domain.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 102
     *
     * @param bool      $groupByDomain
     * @param array     $campaignIds
     * @param array     $campaignRunIds
     * @param array     $campaignNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array|bool|null
     */
    public function GetCampaignRunsSummaryReport(
        $groupByDomain,
        array $campaignIds = null,
        array $campaignRunIds = null,
        array $campaignNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        $parameters = [
            'groupByDomain' => (bool)$groupByDomain,
        ];

        $campaignFilter = [];

        if (null !== $campaignIds) {
            $campaignFilter['CampaignIds'] = $campaignIds;
        }

        if (null !== $campaignRunIds) {
            $campaignFilter['CampaignRunIds'] = $campaignRunIds;
        }

        if (null !== $campaignNames) {
            $campaignFilter['CampaignNames'] = $campaignNames;
        }

        if (false === empty($campaignFilter)) {
            $parameters['campaignFilter'] = $campaignFilter;
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

        return $this->callMethod(__FUNCTION__, $parameters);
    }

    /**
     * This web method returns information about a specific campaign, such as its name, addressing information, subject, and status.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 110
     *
     * @param int $campaignId
     *
     * @return array|bool|null
     */
    public function GetCampaignSummary($campaignId)
    {
        return $this->callMethod(__FUNCTION__, [
            'campaignId' => (int)$campaignId,
        ]);
    }

    /**
     * This web method returns identifying information about trackable links in a campaign, as well as related contact activity.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 117
     *
     * @param $campaignRunId
     *
     * @return array|bool|null
     */
    public function GetTrackedLinkSummaryReport($campaignRunId)
    {
        return $this->callMethod(__FUNCTION__, [
            'campaignRunId' => (int)$campaignRunId,
        ]);
    }

    /**
     * This web method obtains the identifier and contents of all unsubscribe messages associated with the account.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 120
     *
     * @return array|bool|null
     */
    public function GetUnsubscribeMessages()
    {
        return $this->callMethod(__FUNCTION__);
    }

    /**
     * This web method lists all campaigns and their properties (such as the status and type) for an account.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 123
     *
     * @param string    $campaignStatus
     * @param string    $campaignType
     * @param array     $campaignIds
     * @param array     $campaignRunIds
     * @param array     $campaignNames
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return array|bool|null
     */
    public function ListCampaigns(
        $campaignStatus,
        $campaignType,
        array $campaignIds = null,
        array $campaignRunIds = null,
        array $campaignNames = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        $parameters = [
            'campaignStatus' => $this->validateCampaignStatus($campaignStatus),
            'campaignType' => $this->validateCampaignType($campaignType),
        ];

        $campaignFilter = [];

        if (null !== $campaignIds) {
            $campaignFilter['CampaignIds'] = $campaignIds;
        }

        if (null !== $campaignRunIds) {
            $campaignFilter['CampaignRunIds'] = $campaignRunIds;
        }

        if (null !== $campaignNames) {
            $campaignFilter['CampaignNames'] = $campaignNames;
        }

        if (false === empty($campaignFilter)) {
            $parameters['campaignFilter'] = $campaignFilter;
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

        return $this->callMethod(__FUNCTION__, $parameters);
    }

    /**
     * This web method returns all the validated and pending email addresses associated with an account.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 128
     *
     * @return array|bool|null
     */
    public function ListFromEmails()
    {
        return $this->callMethod(__FUNCTION__);
    }

    /**
     * This web method returns identifying information about all tracked links, grouped by specified campaigns.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 131
     *
     * @param array $campaignIds
     *
     * @return array|bool|null
     */
    public function ListTrackedLinksByCampaign(array $campaignIds)
    {
        return $this->callMethod(__FUNCTION__, [
            'campaignIds' => $campaignIds,
        ]);
    }

    /**
     * This web method schedules a specified email campaign to be sent to selected recipients.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 134
     *
     * @param int       $campaignId
     * @param bool      $sendNow
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string    $recurrenceType
     * @param int       $occurrenceCount
     *
     * @return array|bool|null
     */
    public function ScheduleCampaign(
        $campaignId,
        $sendNow,
        \DateTime $startDate,
        \DateTime $endDate,
        $recurrenceType = 'None',
        $occurrenceCount = 0
    ) {
        return $this->callMethod(__FUNCTION__, [
            'campaignId' => (int)$campaignId,
            'sendNow' => (bool)$sendNow,
            'campaignSchedule' => [
                'StartDate' => $this->getUtcDatetime($startDate),
                'EndDate' => $this->getUtcDatetime($endDate),
                'RecurrenceType' => $this->validateRecurrenceType($recurrenceType),
                'OccurrenceCount' => (int)$occurrenceCount,
            ],
        ]);
    }

    /**
     * This web method sends a specified email campaign to selected recipients for testing purposes.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 137
     *
     * @param int    $campaignId
     * @param int    $contactId
     * @param string $contactUniqueIdentifier
     * @param array  $emails
     *
     * @return array|bool|null
     */
    public function SendTestCampaign(
        $campaignId,
        $contactId,
        $contactUniqueIdentifier,
        array $emails
    ) {
        return $this->callMethod(__FUNCTION__, [
            'campaignId' => (int)$campaignId,
            'contactKeyForTest' => [
                'ContactId' => (int)$contactId,
                'ContactUniqueIdentifier' => (string)$contactUniqueIdentifier,
            ],
            'emails' => $emails,
        ]);
    }

    /**
     * This web method adds recipients for an email campaign.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 139
     *
     * @param int   $campaignId
     * @param bool  $sendToAllContacts
     * @param array $contactGroupIds
     *
     * @return array|bool|null
     */
    public function SetCampaignRecipients(
        $campaignId,
        $sendToAllContacts,
        array $contactGroupIds
    ) {
        return $this->callMethod(__FUNCTION__, [
            'campaignId' => (int)$campaignId,
            'campaignRecipients' => [
                'SendToAllContacts' => (bool)$sendToAllContacts,
                'ContactGroupIds' => $contactGroupIds,
            ],
        ]);
    }

    /**
     * This web method stops a scheduled campaign from being sent.
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 141
     *
     * @param int $campaignId
     *
     * @return array|bool|null
     */
    public function StopCampaign($campaignId)
    {
        return $this->callMethod(__FUNCTION__, [
            'campaignId' => (int)$campaignId,
        ]);
    }

    /**
     * @param string $email
     *
     * @return array|bool|null
     */
    public function ValidateFromEmail($email)
    {
        return $this->callMethod(__FUNCTION__, [
            'email' => (string)$email,
        ]);
    }

    /**
     * Validates "CampaignStatus" value.
     *
     * @param string $campaignStatus
     *
     * @return string
     */
    private function validateCampaignStatus($campaignStatus)
    {
        $campaignStatuses = [
            'Incomplete',
            'Complete',
            'OnHold',
            'Scheduled',
            'Sent',
        ];

        if (false === in_array($campaignStatus, $campaignStatuses)) {
            throw new InvalidConfigurationException(sprintf('Invalid CampaignStatus "%s".', $campaignStatus));
        }

        return $campaignStatus;
    }

    /**
     * Validates "CampaignType" value.
     *
     * @param string $campaignType
     *
     * @return string
     */
    private function validateCampaignType($campaignType)
    {
        $campaignTypes = [
            'None',
            'OneOff',
            'Recurring',
            'Continuous',
        ];

        if (false === in_array($campaignType, $campaignTypes)) {
            throw new InvalidConfigurationException(sprintf('Invalid CampaignType "%s".', $campaignType));
        }

        return $campaignType;
    }

    /**
     * Validates "Encoding" value.
     *
     * @param string $encoding
     *
     * @return string
     */
    private function validateEncoding($encoding)
    {
        $encodings = [
            'Western_Windows_1252',
            'Unspecified',
            'Baltic_ISO_8859_4',
            'Baltic_Windows_1257',
            'Central_European_ISO_8859_16',
            'Central_European_Windows_1250',
            'Cyrillic_ISO_8859_5',
            'Cyrillic_Windows_1251',
            'Greek_ISO_8859_7',
            'Greek_Windows_1253',
            'Turkish_ISO_8859_9',
            'Turkish_Windows_1254',
            'Western_ISO_8859_15',
            'Vietnamese_Windows_1258',
        ];

        if (false === in_array($encoding, $encodings)) {
            throw new InvalidConfigurationException(sprintf('Invalid CampaignType "%s".', $encoding));
        }

        return $encoding;
    }

    /**
     * Validates "RecurrenceType" value.
     *
     * @param string $recurrenceType
     *
     * @return string
     */
    private function validateRecurrenceType($recurrenceType)
    {
        $recurrenceTypes = [
            'None',
            'Daily',
            'Weekly',
            'Monthly',
            'Annually',
        ];

        if (false === in_array($recurrenceType, $recurrenceTypes)) {
            throw new InvalidConfigurationException(sprintf('Invalid RecurrenceType "%s".', $recurrenceType));
        }

        return $recurrenceType;
    }
}
