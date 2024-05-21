<?php
interface InsuranceInterface{
    public function getApiResponse(Array $request): Array;
    public function getApiRequest(String $requestName, Array $requestData): Array;
    public function getHeader(String $headerName, Array $headerData): Array;
    public function processApiResponse(String $apiMethodName, Array $apiResponseData):Array;
    
}