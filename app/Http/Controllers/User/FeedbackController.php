<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('user.feedback');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $data['user_id'] = auth()->id();

        Feedback::create($data);

        return redirect()->route('user.feedback')->with('success', 'Cảm ơn bạn đã gửi phản hồi!');
    }
}

