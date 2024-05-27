<?php
interface InsuranceInterface{
    public function getFormattedHeader(Array $headerData): Array;
    public function getFormattedApiRequest(Array $requestData): Array;
    public function getRawApiResponse(Array $request):Array;
    public function getFormattedApiResponse(Array $rawApiResponseData): Array;
}