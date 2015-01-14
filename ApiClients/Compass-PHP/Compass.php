<?php

/**
 * Wrapper for InnerTrend's Public API - Compass
 *
 * @package		Atlas v0.1
 * @author		Alex Stoia
 * @link		http://api.innertrends.com/ 
 */

class Compass
{
	/**
	 * Api version 
	 * 
	 * @var string
	 */
	private $version = '1.0';
	
	/**
	 * Communication endpoint url
	 * @var string
	 */
	private $endpoint="compass.innertrends.com";
	
 
	
	/**
	 * Data collection endpoin
	 * @var string
	 */
	private $collect_endpoint="babel.innertrends.com/store";
	
	/**
	 * Output format
	 *  * json
	 *  * xml
	 *  
	 * @var string
	 */
	private $output = 'json'; 
    
	/**
	 * Communicat through secure protocol, or not
	 * 
	 * @var Boolean
	 */
	private $secure = true;
 
	/**
	 * Your developer private api key
	 * !used for read operations
	 * @var string
	 */
	private $private_key = ''; 
	
	/**
	 * Your developer public api key
	 * !used for write operations
	 * @var string
	 */
	private $public_key = '';
	
	/**
	 * If this is on, request data payload gets printed
	 * 
	 * @var Boolean
	 */
	private $debug=false;
 
	 
	/**
	 * Initiate
	 * @param string $pubk
	 * @param string $privk
	 */
	public function __construct($pubk="",$privk="",$version="")
	{
		/**
		 * Check for global configurations
		 */
		if(defined("IT_COMPASS_PUBLIC_KEY")) $this->public_key =IT_COMPASS_PUBLIC_KEY; 
		if(defined("IT_COMPASS_PRIVATE_KEY")) $this->private_key =IT_COMPASS_PRIVATE_KEY;
		if(defined("IT_COMPASS_VERSION")) $this->version =IT_COMPASS_VERSION;
		
		/**
		 * set/overwrite keys
		 */
		if( $pubk!="" ) $this->public_key =$pubk; 
		if( $privk!="" ) $this->private_key =$privk;
		if( $version!="" ) $this->version =$version;
		 
	}
	
	/**
	 * Activate debug mode;
	 */
	public function debug(){
		$this->debug=true;
		return $this;
	}
	
	/**
	 * Setter for the public key
	 * @param string $key
	 */
	public function setPublicKey($key=""){
		$this->public_key=$key;
		
		return $this;
	}
	
	/**
	 * Setter for the private key
	 * @param string $key
	 */
	public function setPrivateKey($key=""){
		$this->private_key=$key;
	
		return $this;
	}
	
		
	/**
	 * Setter for the api version
	 * @param string $v
	 * @return Compass
	 */
	public function setVersion($v=""){
		$this->version=$v;
		return $this;
	}
	
	/**
	 * Setter for  the output format 
	 * @param string $format
	 */
	public function setOutputFormat($format="json"){
		$this->output=$format;
		
		return $this;
	}
    
	/**
	 * Formatting the data to be send ready
	 * 
	 * @param array $builder
	 * @return array
	 */
	private function buildRequest($builder=array()){
 		
		if($builder['op']=="log"){
	
			/**
			 * Compose endpoint
			 * @var string
			 */
			$terminal=(($this->secure) ? 'https' : 'http').'://'.$this->collect_endpoint;
			
		 	
			$type="action";$cvars="";$event="";$cvi=1;	
		 	
		 	if(sizeof($builder['query'])==3 or (sizeof($builder['query'])==2 and !is_array($builder['query'][1])))
		 	{
		 		$type=$builder['query'][0];
		 		$event=$builder['query'][1];
		 		$cvi=2;
		 	}
		 	else $event=$builder['query'][0];
		 	
		 	if(is_array($builder['query'][$cvi])){ 
		 		foreach( $builder['query'][$cvi] as $k => $p){
		 			if($k=="_identity")  $k="&itp_itid";
		 			else if($k=="_type")  $k="&itp_type";
		 			else $k="&itp_".$k;
		 			
		 			$cvars.=$k."=".urlencode($p);
		 		}
		 	}
		 	
            //$id="&itp_itid=$email";
			$terminal.="?_itkey=$this->public_key&itp_ittype=$type&itp_itval=$event".$cvars;
		    $request['url']=$terminal;
		    $request['type']="get";
		    $request['op']="log";
		}
		else{
			
			$terminal=(($this->secure) ? 'https' : 'http').'://'.$this->endpoint.'/atlas/'.$this->version.'';
			 
			if($builder['op']=="get" or $builder['op']=="list"){
			    $request['type']="get";
			}
			else
			{
				$request['type']="post";
			}
			
			$query=$builder;
			
			unset($query['op'],$query['access']);
			
			if(isset($query['filters']) and is_array($query['filters']) )
				$query['filters']="filters=".urlencode(json_encode($query['filters']));
			
			if($builder['access']=="reports"){
			    if(isset($builder['lid']) and $builder['lid']>0) $path="logbooks/".$builder['lid'];
			    if(isset($builder['rid']) and $builder['rid']>0 and $path!="") $path.="/reports/".$builder['rid'];
			   
			    if(!isset($builder['lid']) and !isset($builder['rid'])) $path="reports/";
			}
			else $path=$builder['access'];
			
			unset($query['rid'],$query['lid']);
			
			$request['url']=$terminal.'/'.$path.'/'; 
			
			if($request['type']=="get" and !empty($query))$request['url'].='?'.join('&',$query);
			else $request['fields']=$query;
		 
		}
		return $request;
	}
	
	/**
	 * Send the built request to the api via curl
	 * @param array $request
	 */
	public function send($request=array()) {
		
		/**
		 * print request configuration, before send
		 */
		if($this->debug){
			print_r($request);
		}
		
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $request['url']);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);
		
		if(!isset($request['op']))
		 curl_setopt($curl_handle, CURLOPT_USERPWD,$this->public_key.':'.$this->private_key);
		
		 if($request['type']=="post"){
			curl_setopt($curl_handle, CURLOPT_POST, count($request['fields']));
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($request['fields'], '', '&')); 
	 	} 
	
		$response = curl_exec($curl_handle);
	    $info=curl_getinfo($curl_handle,CURLINFO_HTTP_CODE);
	    
	   if(!isset($request['format']) or $request['format']=="json") $response=json_decode($response);
	    
	    
		curl_close($curl_handle);
	
	    return $response;
	
	}
	
	/**
	 * Compose and send the request from the called method
	 *  * format: getMethod,createMethod,updateMethod -> concrete: getReports(array)
	 *  
	 * @param string $method
	 * @param array $args
	 */
	public function __call($method,$args) {
 
		$data=array();
		$analyzed=array();
		$builder=array();
		
		/**
		 * Analyze called method
		 */
        if(preg_match("/^(get|create|update|list)(.*)/", $method,$analyzed)){
        
           /**
            * Create request parameters
            * @var array
            */
           $builder['op']=$analyzed[1];
           $builder['access']=strtolower($analyzed[2]);
           $builder=array_merge($builder,$args[0]);
           
           /**
            * Build request
            * @var array
            */
           $request=$this->buildRequest($builder);
        
        }
        else
        {
        	/**
        	 * Send a log to colletor endpoint
        	 */
        	if($method=="log"){
        		$builder['op']="log";
        		$builder['query']=$args;
        		$request=$this->buildRequest($builder);
        	}
        }
		
        
        /**
         * Send final composed request
         * @var array
         */
        $data=$this->send($request);
		
		return $data;
	}
    
 
    



}
