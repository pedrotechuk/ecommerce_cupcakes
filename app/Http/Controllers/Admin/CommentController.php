<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::query()->with('user', 'product')->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function toggle(Comment $comment): RedirectResponse
    {
        $comment->update(['is_hidden' => ! $comment->is_hidden]);

        return back()->with('status', $comment->is_hidden ? 'Comentário ocultado.' : 'Comentário reexibido.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Comentário removido.');
    }
}
