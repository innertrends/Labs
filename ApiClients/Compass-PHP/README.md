 InnerTrends PHP Client Library
===================
 This wrapper lets developers interact with the InnerTrends API, allowing them to store event logs such as errors and actions. 
 The API also facilitates the interogation of their formed logs database by using a slick sintax.
                                        
 Requirements
-----
 To be able to use this client, first, you must obtain the public and/or private key from your InnerTrends (https://my.innertrends.com) account                                        

 Access | Description
 --- | ---
 Public Key | allows the sending of logs
 Private Key | allows the interogation of the logs database
 Version | the api version you're connecting to. It defaults to the last version at the time you took the wrapper.

 Usage
-----
 When instantiating the client you can set the public key, private key and version in the constructor.    These are optional and can later be set via the specific setter methods that the wrapper implements (setVersion($v), setPrivateKey($k), setPublicKey($k)).

 Another, more global approach, to set this configuration variables is to use constants that are defined before the instantiation and visible throughout the application. As a first act, the library check if the constants exist, and if true, they are being used.

Constant | Variable
--- | ---
IT_COMPASS_PUBLIC_KEY | Public key
IT_COMPASS_PRIVATE_KEY | Private key
IT_COMPASS_VERSION | The api version

To send a log to InnerTrends the "action"/ "error" method has to be used, which accepts just one parameter:

Parameter | Optional | Description
--- | --- | ---
 
event data | true | the event data, an array of contextual data for the current event

######  sending a log

```  $client->action($event_data); ```
 or
 ``` $client->error($event_data); ```
 
 The '$event_data' is a 'key -> value' array that holds all the contextual data of the event; the keys hold the name of the event member, and the value  their description.
 We do have a few special key that should and can be used only in the intended puposes:
 
 Key | Optional | Description
--- | --- | ---
_event | true | this designates the current event name. The name of the entire context. If not set, the default                    will be set to 'other'
_identity | true | the subject of the event. The actual user. The format is of your choosing, it can be an email                    address or anykind of string with which you can identify him.
 
 Examples
-----
 
###### Instantiating the Client Library:
```php
$client= new CompassApi(); // if it takes the configuration from the global constants
//or
$client= new CompassApi($public_key,$private_key,$version);
```

###### Configuring the Library by using the setters
```php
$client= new CompassApi();
$client->setPrivateKey($private_key);
$client->setPublicKey($public_key); 
```

###### Sending an action event to IT
```php
    		      
 $client->action(array("_event"=>"register","Website"=>"http://somesite.com","Name"=>"Jon Doe" ));
```

###### Sending an error event to IT
```php
 	$client->error(array("_event"=>"register","_identity"=>"user@site.com","fault"=>"invalid email address supplied" ));
``` 

######  List all logbooks accessible for the current account
```php  

 $logbooks=$client->listLogbooks();
```

######  List all reports accessible for the current account, bound to a logbook
```php  
 $reports=$client->listReports(array("lid"=>17));
```

###### Extract last records from the  logbook: 17 
```php 
 $records=$client->getStream(array( "lid"=>17));
```

###### Return the number of entries in the   logbook: 17 
```php 
 $records=$client->getStream(array( "lid"=>17,"filters"=>array("operator"=>"count")));
```

###### Extract last records from the  logbook: 17, report: 81 with an extra filter:
       user that matches exactly an email address
```php 
 $data=array("lid"=>17,"rid"=>81,
 		     "filters"=>array("include"=>
 		     		           array("user"=>array("matches_exactly"=>"user@domain.com")
 		     		 		)
            )
 		); 
 
 $records=$client->getStream($data);
```
Support
-------------------
If you have any questions, bugs, or suggestions, please report them via Github Issues.  
