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
 After you registered the wrapper in your autoload handler or just included the path, you can start using its static methods. To set/change its configurations you must use the "CompassApi::configure" method: `` CompassApi::configure(array("public_key"=>"xxxxxxxxxxxxxxxxxxxx","private_key"=>"xxxxxxxxxxxxxxxxxxxx","version"=>"xx","debug"=>true));`` . Only the 'public_key' is mandatory.

 Another, more global approach, to set this configuration variables is to use constants that are defined before the instantiation and visible throughout the application. If the system you use permits this.  As a first act, the library checks if the constants exist, and if true, they are being used. (recommended)

Constant | Variable
--- | ---
IT_COMPASS_PUBLIC_KEY | Public key
IT_COMPASS_PRIVATE_KEY | Private key
IT_COMPASS_VERSION | The api version

To send a log to InnerTrends the "action"/ "error" method has to be used, which accepts just one parameter:

Parameter | Optional | Description
--- | --- | ---
event name | false | the event data, an array of contextual data for the current event
event data | true | the event name, how you want to label the

######  sending a log

``` CompassApi::log("event_name",$event_data); ```
 
 
 The '$event_data' is a 'key -> value' array that holds all the contextual data of the event; the keys hold the name of the event member, and the value,  his description.
 We do have a few special key that should and can be used only in the intended puposes:
 
 Key | Optional | Description
--- | --- | ---
_event | true | this designates the current event name. The name of the entire context. If not set, the default                    will be set to 'other'
_type | true | this designates the type of log you are going to send. "error" or "action". The default is "action".
_identity | true | the subject of the event. The actual user. The format is of your choosing, it can be an email                    address or anykind of string with which you can identify him.
 
 Examples
-----
 
###### Instantiating the Client Library if no global constant variables are set:
```php
CompassApi::configure(array("public_key"=>"xxxxxxxxxxxxxx"))
```
  
###### Sending an action event to IT
```php
    		      
 CompassApi::log("register",array("Website"=>"http://somesite.com","Name"=>"Jon Doe" ));
```

###### Sending an error event to IT
```php
 	 CompassApi::log("register",array("_type"=>"error","_identity"=>"user@site.com","fault"=>"invalid email address supplied" ));
``` 

######  List all logbooks accessible for the current account
```php  

 $logbooks= CompassApi::listLogbooks();
```

######  List all reports accessible for the current account, bound to a logbook
```php  
 $reports= CompassApi::listReports(array("lid"=>17));
```

###### Extract last records from the  logbook: 17 
```php 
 $records= CompassApi::getStream(array( "lid"=>17));
```

###### Return the number of entries in the   logbook: 17 
```php 
 $records= CompassApi::getStream(array( "lid"=>17,"filters"=>array("operator"=>"count")));
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
 
 $records CompassApi::getStream($data);
```
Support
-------------------
If you have any questions, bugs, or suggestions, please report them via Github Issues.  
