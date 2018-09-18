<?php 
namespace App\Traits;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Response;

trait ExceptionTrait {
	
	public function returnExeption($req,$exep){
		if($exep instanceof ModelNotFoundException)
        {
            return $this->isModel($req,$exep);
        }
        if ($exep instanceof NotFoundHttpException) {
        	return $this->isHttp($req,$exep);
        }if($exep instanceof MethodNotAllowedHttpException){
            return $this->isMethod($req,$exep);
        }
        
        return parent::render($req,$exep);
	}
	protected function isModel($req,$exep){
        
		//  return response()->json([
        //         'error' => 'Data not found'
        //     ], 404);
        $response['error'] = array(); 
        $response['data'] = []; 
        $model = last(explode('\\',$exep->getModel()));
        $response['data']['message']= "$model not found"; 
        $response['success'] = false;
        return Response::json($response, $status=401, $headers=[], $options=0);
        
	}
	protected function isHttp($req,$exep){
        $response['error']['message']= "API endpoint is not found"; 
        $response['data'] = [];
        $response['success'] = false;
        return Response::json($response, $status=404, $headers=[], $options=0);
        
		//  return response()->json([
        //         'error' => 'API endpoint is not found'
        //     ], 404);
    }
    
    protected function isMethod($req,$exep){
        $response['error']['message'] = "This method is not allowed"; array(); 
        $response['data'] = [];
        $response['success'] = false;
        return Response::json($response, $status=404, $headers=[], $options=0);
        
		//  return response()->json([
        //         'error' => 'API endpoint is not found'
        //     ], 404);
    }
    
    protected function unauthenticated($request, AuthenticationException $exception)
         {
            $response['error']['message']= "Unauthenticated"; 
            $response['data'] = [];
            $response['success'] = false;
            return $request->expectsJson()
                    ? Response::json($response, $status=401, $headers=[], $options=0)
                    : '';
                    // redirect()->guest(route('authentication.index'))
    }
    
}
?>