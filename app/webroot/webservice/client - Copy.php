<?php 
try
{
	  $client = new SoapClient("http://localhost/outbackmedia_streaming/webservice/server.php?wsdl");
	  $xml = $client->Authorize('r_admin',123456);
	  $xmlget = simplexml_load_string($xml);
	  $token = $xmlget->token; 
	  $monitor_xml = $client->GetMonitors($token);
	  $monitor_xmlget = simplexml_load_string($monitor_xml);
	  $attr = $monitor_xmlget->monitor->attributes();
	  $monitor_id = @$attr['id'];
	  $timeline = $client->GetMonitorTimeline($token,$monitor_id); 
}
catch (SoapFault $fault)
{
	trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

header('Content-type: text/xml');
echo $timeline;
exit;

?>