<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use jcobhams\NewsApi\NewsApi;
use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class TopHeadlinesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/paginated",
     *     summary="Get top news",
     *     description="Fetches top news based on the specified category and country.",
     *     operationId="getTopNews",
     *     tags={"Top News"},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=true,
     *         description="The category of news (business, entertainment, health, science, sports, technology).",
     *         @OA\Schema(type="string", enum={"business", "entertainment", "health", "science", "sports", "technology"})
     *     ),
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         required=false,
     *         description="The country code for news filtering (e.g., 'us', 'gb', 'ca').",
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
     *         response=400,
     *         description="Validation error",
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
        $apiKey = env('NEWS_API_KEY');
        $request->validate([
            'category' => 'nullable|in:business,entertainment,health,general,science,sports,technology',
            'country'  => 'nullable|string|max:2',
        ]);

        $user= Auth::user();

        $category = $request->input('category') ?? ($user->category ? $user->category : 'general');
        $pageSize = $user->articlesPerPage ? $user->articlesPerPage : '5';
        $country = $request->input('country', 'us');
        $news = $this->fetchTopNews($category, $country, $pageSize);

        $newsapi = new NewsApi($apiKey);
        $categories = $newsapi->getCategories();
        $countries = $countries = Country::all()->pluck('name', 'code');
    
        return view('topHeadlines', [
            'news' => $news['articles'],
            'categories' => $categories,
            'countries' => $countries,
        ]);
    }

    /**
     * Fetch top news based on the specified category and country.
     *
     * @param string $category The category of news.
     * @param string|null $country The country code for news filtering.
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchTopNews($category, $country, $pageSize)
    {
        $apiKey = env('NEWS_API_KEY');
        $url = "https://newsapi.org/v2/top-headlines?category={$category}&country={$country}&pageSize={$pageSize}&apiKey=$apiKey";
        $client = new Client();
        $response = $client->get($url);
        $news = json_decode($response->getBody(), true);

        return $news;
    }
}