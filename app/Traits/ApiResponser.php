<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
	private function SuccessResponse($data,$message='Success',$code=200){
		$result['error'] = [];
		$result['success'] = 1;
		$result['data']['message'] = $message;	
		$result['data'] = $data;
		return response()->json($result,$code);
	}

	protected function ErrorResponse($data,$message='Success',$code=400){
		$result['error'] = $message;
		$result['success'] = 0;
		$result['data'] = [];
		return response()->json($result,$code);
	}

	protected function showAll(Collection $collection,$code=200){
		$this->SuccessResponse($collection,'',$code);
	}

	protected function showOne(Model $model,$code=200){
		$this->SuccessResponse($model,'',$code);
	}
}
?>