<?php

namespace App\Traits;

trait GeneralTrait
{
    public function uploadImage($image, $directory)
    {
        $imageName = time() . '-' . uniqid() . '.' . $image->extension();
        $image->move(public_path("Images\\$directory"), $imageName);

        return $imageName;
    }

    function returnData(object $data, $message = '')
    {
        return response()->json([
            'status' => "good",
            'message' => $message,
            'data' => $data
        ]);
    }

    function returnErrorMessage($message = '', $statusCode = 404)
    {
        return response()->json([
            'status' => "bad",
            'message' => $message
        ], $statusCode);
    }
    
    function returnSuccessMessage($message = '')
    {
        return response()->json([
            'status' => "good",
            'message' => $message
        ], 200);
    }

    function returnSuccessMessageWithToken($message = '', $token = '')
    {
        return response()->json([
            'status' => "good",
            'message' => $message,
            'token' => $token
        ], 200);
    }
}
