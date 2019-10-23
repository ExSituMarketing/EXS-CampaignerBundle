Campaigner
---
### ListAttribute Method

This method gets all contact attributes and their properties, like listing columns of a table. It accepts 3 parameters:
- IncludeAllDefaultAttributes (bool)
- IncludeAllCustomAttributes (bool)
- IncludeAllSystemAttributes (bool)

---
### GetContacts Method

This method gets information about the specified contacts. The only required field we need to send is the email address.

---
### ImmediateUpload Method

This method is used to synchronously add or updates information up to 1000 contacts.

A similar method, `UploadMassContacts`, uses asynchronous and can process more than 1000 contacts.

The format of the request for both are identical. So they could be used interchangeably.

#### Update
In order to update, ~~we need to get the unique identifier for the contact using the `ListContactFields` or `ContactUniqueIdentifier`~~
we just need to send the email address of the contact.

We also need to specify which attributes are being updated. If we do not, it will not change anything or set them to NULL (NULL if adding)

#### Restrictions
The user cannot change its email if it is an unsubscribed user. So in order to update their contact information, they need to be subscribed.
To resubscribing the user, use `ResubscribeContact` method.


#### Add New Contacts
It is the exact same call, but you need to pass required properties:
- EmailAddress
- ContactKey
  - where ContactId is = 0
  - and ContactUniqueIdentifier is the email
- triggerWorkflow


#### Custom Attributes
```
"CustomAttributes": {
  "CustomAttribute": {
    {
      "Id": "7254031",
      "_": "Qd23fsHdT4wfYhsFG4aDFhlm65h"
    },
    {
      "Id": "7112518",
      "_": "email@email.com"
    }
  }
}
```
 - where `Id` is the unique id of the custom field - You can get this information by calling `ListAttributes` method.
 - where `_` is the value assigned to that custom field
