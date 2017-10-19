<?php

namespace EXS\CampaignerBundle\Service;

/**
 * Class ContentManager
 *
 * @package EXS\CampaignerBundle\Service
 */
class ContentManager extends AbstractSoapClient
{
    /**
     * This web method adds or updates a custom email template for the Full Email Editor or the New Smart Email Builder.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 157
     *
     * @param int    $templateId
     * @param string $templateName
     * @param int    $categoryId
     * @param string $editorType
     * @param string $html
     * @param string $text
     * @param bool   $isVisible
     * @param bool   $isResponsive
     * @param array  $tags
     * @param string $description
     *
     * @return array|bool|null
     */
    public function CreateUpdateMyTemplates(
        $templateId,
        $templateName,
        $categoryId,
        $editorType,
        $html,
        $text,
        $isVisible = null,
        $isResponsive = null,
        array $tags = null,
        $description = null
    ) {
        $parameters = [
            'TemplateId' => (int)$templateId,
            'TemplateName' => (string)$templateName,
            'CategoryId' => (int)$categoryId,
            'EditorType' => (string)$editorType,
            'templateContent' => [
                'HTML' => (string)$html,
                'Text' => (string)$text,
            ],
        ];

        if (null !== $description) {
            $parameters['Description'] = (string)$description;
        }

        if (null !== $tags) {
            $parameters['Tags'] = $tags;
        }

        if (null !== $isVisible) {
            $parameters['IsVisible'] = (bool)$isVisible;
        }

        if (null !== $isResponsive) {
            $parameters['IsResponsive'] = (bool)$isResponsive;
        }

        return $this->callMethod(__FUNCTION__, $parameters);
    }

    /**
     * This web method deletes one or more media files in the image library for an account.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 161
     *
     * @param array $mediaFileIds
     *
     * @return array|bool|null
     */
    public function DeleteMediaFiles(array $mediaFileIds)
    {
        return $this->callMethod(__FUNCTION__, [
            'mediaFileIds' => $mediaFileIds,
        ]);
    }

    /**
     * This web method downloads a single HTML email template and, if available, the plain text version of the email template (base64 encoded).
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 163
     *
     * @param int $templateId
     *
     * @return array|bool|null
     */
    public function GetEmailTemplate($templateId)
    {
        return $this->callMethod(__FUNCTION__, [
            'templateId' => (int)$templateId,
        ]);
    }

    /**
     * This web method lists all available email templates for an account.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 165
     *
     * @return array|bool|null
     */
    public function ListEmailTemplates()
    {
        return $this->callMethod(__FUNCTION__);
    }

    /**
     * This web method lists the media files in the image library for an account.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 165
     *
     * @return array|bool|null
     */
    public function ListMediaFiles()
    {
        return $this->callMethod(__FUNCTION__);
    }

    /**
     * This web method lists the projects on an account.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 171
     *
     * @return array|bool|null
     */
    public function ListProjects()
    {
        return $this->callMethod(__FUNCTION__);
    }

    /**
     * This web method uploads one media file to the image library for an account, and returns identifying information (such as file name and the URL) for the media file.
     *
     * @see docs/Campaigner-Elements-API-User-Guide.pdf page 173
     *
     * @param string $localFilename
     * @param string $targetFilename
     *
     * @return array|bool|null
     */
    public function UploadMediaFile($localFilename, $targetFilename)
    {
        if (
            (false === file_exists($localFilename))
            || (false === is_readable($localFilename))
            || (false === in_array(mime_content_type($localFilename), ['application/pdf', 'image/gif', 'image/jpeg', 'image/png']))
        ) {
            throw new \RuntimeException(sprintf('Invalid file "%s".', $localFilename));
        }

        return $this->callMethod(__FUNCTION__, [
            'fileName' => $targetFilename,
            'fileContentBase64' => base64_encode(file_get_contents($localFilename)),
        ]);
    }
}
