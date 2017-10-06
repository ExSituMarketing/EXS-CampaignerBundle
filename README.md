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
    wsdl:
        contact_management: 'https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL'
        campaign_management: 'https://ws.campaigner.com/2013/01/campaignmanagement.asmx?WSDL'
    xsd:
        contacts_search_criteria: '@EXSCampaignerBundle/Resources/xsd/ContactsSearchCriteria2.xsd'
```

## Web services and methods

* Contact Management Web Service

  Service id : `exs_campaigner.contact_manager`

  Methods :
  * CreateUpdateAttribute
  * DeleteAttribute
  * DeleteContacts
  * DownloadReport
  * GetContacts
  * GetUploadMassContactsResult
  * GetUploadMassContactsStatus
  * ImmediateUpload
  * InitiateDoubleOptIn
  * ListAttributes
  * ListContactFields
  * ListTestContacts
  * ResubscribeContact
  * RunReport
  * UploadMassContacts


* Campaign Management Web Service

  Service id : `exs_campaigner.campaign_manager`

  Methods :
  * CreateUpdateCampaign
  * DeleteCampaign
  * DeleteFromEmail
  * GetCampaignRunsSummaryReport
  * GetCampaignSummary
  * GetTrackedLinkSummaryReport
  * GetUnsubscribeMessages
  * ListCampaigns
  * ListFromEmails
  * ListTrackedLinksByCampaign
  * ScheduleCampaign
  * SendTestCampaign
  * SetCampaignRecipients
  * StopCampaign
  * ValidateFromEmail


* List Management Web Service

  Service id : `exs_campaigner.list_manager`

  Methods :
  * CreateUpdateContactGroups
  * DeleteContactGroups
  * ListContactGroups
  
  
* _Content Management Web Service_ (Not implemented yet)
* _Workflow Management Web Service_ (Not implemented yet)
* _SMTPService Web Service_ (Not implemented yet)
