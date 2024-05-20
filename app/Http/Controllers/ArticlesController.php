<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use jcobhams\NewsApi\NewsApi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/list",
     *     summary="Get all news articles",
     *     description="Fetches all news articles from the News API.",
     *     operationId="getAllNewsArticles",
     *     tags={"All News"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="author", type="string"),
     *                     @OA\Property(property="publishedAt", type="string"),
     *                     @OA\Property(property="url", type="string"),
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
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
    public function list(Request $request)
    {
        $user= Auth::user();
        $apiKey = env('NEWS_API_KEY');
        $pageSize = $user->articlesPerPage ? $user->articlesPerPage : '5';

        $url = "https://newsapi.org/v2/everything?q=*&pageSize={$pageSize}&apiKey=$apiKey";
        $client = new Client();
        $response = $client->get($url);

        $news = json_decode($response->getBody(), true);

        return view('articles', ['news' => $news['articles']]);
    }
}
