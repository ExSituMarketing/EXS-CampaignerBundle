# EXS/CampaignerBundle



## What is this bundle doing ?

This bundle provides Campaigner APIs' as Symfony services.

See [Campaigner API User Guide](docs/Campaigner-Elements-API-User-Guide.pdf) for the list of web services and methods. 

## Installation

Download the bundle using composer

```
$ composer require exs/campaigner-bundle
```

Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new EXS\CampaignerBundle\EXSCampaignerBundle(),
        // ...
    );
}
```

## Configuration

Required configuration :

```yml
exs_campaigner:
    username: 'someusername'
    password: 'somepassword'
```

Complete configuration (with default values) :

```yml
exs_campaigner:
    username: 'someusername'
    password: 'somepassword'
    # Default values
    wsdl:
        campaign_management: 'https://ws.campaigner.com/2013/01/campaignmanagement.asmx?WSDL'
        contact_management: 'https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL'
        content_management: 'https://ws.campaigner.com/2013/01/contentmanagement.asmx?WSDL'
        list_management: 'https://ws.campaigner.com/2013/01/listmanagement.asmx?WSDL'
        smtp_management: 'https://ws.csmtp.net/2014/06/SMTPService.asmx?WSDL'
        workflow_management: 'https://ws.campaigner.com/2013/01/workflowmanagement.asmx?WSDL'
    xsd:
        contacts_search_criteria: '@EXSCampaignerBundle/Resources/xsd/ContactsSearchCriteria2.xsd'
```

## Web services and methods


* Campaign Management Web Service

  Service id : `exs_campaigner.campaign_manager`

  Methods :
  * [CreateUpdateCampaign](docs/Campaigner-Elements-API-User-Guide.pdf#page=91)
  * [DeleteCampaign](docs/Campaigner-Elements-API-User-Guide.pdf#page=98)
  * [DeleteFromEmail](docs/Campaigner-Elements-API-User-Guide.pdf#page=100)
  * [GetCampaignRunsSummaryReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=102)
  * [GetCampaignSummary](docs/Campaigner-Elements-API-User-Guide.pdf#page=110)
  * [GetTrackedLinkSummaryReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=117)
  * [GetUnsubscribeMessages](docs/Campaigner-Elements-API-User-Guide.pdf#page=120)
  * [ListCampaigns](docs/Campaigner-Elements-API-User-Guide.pdf#page=123)
  * [ListFromEmails](docs/Campaigner-Elements-API-User-Guide.pdf#page=128)
  * [ListTrackedLinksByCampaign](docs/Campaigner-Elements-API-User-Guide.pdf#page=131)
  * [ScheduleCampaign](docs/Campaigner-Elements-API-User-Guide.pdf#page=134)
  * [SendTestCampaign](docs/Campaigner-Elements-API-User-Guide.pdf#page=137)
  * [SetCampaignRecipients](docs/Campaigner-Elements-API-User-Guide.pdf#page=139)
  * [StopCampaign](docs/Campaigner-Elements-API-User-Guide.pdf#page=141)
  * [ValidateFromEmail](docs/Campaigner-Elements-API-User-Guide.pdf#page=143)


* Contact Management Web Service

  Service id : `exs_campaigner.contact_manager`

  Methods :
  * [CreateUpdateAttribute](docs/Campaigner-Elements-API-User-Guide.pdf#page=26)
  * [DeleteAttribute](docs/Campaigner-Elements-API-User-Guide.pdf#page=30)
  * [DeleteContacts](docs/Campaigner-Elements-API-User-Guide.pdf#page=32)
  * [DownloadReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=34)
  * [GetContacts](docs/Campaigner-Elements-API-User-Guide.pdf#page=37)
  * [GetUploadMassContactsResult](docs/Campaigner-Elements-API-User-Guide.pdf#page=45)
  * [GetUploadMassContactsStatus](docs/Campaigner-Elements-API-User-Guide.pdf#page=49)
  * [ImmediateUpload](docs/Campaigner-Elements-API-User-Guide.pdf#page=53)
  * [InitiateDoubleOptIn](docs/Campaigner-Elements-API-User-Guide.pdf#page=63)
  * [ListAttributes](docs/Campaigner-Elements-API-User-Guide.pdf#page=67)
  * [ListContactFields](docs/Campaigner-Elements-API-User-Guide.pdf#page=71)
  * [ListTestContacts](docs/Campaigner-Elements-API-User-Guide.pdf#page=75)
  * [ResubscribeContact](docs/Campaigner-Elements-API-User-Guide.pdf#page=78)
  * [RunReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=82)
  * [UploadMassContacts](docs/Campaigner-Elements-API-User-Guide.pdf#page=85)


* Content Management Web Service

  Service id : `exs_campaigner.content_manager`

  Methods :
  * [CreateUpdateMyTemplates](docs/Campaigner-Elements-API-User-Guide.pdf#page=157)
  * [DeleteMediaFiles](docs/Campaigner-Elements-API-User-Guide.pdf#page=161)
  * [GetEmailTemplate](docs/Campaigner-Elements-API-User-Guide.pdf#page=163)
  * [ListEmailTemplates](docs/Campaigner-Elements-API-User-Guide.pdf#page=165)
  * [ListMediaFiles](docs/Campaigner-Elements-API-User-Guide.pdf#page=168)
  * [ListProjects](docs/Campaigner-Elements-API-User-Guide.pdf#page=171)
  * [UploadMediaFile](docs/Campaigner-Elements-API-User-Guide.pdf#page=173)


* List Management Web Service

  Service id : `exs_campaigner.list_manager`

  Methods :
  * [CreateUpdateContactGroups](docs/Campaigner-Elements-API-User-Guide.pdf#page=146)
  * [DeleteContactGroups](docs/Campaigner-Elements-API-User-Guide.pdf#page=150)
  * [ListContactGroups](docs/Campaigner-Elements-API-User-Guide.pdf#page=152)


* SMTPService Web Service

  Service id : `exs_campaigner.smtp_manager`

  Methods :
  * [DownloadReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=183)
  * [GetDetailSmtpStatus](docs/Campaigner-Elements-API-User-Guide.pdf#page=185)
  * [GetSmtpActivityReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=190)
  * [GetSmtpBounceReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=195)
  * [GetSmtpReportGroupSummary](docs/Campaigner-Elements-API-User-Guide.pdf#page=200)
  * [RunReport](docs/Campaigner-Elements-API-User-Guide.pdf#page=205)


* Workflow Management Web Service

  Service id : `exs_campaigner.workflow_manager`

  Methods :
  * [ListWorkflows](docs/Campaigner-Elements-API-User-Guide.pdf#page=177)
  * [TriggerWorkflow](docs/Campaigner-Elements-API-User-Guide.pdf#page=180)
