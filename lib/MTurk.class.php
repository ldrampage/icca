<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include_once($_SERVER['DOCUMENT_ROOT']."/api/mturk/aws-autoloader.php"); 
    
    class MTurkC {
        public $mTurk;
        
        function __construct() {
            $region = 'us-east-1';
            $aws_access_key_id = 'AKIAJPXENBDVMVZPAHCQ';
            $aws_secret_access_key = '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'; 
            
            $credentials = new Aws\Credentials\Credentials($aws_access_key_id, $aws_secret_access_key);
            $endpoint = 'https://mturk-requester-sandbox.us-east-1.amazonaws.com';
            
            // production endpoint
            // $endpoint = 'https://mturk-requester.us-east-1.amazonaws.com';
            
            $this->mTurk = new Aws\MTurk\MTurkClient([
                'version'     => 'latest',
                'region'      => $region,
                'credentials' => $credentials,
                'endpoint' => $endpoint
            ]);
        }
        
        function listHITs() {
            return $this->mTurk->listHITs(["MaxResults" => 100])->get('HITs');
        }
        
        function listReviewableHITs() {
            $hitIds = $this->mTurk->listReviewableHITs()->get('HITs'); //only return the ids of the reviewable HITs
            $revHITs = array();
            
            foreach($hitIds as $hitId) {
                if($hitId['HITId']) {
                    $hit = $this->getHIT($hitId['HITId']);   
                    array_push($revHITs, $hit);
                }
            }
            return $revHITs;
        }
        
        function listAssignmentsForHIT($id) {
            return $this->mTurk->listAssignmentsForHIT([ 'HITId' => $id ])->get('Assignments');
        }
        
        function getBalance($balType) {
            return $this->mTurk->getAccountBalance()->get($balType);
        }
        
        function getHIT($id) {
            return $this->mTurk->getHIT([ 'HITId' => $id ])->get('HIT');
        }
        
        function getAssignment($id) {
            return $this->mTurk->getAssignment([ 'AssignmentId' => $id ])->get('Assignment');;
        }
        
        function createHIT($params) {
            return $this->mTurk->createHIT($params);
        }
        
        function createAdditionalAssignmentsForHIT($id, $numOfAssign) {
            return $this->mTurk->createAdditionalAssignmentsForHIT([
                'HITId' => $id,
                'NumberOfAdditionalAssignments' => $numOfAssig
            ]);
        }
        
        function updateHITReviewStatus($id, $rev_val) {
            return $this->mTurk->updateHITReviewStatus([
                'HITId' => $id,
                'Revert' => $rev_val,
            ]);
        }
        
        function updateExpirationForHIT($id, $date = null) {
            if(!$date) {
                $date = (new DateTime())->format('c');
            }
            
            return $this->mTurk->updateExpirationForHIT([
                'ExpireAt' => $date,
                'HITId' => $id
            ]);
        }
        
        function deleteHIT($id) {
            try {
                $result = $this->mTurk->deleteHIT([ 'HITId' => $id ]);  
                return array(
                    'status' => 'success'
                );
            } catch(Exception $e) {
                return array(
                    'status' => 'error',
                    'error' => array(
                        'msg' => $e->getMessage(),
                        'code' => $e->getCode(),
                    ),
                );
            }
        } 
        
        function approveAssignment($params) {
            try {
                $result = $this->mTurk->approveAssignment($params);  
                return array(
                    'status' => 'success'
                );
            } catch(Exception $e) {
                return array(
                    'status' => 'error',
                    'error' => array(
                        'msg' => $e->getMessage(),
                        'code' => $e->getCode(),
                    ),
                );
            }
        } 
        
        function rejectAssignment($params) {
            try {
                $result = $this->mTurk->rejectAssignment($params);  
                return array(
                    'status' => 'success'
                );
            } catch(Exception $e) {
                return array(
                    'status' => 'error',
                    'error' => array(
                        'msg' => $e->getMessage(),
                        'code' => $e->getCode(),
                    ),
                );
            }
        } 
        
        function convertSeconds($sec) {
            $arr = array('value' => 0, 'unit' => null);
            
            $day = $sec / (24 * 3600); 
      
            $sec = $sec % (24 * 3600); 
            $hour = $sec / 3600; 
          
            $sec %= 3600; 
            $minute = $sec / 60 ; 
            
            if($day > 0) {
                $arr['value'] = $day;
                $arr['unit'] = 'day';
            } else if($hour > 0) {
                $arr['value'] = $hour;
                $arr['unit'] = 'hour';
            } else if($minute > 0) {
                $arr['value'] = $minute;
                $arr['unit'] = 'minute';
            }
            return $arr;
        }
        
        function convertSeconds1($sec) {
            $arr = array('value' => 0, 'unit' => null);
            
            $day = $sec / (24 * 3600); 
      
            $sec = $sec % (24 * 3600); 
            $hour = $sec / 3600; 
          
            $sec %= 3600; 
            $minute = $sec / 60 ; 
            
            if($day > 0) {
                $arr['value'] = $day;
                $arr['unit'] = $arr['value'] > 1 ? 'days' : 'day';
            } else if($hour > 0) {
                $arr['value'] = $hour;
                $arr['unit'] = $arr['value'] > 1 ? 'hours' : 'hour';
            } else if($minute > 0) {
                $arr['value'] = $minute;
                $arr['unit'] = $arr['value'] > 1 ? 'minutes' : 'minute';
            }
            return $arr;
        }
    }
?>