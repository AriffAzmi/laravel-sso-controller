<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Illuminate\Http\Request;

class SSOApiController extends Controller
{
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        
        if ($validator->fails()) { 
            
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 412);        
        }

        $email = $request->email;
        $password = $request->password;

        try {
        	
	        // Check if user exist
	        $user = User::where('email',$email)
	        ->firstOrFail();

	        // Check if password match
	        if (Hash::check($password, $user->password)) {
	        	
	        	return response()->json([
	        		'status' => true,
	        		'data' => $user,
	        		'message' => 'User successfully authenticated.'
	        	]);
	        }
	        else {

	        	return response()->json([
	                'status' => false,
	                'message' => 'Validation error',
	                'errors' => $validator->errors()
	            ], 412);   
	        }
        } catch (\Exception $e) {
        	
        	return response()->json([
    			'status' => false,
    			'message' => 'User not found',
    			'_e' => $e->getMessage()
    		],404);
        }
    }

    public function checkIfValidID(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'sso_id'     => 'required',
        ]);
        
        if ($validator->fails()) { 
            
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 412);        
        }
		
		try {
			
    		// Check if sso_id exists
    		$check = User::where('sso_id',$request->sso_id)
    		->firstOrFail();

    		return response()->json([
    			'status' => true
    		]);
		} catch (\Exception $e) {

			return response()->json([
    			'status' => false,
    			'message' => 'User not found',
    			'_e' => $e->getMessage()
    		],404);
		}
    }
}
