<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use jcobhams\NewsApi\NewsApi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Language;

/**
 * @OA\Info(
 *     title="News Portal API",
 *     version="1.0.0",
 *     description="API for News Portal",
 *     @OA\Contact(
 *         email="your@email.com",
 *         name="Your Name"
 *     ),
 *     @OA\License(
 *         name="Your License",
 *         url="http://your-license-url.com"
 *     )
 * )
 */

class NewsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/paginated",
     *     summary="Search for news articles",
     *     description="Searches for news articles based on the provided parameters.",
     *     operationId="searchAllNews",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="The search query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort option for the results",
     *         required=false,
     *         @OA\Schema(type="string", enum={"relevancy", "popularity", "publishedAt"})
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         description="Language code for filtering results",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=2)
     *     ),
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
    public function paginated(Request $request)
    {
        $user= Auth::user();
        $apiKey = env('NEWS_API_KEY');
        $request->validate([
            'sort' => 'nullable|in:relevancy,popularity,publishedAt',
            'language'  => 'nullable|string|max:2',
        ]);

        $search = $request->input('search') ? $request->input('search') : '*';
        $sort = $request->input('sort') ? $request->input('sort') : 'publishedAt';
        $language = $request->input('language');

        $pageSize = $user->articlesPerPage ? $user->articlesPerPage : '5';

        $news = $this->fetchSearchedNews($search, $sort, $language, $pageSize);

        $newsapi = new NewsApi($apiKey );
        $sort = $newsapi->getSortBy();
        $languages = Language::all()->pluck('name', 'code');

        return view('dashboard', [
            'news' => $news['articles'],
            'sortOptions' => $sort,
            'languages' => $languages,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/fetch-searched-news",
     *     summary="Fetch searched news articles",
     *     description="Fetches news articles based on the provided search parameters.",
     *     operationId="fetchSearchedNews",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="The search query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort option for the results",
     *         required=true,
     *         @OA\Schema(type="string", enum={"relevancy", "popularity", "publishedAt"})
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         description="Language code for filtering results",
     *         required=true,
     *         @OA\Schema(type="string", maxLength=2)
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of articles per page",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
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
    private function fetchSearchedNews($search, $sort, $language, $pageSize)
    {
        $apiKey = env('NEWS_API_KEY');
        $url = "https://newsapi.org/v2/everything?q={$search}&sort={$sort}&language={$language}&pageSize={$pageSize}&apiKey=$apiKey";
        $client = new Client();
        $response = $client->get($url);
        $news = json_decode($response->getBody(), true);

        return $news;
    }
}