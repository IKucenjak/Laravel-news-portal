<?php
namespace App\Http\Controllers;

use App\Models\UserFavorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    /**
     * @OA\Post(
     *     path="/favorite/create",
     *     summary="Add an article to the user's favorites list",
     *     tags={"Favorites"},
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         description="The URL of the article",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="The title of the article",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="The author of the article",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="A brief description of the article",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="imageUrl",
     *         in="query",
     *         description="URL of the article's image",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article added to favorites successfully",
     *         @OA\JsonContent(
     *             example={"message": "Article added to favorites!"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, article already in favorites",
     *         @OA\JsonContent(
     *             example={"error": "This article is already in your favorites list!"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, user not authenticated",
     *         @OA\JsonContent(
     *             example={"error": "Authentication required"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             example={"error": "Internal Server Error"}
     *         )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $existingFavorite = UserFavorites::where('user_id', $user->id)
            ->where('url', $request->input('url'))
            ->first();

        if ($existingFavorite) {
            return redirect()->back()->with('error', 'This article is already in your favorites list!');
        }

        $favorite = new UserFavorites([
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'author' => $request->input('author'),
            'description' => $request->input('description'),
            'imageUrl' => $request->input('imageUrl'),
            'user_id' => $user->id,
        ]);

        $favorite->save();

        return redirect()->back()->with('success', 'Article added to favorites!');
    }


    /**
     * @OA\Get(
     *     path="/favorite/list",
     *     summary="Get the list of user's favorite articles",
     *     tags={"Favorites"},
     *     @OA\Response(
     *         response=200,
     *         description="List of user's favorite articles",
     *         @OA\JsonContent(
     *             example={
     *                 "favorites": {
     *                     {
     *                         "id": 1,
     *                         "title": "Sample Article 1",
     *                         "url": "http://sample-article-1.com",
     *                         "author": "John Doe",
     *                         "description": "Lorem ipsum dolor sit amet.",
     *                         "imageUrl": "http://sample-article-1.com/image.jpg"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "title": "Sample Article 2",
     *                         "url": "http://sample-article-2.com",
     *                         "author": "Jane Doe",
     *                         "description": "Consectetur adipiscing elit.",
     *                         "imageUrl": "http://sample-article-2.com/image.jpg"
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, user not authenticated",
     *         @OA\JsonContent(
     *             example={"error": "Authentication required"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             example={"error": "Internal Server Error"}
     *         )
     *     )
     * )
     */
    public function list()
    {
        $user = Auth::user();
        $favorites = UserFavorites::where('user_id', $user->id)->get();

        return view('favorites', ['favorites' => $favorites]);
    }


    /**
     * @OA\Delete(
     *     path="/favorite/delete/{favorite}",
     *     summary="Delete a favorite article",
     *     tags={"Favorites"},
     *     @OA\Parameter(
     *         name="favorite",
     *         in="path",
     *         description="ID of the favorite article to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article removed from favorites successfully",
     *         @OA\JsonContent(
     *             example={"success": "Article removed from favorites!"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, user not authenticated",
     *         @OA\JsonContent(
     *             example={"error": "Authentication required"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found, favorite article not found",
     *         @OA\JsonContent(
     *             example={"error": "Favorite article not found"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             example={"error": "Internal Server Error"}
     *         )
     *     )
     * )
     */
    public function delete($favourite_id)
    {
        $user = Auth::user();
        $favorite = UserFavorites::where('id', $favourite_id)
            ->where('user_id', $user->id);

        if ($favorite) {
            $favorite->delete();

            return redirect()->back()->with('success', 'Article removed from favorites!');
        }

        return redirect()->back()->with('error', 'Favorite not found');
    }
}