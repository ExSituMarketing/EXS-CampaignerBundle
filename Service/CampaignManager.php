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

        if (null === in_array($campaignStatus, $campaignStatuses)) {
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

        if (null === in_array($campaignType, $campaignTypes)) {
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

        if (null === in_array($encoding, $encodings)) {
            throw new InvalidConfigurationException(sprintf('Invalid CampaignType "%s".', $encoding));
        }

        return $encoding;
    }
}
