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
    private $insuranceService;

    public function __construct(InsuranceInterface $insuranceService) {
        $this->insuranceService = $insuranceService;
    }
    public function getFormattedHeader(Array $headerData): array
    {
        
    }

    public function getFormattedApiRequest(Array $requestData): array
    {
        
    }
    public function getRawApiResponse(Array $request): array
    {
        
    }
    public function getFormattedApiResponse(Array $rawApiResponseData): array
    {
        
    }

}