<?php
require_once './Model/User.php';
require_once './Model/Stats.php';
require_once './Aplication/SessionService.php';

class StatsService 
{
    public function GetOrderEmployeesBySector($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::OrderEmployeesBySector($fromDate, $toDate);    
        $response = $response->withJson($report, 200);  
        return $response;
      }

      public function GetOrdersBySector($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::OrdersBySector($fromDate, $toDate);    
        $response = $response->withJson($report, 200);  
        return $response;
      }

      public function GetUnitsSales($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::UnitsSales($fromDate, $toDate);  
        $rv= new stdclass(); 
        $rv->top =  array_shift(array_values($report));
        $rv->bottom =  array_pop(array_values($report));
        $response = $response->withJson($rv, 200);  
        return $response;
      }

      
      public function GetDelayedOrders($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::DelayedOrders($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }

      public function GetCanceledOrders($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::CanceledOrders($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }
      
      public function GetTableUsage($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::TableUsage($fromDate, $toDate);  
        $rv= new stdclass(); 
        $rv->top =  array_shift(array_values($report));
        $rv->bottom =  array_pop(array_values($report));
        $response = $response->withJson($rv, 200);  
        return $response;
      }
      public function GetTableTotalAmount($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::TableTotalAmount($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }
      
      public function Getbilling($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::Billing($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }
      

}
?>