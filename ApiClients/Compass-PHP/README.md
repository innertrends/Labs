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

To send a log to InnerTrends the "log" method has to be used, which accepts 3 parameters:

Parameter | Optional | Description
--- | --- | ---
type | true | determines the event type. Innertrends supports 2 types: action, error. If the type is not set it defaults to "action"
event | false | the event name
event data | true | the event data, an array of contextual data for the current event

###### Valid methods of using the log function

```  $client->log($type,$event,$event_data); ```
 or
 ```  $client->log($event,$event_data); ```
  or
 ```  $client->log($event); ```
 
 Examples
-----
 
###### Instantiating the Client Library:
```php
$client= new Compass();
//or
$client= new Compass($public_key,$private_key,$version);
```

###### Configuring the Library by using the setters
```php
$client= new Compass();
$client->setPrivateKey($private_key);
$client->setPublicKey($public_key); 
```

###### Sending an action event to IT
```php
    $data=array("Section"=>"account", 
				            "Website"=>"http://somesite.com",
				            "Name"=>"Jon Doe" 
				            );
				      
	$client->log("register",$event_data);
	//or
	$client->log("action","register",$event_data);
```

###### Sending an error event to IT
```php
    $data=array("Section"=>"account", 
				            "fault"=>"invalid email address supplied",
				            "email"=>"jondoe@test" 
				            );
				      
 	$client->log("error","register",$event_data);
``` 

######  List all logbooks accessible for the current account
```php  

 $logbooks=$client->listLogbooks();
```

######  List all reports accessible for the current account, bound to a logbook
```php  
 $data=array("lid"=>17);
 $reports=$client->listReports($data);
```

###### Extract last records from the  logbook: 17 
```php 
 $data=array( "lid"=>17)  
 
 $records=$client->getStream($data);
```

###### Return the number of entries in the   logbook: 17 
```php 
 $data=array( "lid"=>17,"filters"=>array("operator"=>"count"));
 $records=$client->getStream($data);
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
