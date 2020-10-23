<?php
//Data Fetcher Class
const LINE_HTM = "\n ";
class DataFetcher
{
	
private $URL_INFO ;
private $URL_LEARN;
private $TIME_START;
private $BID_PRICE ;
private $TIME_END;
private $ERROR_CODE ;
private $DATA_DUMP ;

	function __construct($name){
		$this->URL_INFO =   "https://financialmodelingprep.com/api/v3/forex/".$name."?apikey=119ce523ea723d62a0b4006650c362d6";
		$this->URL_LEARN =   "https://financialmodelingprep.com/api/v3/historical-chart/1min/".$name."?apikey=119ce523ea723d62a0b4006650c362d6";
		$this->TIME_START = time();
		$this->ERROR_CODE = 0;
		$this->BID_PRICE = 0;
		$this->TIME_END = 0;
		$this->DATA_DUMP = 0;
		$this->getInitialData();
	}
		public function setData(){ #1
				echo "..Initialising Data Fetching Class".LINE_HTM;

		
				echo "..URL Initiated".LINE_HTM;

		
				echo "..Time logged".LINE_HTM;

		$channel = curl_init();

curl_setopt($channel, CURLOPT_AUTOREFERER, TRUE);
curl_setopt($channel, CURLOPT_HEADER, 0);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($channel, CURLOPT_URL, $this->URL_INFO);
curl_setopt($channel, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($channel, CURLOPT_TIMEOUT, 0);
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);

		$output = curl_exec($channel);
		$this->TIME_END = time();
		
		if (curl_error($channel)) {
			$this->ERROR_CODE = 1;
			die( 'error:' . curl_error($channel));
			
		} else {
			$this->ERROR_CODE = 0;
		 $this->DATA_DUMP = json_decode($output);
		
		}


	}
	#1
	
	function getTimeStart(){
		return $this->TIME_START;
	}
	

	
	function getTimeEnd(){
		return $this->TIME_END;
	}
	
	function getData(){
		return $this->DATA_DUMP;
	}
	
	function getRetCode(){
		return $this->ERROR_CODE;
	}
	

function getInitialData(){
	echo "..Initialising Learning Data Fetching Class".LINE_HTM;

		
				echo "..URL Initiated for Learning".LINE_HTM;

		
				echo "..Time not logged".LINE_HTM;

		$channel = curl_init();

curl_setopt($channel, CURLOPT_AUTOREFERER, TRUE);
curl_setopt($channel, CURLOPT_HEADER, 0);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($channel, CURLOPT_URL, $this->URL_LEARN);
curl_setopt($channel, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($channel, CURLOPT_TIMEOUT, 0);
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);

		$output = curl_exec($channel);
		if (curl_error($channel)) {
			$this->ERROR_CODE = 1;
			die( 'error:' . curl_error($channel));
			
		} else {
			$this->ERROR_CODE = 0;
		 $this->DATA_DUMP = json_decode($output);
		
		}



}






}

?>