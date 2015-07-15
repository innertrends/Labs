 window['_itl']=(function(){
	
	   var cfg={store_ep:"https://babel.innertrends.com/store?",
			    query_ep:"https://compass-alfa.innertrends.com/atlas/latest/",
			    key:_itlk, domain:_itld,obs:{identity:null},
	            af:['user',"citj"]};
	   
	   function err(e){
		   if(console){
			   console.log(e);
		   }
	   }
	   	 
	   function stream(query){
		   if(query==null) return false;
		   
		   if(typeof query!="object"){
			   err("invalid query");
			   return false;
		   }
		   
		   if(!query.lid){
			   err("invalid lid");
			   return false;
		   }
		   
		   fquery="logbooks/"+query.lid;
		   
		   if(query.rid)  fquery+="/reports/"+query.rid;
		   if(query.filters){
			  allowed=cfg.af.toString();
			  for(var i in query.filters) if(!allowed.indexOf(i)) delete query.filters[i];
			  fquery+="?filters="+encodeURIComponent(JSON.stringify(query.filters));
		   }
		   
		   if(query.citj) fquery+=(fquery.indexOf("?")==-1?"?":"&")+"citj="+query.citj ; 
		   if(query.user) fquery+=(fquery.indexOf("?")==-1?"?":"&")+"user="+query.user ;
		   		 
		   
		   if(!fquery.callback) fquery.callback=function(){};
		    
                   
		  return getStream(fquery,query.callback); 
		   
	   }
	   
	   function getStream(fquery,callback){
              if (typeof XMLHttpRequest=="function")
		   var xhr = new XMLHttpRequest();
             else  var xhr=new XDomainRequest();
                    
                    xhr.open("post", cfg.query_ep+fquery, true);
		    xhr.onreadystatechange = function() {
		         
		        if (xhr.readyState == 4) {  
		            var response="{}"; 
		             if(xhr.status==500){ 
		                 response={status:'error',message:"Internal server error",http_status:500};
		             }
		             else{
		                  if(xhr.responseText!=""){ 
		                      response=xhr.responseText;
		                  }
		                  else response={status:'error',message:"Internal server error",http_status:500};
		             }
		             	
		                callback(response);
		        }
		    }
		   
		    	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		  
		       	xhr.send("pk="+btoa(cfg.key));	
		 
	   }
	   function process(l,b,p){
		 
		     if(l=="identity"){
		    	 identity(b);
		    	 return;
		     }
		     if(l=="no_identity"){
		    	 noIdentity(b);
		    	 return;
		     }
		   
		     var cvars="",callback=null, build="_itkey="+cfg.key,cvarse=false,
		    	   payload={type:"action",event:"",identity:"",context:{}}; 
			 
		     if(typeof l!="object"){
			     if(b==null){
			    	 b=l;
			    	 l=null; 
			     } 
			     else if(typeof b=="object"){
			    	 p=b;
			    	 b=l;
			    	 l=null;
			     }
		     } else { p=l, l=null; };
		     
		     if( b==null && (p==null || (typeof p=="object" && !p._event)) ) {
		    	 err('empty event name');
		    	 return false;
		     } 		     
		     payload.event=b;
		     l?payload.identity=l:cfg.obs.identity?payload.identity=cfg.obs.identity:""; 
		     
		     p==null?p={}:"";
		      if(typeof p=="object"){ 
		    	 cvarse=true;
		    	 for(var i in p){
		    		 var d=0
		    		 if(i=="_callback"){
		    			 callback=p[i];  d=1;
		    		 }
		    		 else if(i=="_identity"){
		    			 payload.identity=p[i]; d=1;
		    		 }
		    		 else if(i=="_event"){
		    			 payload.event=p[i]; d=1;
		    		 }
		    		 else if(i=="_type") {
		    			 ["action","error"].toString().indexOf(i)!=-1?payload.type=p[i]:""; d=1;
		    		 }
		    		 
		    		 d==1?delete p[i]:"";
		    	 }
		     }
		     payload.context=p;
		     payload=JSON.stringify(payload);
		     build+="&_itp="+encodeURIComponent(payload);
		     var transport=new Image();
		         transport.src=cfg.store_ep+build;
		         
		         if(cvarse && p._callback){
			         transport.onload=function(){
			        	 p._callback();
			         }
		         }
	   } 
	   
	   function identity(id){
		   cfg.obs.identity=id;
	   }
	   function noIdentity(id){
		   cfg.obs.identity=null;
	   }
	   
	   function init(){
		    if(typeof _itlt!="undefined" && _itlt.length>0){
		    	for(var i=0;i<=_itlt.length-1;i++){ 
		    		process(_itlt[i][0],_itlt[i][1],_itlt[i][2]);
		    	}
		    	
		    	 _itlt=[];
		    }
		    
		    if(typeof _itlq!="undefined" && _itlq.length>0){
		    	for(var i=0;i<=_itlq.length-1;i++){ 
		    		stream(_itlq[i][0]);
		    	}
		    	
		    	 _itlq=[];
		    }
		   
	   }
	   
	   init();
	   
	   return {log:process,stream:stream}
})();  
