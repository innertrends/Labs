
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
 Public Key | allows the sending of logs
 Version | unless specifically overwritten it defaults to the latest version.

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
})(window,'_itl',document,"innertrends.com");
</script>
```
 The "xxxxxxxxxxxxxxxx" has to be replaced with you public key. 
 You can also use the code from this repository and point the link to the file on the server that contains the code (my.innertrends.com/itl.js).
