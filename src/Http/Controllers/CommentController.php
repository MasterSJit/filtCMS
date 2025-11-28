<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Comment;
use EthickS\FiltCMS\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|min:3',
            'author_name' => 'required_without:user_id|string|max:255',
            'author_email' => 'required_without:user_id|email|max:255',
            'parent_id' => 'nullable|exists:filtcms_comments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $comment = new Comment($validator->validated());
        $comment->user_id = Auth::id();
        $comment->ip_address = $request->ip();
        $comment->user_agent = $request->userAgent();

        // Check for profanity
        if ($comment->containsProfanity()) {
            $comment->status = 'pending';
            $comment->is_flagged = true;
        } else {
            $moderateComments = Setting::get('moderate_comments', false);
            $comment->status = $moderateComments ? 'pending' : 'approved';
        }

        $comment->save();

        return redirect()->back()->with('success', 'Comment submitted successfully!');
    }
}
