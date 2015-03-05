
 InnerTrends Js Client Library
===================
 This code lets developers interact with the InnerTrends API, allowing them to store event logs such as errors and actions. 
 The API also facilitates the interogation of their formed logs database by using a clear an easy sintax.
 
                                         
 Requirements
-----
 To be able to use this client, first, you must obtain the public  key from your InnerTrends
 (https://my.innertrends.com) account                                        

 Access | Description
 --- | ---
 Public Key | allows the sending and reading of logs
 Version | unless specifically overwritten it defaults to the latest version.

 The querying of the logs is limited for the js version of the api

 Usage
-----
 In order to use the  code in your website you have to add the following snipped of code in your sites head:
 ```js
 <script>
(function(w,o,d,s) { 
	  w['_itlt']=[]; w['_itlq']=[];w['_itld']=s;w['_itlk']="xxxxxxxxxxxxxxxx";w[o]=w[o]||{log:function(t,v,p){w['_itlt'].push([t,v,p])},stream:function(q){w['_itlq'].push([q])}};
	 var pa = d.createElement('script'), ae = d.getElementsByTagName('script')[0]
, protocol = (('https:' == d.location.protocol) ? 'https://' : 'http://');pa.async = 1;  
 pa.src = protocol + 'my.innertrends.com/itl.js'; pa.type = 'text/javascript'; ae.parentNode.insertBefore(pa, ae);
})(window,'_itl',document,"yourdomain.com");
</script>
```
 The "xxxxxxxxxxxxxxxx" has to be replaced with you public key. 
 You can also use the code from this repository and point the link to the file on the server that contains the code (my.innertrends.com/itl.js).
 
 To send a log to InnerTrends the "log" method has to be used, which accepts 3 parameters:

Parameter | Optional | Description
--- | --- | ---
type | true | determines the event type. Innertrends supports 2 types: action, error. If the type is not set it defaults to "action"
event | false | the event name
event data | true | the event data, an object of contextual data for the current event.

###### Valid methods of using the log function

``` _itl.log(type,event,event_data); ```
 or
 ``` _itl.log(event,event_data); ```
  or
 ```  _itl.log(event); ```
 
 
 To query the logs database you must use the "stream" method   which accepts 1 parameters:

Parameter | Optional | Description
--- | --- | ---
query | false | an object that configures the query request

 The query object support the follwing parameters:
 
 Parameter  | Description
--- | ---
lid | the logbook id (mandatory if no citj exists)
rid | the report id. to use the report id the lid is mandatory
user | filter the data by this specific user (with respect with the format used by you). The user can be encrypted for a more secure exchage of fata (suggested method)
filters | an object of filters that's applied upond the selected data (rid,lid or citj)
citj | a custom InnerTrens job id
callback | a function that gets triggered when the request is finished -it's fed with a response object-. The function is usable in all formats
 
 ###### Valid methods of using the stream function

``` _itl.stream({lid:2}); ```
 or
 ``` _itl.stream({lid:2,rid:12}); ```
  or
 ``` _itl.stream({lid:2,rid:12,user:uid}); ```
 or
 ```js
 _itl.stream({lid:2,rid:12,callback:function(msg){
           if(msg.status=="ok"){
              console.log(msg.data);
           }
           else
           {
           }
      }}); 
 ```
   or
 ``` _itl.stream({citj:"custom_id"}); ```
