<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
	protected function SuccessResponse($data,$message='Success',$code=200){
		

        $response['error'] = array(); 
        $response['data'] = $data;
        if(!empty($message))
         $response['data']['message'] = $message;
        $response['success'] = 1; 

        // dd($data);
		return response()->json($response,$code);
	}

	protected function ErrorResponse($message='Error',$code=400){

		 if(!empty($message))
             $response['error'][] = $message;  
        
        $response['data'] = [];  
        $response['success'] = 0; 
        // dd($response,$code);
        return response()->json($response, $code);
	}

	protected function showAll(Collection $collection,$code=200){
		$this->SuccessResponse($collection,'',$code);
	}

	protected function showOne(Model $model,$code=200){
		$this->SuccessResponse($model,'',$code);
	}
}
?>