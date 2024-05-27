<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('InsuranceInterface.php');
// error_reporting(E_ALL);
/**
 *
 * @package Protect
 * @subpackage Insurance
 * @author Saman <saman.teamtft@gmail.com>
 * @version V1
 */
class InsuranceService implements InsuranceInterface
{
    protected $insuranceService;

    public function __construct(InsuranceInterface $insuranceService) {
        $this->insuranceService = $insuranceService;
    }
    public function getFormattedHeader(Array $headerData): Array
    {
        try{
        $response = $this->insuranceService->getFormattedHeader($headerData);
        return $response;
        } catch(Exception $e){
            throw $e;
        }
    }

    public function getFormattedApiRequest(Array $requestData): Array
    {
        try{
            $formattedApiRequest = $this->insuranceService->getFormattedHeader($requestData);
            return $formattedApiRequest;
            } catch(Exception $e){
                throw $e;
            }
    }
    public function getRawApiResponse(Array $request): Array
    {
        try{
            $rawApiResponse = $this->insuranceService->getFormattedHeader($request);
            return $rawApiResponse;
            } catch(Exception $e){
                throw $e;
            }
    }
    public function getFormattedApiResponse(Array $rawApiResponseData): Array
    {
        try{
            $formattedApiRequest = $this->insuranceService->getFormattedHeader($rawApiResponseData);
            return $formattedApiRequest;
            } catch(Exception $e){
                throw $e;
            }
    }

}