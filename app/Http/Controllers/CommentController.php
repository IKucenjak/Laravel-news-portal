<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Http\Controllers\ArticlesController;


class CommentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Create a new comment",
     *     description="Create a new comment for the authenticated user.",
     *     operationId="createComment",
     *     tags={"Comments"},
     *     security={{ "api_key": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Comment data",
     *         @OA\JsonContent(
     *             required={"body", "url"},
     *             @OA\Property(property="body", type="string", example="This is a comment body."),
     *             @OA\Property(property="url", type="string", example="https://example.com/article"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment added successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment added successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        $user = Auth::user();

        $comment = new Comment([
            'body' => $request->input('body'),
            'url' => $request->input('url'),
            'user_id' => $user->id,
        ]);

        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully.');
    }


    /**
     * @OA\Get(
     *     path="/api/users/{user_id}/comments",
     *     summary="Get comments for a specific user",
     *     description="Retrieve a list of comments for a specific user.",
     *     operationId="getUserComments",
     *     tags={"User Comments"},
     *     security={{ "api_key": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of user comments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="body", type="string", example="This is a user comment."),
     *                 @OA\Property(property="url", type="string", example="https://example.com/article"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
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
     * )
     */
    public function list($user_id)
    {
        $user = User::findOrFail($user_id);
        $userComments = Comment::where('user_id', $user_id)->get();
        
        return view('admin.user-comments', [
            'userComments' => $userComments,
            'user' => $user,
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/comments/{comment_id}/edit",
     *     summary="Edit a comment",
     *     description="Retrieve a comment for editing.",
     *     operationId="editComment",
     *     tags={"Comments"},
     *     security={{ "api_key": {} }},
     *     @OA\Parameter(
     *         name="comment_id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to edit",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment for editing",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="body", type="string", example="This is a comment body."),
     *             @OA\Property(property="url", type="string", example="https://example.com/article"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
    */
    public function edit($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        // Check if the user is the owner of the comment or is an admin
        $isWithinFirst10Minutes = $this->isWithinFirst10Minutes($comment->created_at);
        if (!$isWithinFirst10Minutes && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'You are not allowed to edit this comment after 10 minutes.');
        }

        return view('comments.edit')->with([
            'comment' => $comment,
            'isWithinFirst10Minutes' => $isWithinFirst10Minutes,
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/comments/{comment_id}",
     *     summary="Update a comment",
     *     description="Update the body of a comment.",
     *     operationId="updateComment",
     *     tags={"Comments"},
     *     security={{ "api_key": {} }},
     *     @OA\Parameter(
     *         name="comment_id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to update",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated comment data",
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", example="Updated comment body."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment updated successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="The given data was invalid."),
     *             @OA\Property(property="message", type="string", example="The body field is required."),
     *             @OA\Property(property="errors", type="object", example={"body": {"The body field is required."}}),
     *         ),
     *     ),
     * )
     */
    public function update(Request $request, $comment_id)
    {
        $request->validate([
            'body' => 'required|string|max:255',
        ]);

        $comment = Comment::findOrFail($comment_id);
        $articleTitle = $request->input('title');
        
        $isWithinFirst10Minutes = $this->isWithinFirst10Minutes($comment->created_at);
        if (!$isWithinFirst10Minutes && auth()->user()->role !== 'admin') {
            return redirect()->route('admin.user-comments', ['user_id' => $comment->user_id])
                ->with('error', 'You are not allowed to edit this comment after 10 minutes.');
        }

        $comment->update([
            'body' => $request->input('body'),
        ]);

        // Redirect to the page displaying all comments for the specific article
        return redirect()->route('articles.view-comments', ['url' => $comment->url, 'isWithinFirst10Minutes' => $isWithinFirst10Minutes])
            ->with('success', 'Comment updated successfully.');
    }



    /**
     * @OA\Delete(
     *     path="/api/comments/{comment_id}",
     *     summary="Delete a comment",
     *     description="Delete a comment by its ID.",
     *     operationId="deleteComment",
     *     tags={"Comments"},
     *     security={{ "api_key": {} }},
     *     @OA\Parameter(
     *         name="comment_id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment deleted successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function delete($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }


    /**
     * @OA\Get(
     *     path="/api/articles/comments",
     *     summary="View comments for an article",
     *     description="Retrieve comments for a specific article.",
     *     operationId="viewArticleComments",
     *     tags={"Article Comments"},
     *     security={{ "api_key": {} }},
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         required=true,
     *         description="URL of the article",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         description="Title of the article",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of comments for the article",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="body", type="string", example="This is a comment body."),
     *                 @OA\Property(property="url", type="string", example="https://example.com/article"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function viewComments(Request $request)
    {
        $articleUrl = $request->input('url');
        $articleTitle = $request->input('title');
        $comments = Comment::where('url', $articleUrl)->get();
        $numberOfComments = $comments->count();

        $isWithinFirst10Minutes = $comments->every(function ($comment) {
            return now()->diffInMinutes($comment->created_at) <= 10;
        });

        return view('comments.comments', [
            'comments' => $comments,
            'articleTitle' => $articleTitle,
            'isWithinFirst10Minutes' => $isWithinFirst10Minutes,
        ]);
    }


    private function isWithinFirst10Minutes($createdAt)
    {
        $now = now();
        $diffInMinutes = $now->diffInMinutes($createdAt);

        return $diffInMinutes <= 10;
    }
}
