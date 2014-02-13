<?php

$platformCode = 'SD';


$identifier =  substr($this->customerReferenceID, -5, 5);

try {
  $client = new SoapClient($wsdl,array
                                 (
                                   "trace"      => 1,
                                   "exceptions" => 1
                                 )
                          );
    }catch (Exception $e){                           
        echo $e->__toString();
         exit();
    }

// Prepare SoapHeader parameters 
$sh_param->TransId = 'transid'; 
$sh_param->ReqId = '1'; 
$sh_param->Ver = '1'; 
$sh_param->Consumer = 'SCIDIR'; 
$sh_param->ConsumerClient = "SUSHI:CORAL"; 
$sh_param->LogLevel = 'All'; 
$headers = new SoapHeader('http://webservices.elsevier.com/schemas/easi/headers/types/v1', 'EASIReq', $sh_param, false);
 
// Prepare Soap Client 
try {
  $client->__setSoapHeaders($headers); 
}catch (Exception $e){
         echo $e->__toString();
         exit();
}
var_dump($client->__getFunctions());

echo "<br /><br />";


try {
  $result = $client->GetReport(
                    array
                    (
                        'Requestor' => array
                        (
                            'ID' => $this->requestorID,
                            'Name' => $user->loginID,
                            'Email' => $this->requestorEmail
                        ),
                        'CustomerReference' => array
                        (
                            'ID' => $this->customerReferenceID,
                            'Name' => $this->customerReferenceName
                        ),
                        'ReportDefinition'  => array
                        (
                            'Filters' => array
                            (
                                'UsageDateRange' => array
                                (
                                    'Begin' => $startDate,
                                    'End' => $endDate
                                )
                            ),
                            'Name' => $reportLayout,
                            'Release' => $this->release
                        ),
                        'Created' => $createDate,
                        'ID' => '1',
                        'authenticationWrapper' => array
                        (
                            'endUserId' => array
                            (
                                'identifier' => $identifier,
                                'identifierType' => "accountId"
                            ),
                            'integratorId' => $this->requestorID,
                            'platformCode' => $platformCode
                        ) 
                    )
  );
}
catch (Exception $e)
{
         echo $e->__toString() . "<br />";
         var_dump($result);
         exit();
}

 
?>