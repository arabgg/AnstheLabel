namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'OK', $meta = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ]);
    }

    public static function error($message = 'Error', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ], $code);
    }
}