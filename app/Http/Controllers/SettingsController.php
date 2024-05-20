<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use jcobhams\NewsApi\NewsApi;

class SettingsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/settings/show",
     *     summary="Get user settings",
     *     description="Fetches the user's settings.",
     *     operationId="getUserSettings",
     *     tags={"User Settings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="defaultCategory", type="string"),
     *                 @OA\Property(property="articlesPerPage", type="integer"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="error", type="string"),
     *                 @OA\Property(property="message", type="string"),
     *             }
     *         )
     *     )
     * )
     */
    public function show()
    {
        $userSettings = auth()->user()->settings;

        return view('settings.show', ['userSettings' => $userSettings]);
    }


    /**
     * @OA\Post(
     *     path="/settings/update",
     *     summary="Update user settings",
     *     description="Updates the user's settings.",
     *     operationId="updateUserSettings",
     *     tags={"User Settings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="defaultCategory", type="string", enum={"business", "entertainment", "general", "health", "science", "sports", "technology"}),
     *                 @OA\Property(property="articlesPerPage", type="integer", minimum=5),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Settings updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="error", type="string"),
     *                 @OA\Property(property="message", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="error", type="string"),
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="errors", type="object"),
     *             }
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        $request->validate([
            'defaultCategory' => 'required|string|in:business,entertainment,general,health,science,sports,technology',
            'articlesPerPage' => 'required|integer|min:5',
        ]);

        $user = auth()->user();
        $user->update([
            'category' => $request->input('defaultCategory'),
            'articlesPerPage' => $request->input('articlesPerPage'),
        ]);

        return redirect()->route('settings.show')->with('success', 'Settings updated successfully');
    }
}
