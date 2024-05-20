<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserFavorites;


class AdminDashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/dashboard",
     *     summary="Retrieve Users for Admin Dashboard",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="List of users for the admin dashboard",
     *         @OA\JsonContent(
     *             example={
     *                 "users": {
     *                     {
     *                         "id": 1,
     *                         "name": "John Doe",
     *                         "email": "john.doe@example.com",
     *                         "role": "admin"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "name": "Jane Doe",
     *                         "email": "jane.doe@example.com",
     *                         "role": "user"
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             example={"error": "Internal Server Error"}
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();

        return view('admin.dashboard', ['users' => $users]);
    }


    /**
     * @OA\Get(
     *     path="/admin/edit-user/{user_id}",
     *     summary="Edit User Information",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to edit",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Edit user view",
     *         @OA\JsonContent(
     *             example={
     *                 "user": {
     *                     "id": 1,
     *                     "name": "John Doe",
     *                     "email": "john.doe@example.com",
     *                     "role": "admin"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             example={"error": "User not found"}
     *         )
     *     )
     * )
     */
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);

        return view('admin.edit-user', compact('user'));
    }


    /**
     * @OA\Post(
     *     path="/admin/update-user/{user_id}",
     *     summary="Update User Information",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "role"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="User's name"),
     *             @OA\Property(property="category", type="string", example="technology", description="User's category"),
     *             @OA\Property(property="role", type="string", example="admin", enum={"admin", "user"}, description="User's role")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information updated successfully",
     *         @OA\JsonContent(
     *             example={"success": "User information updated successfully"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             example={"error": "Validation failed", "details": {"name": {"The name field is required."}}}
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             example={"error": "User not found"}
     *         )
     *     )
     * )
     */
    public function update(Request $request, $user_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|in:business,entertainment,health,general,science,sports,technology',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::findOrFail($user_id);

        $user->update([
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'role' => $request->input('role'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User information updated successfully.');
    }


    /**
     * @OA\Get(
     *     path="/admin/list-user-favorites/{user_id}",
     *     summary="List User's Favorite Articles",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user whose favorite articles to list",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of user's favorite articles",
     *         @OA\JsonContent(
     *             example={
     *                 "user": {
     *                     "id": 1,
     *                     "name": "John Doe",
     *                     "email": "john.doe@example.com",
     *                     "role": "admin"
     *                 },
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
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             example={"error": "User not found"}
     *         )
     *     )
     * )
     */
    public function list($user_id)
    {
        $user = User::with('favorites')->findOrFail($user_id);

        return view('admin.user-favorites', compact('user'));
    }


    /**
     * @OA\Delete(
     *     path="/admin/delete-favorite/{favoriteId}",
     *     summary="Delete User's Favorite Article",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="favoriteId",
     *         in="path",
     *         required=true,
     *         description="ID of the favorite article to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article removed from favorites successfully",
     *         @OA\JsonContent(
     *             example={"success": "Article removed from favorites successfully"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, user not authenticated",
     *         @OA\JsonContent(
     *             example={"error": "Unauthorized, user not authenticated"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to remove this article from favorites",
     *         @OA\JsonContent(
     *             example={"error": "Unauthorized to remove this article from favorites"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorite article not found",
     *         @OA\JsonContent(
     *             example={"error": "Favorite article not found"}
     *         )
     *     )
     * )
     */
    public function deleteUserFavorite($user_id, $favorites_id)
    {
        $user = Auth::user();
        $favorite = UserFavorites::where('id', $favorites_id)
            ->where('user_id', $user_id);

        if ($user->role === 'admin') {
            $favorite->delete();
            return redirect()->back()->with('success', 'Article removed from favorites!');
        }
    
        return redirect()->back()->with('error', 'Unauthorized to remove this article from favorites.');
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/users/{user_id}",
     *     summary="Delete a user",
     *     description="Delete a user by their ID.",
     *     operationId="deleteUser",
     *     tags={"Admin Users"},
     *     security={{ "api_key": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized to delete this user."),
     *         ),
     *     ),
     * )
     */
    public function deleteUser($user_id)
    {
        $authUser = Auth::user();
        $userToDelete = User::findOrFail($user_id);
    
        if ($authUser->role === 'admin') {
            $userToDelete->delete();
            return redirect()->back()->with('success', 'User successfully deleted!');
        }
    
        return redirect()->back()->with('error', 'Unauthorized to delete this user.');
    }
}
