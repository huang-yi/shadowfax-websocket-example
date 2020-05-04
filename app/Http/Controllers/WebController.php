<?php

namespace App\Http\Controllers;

class WebController extends Controller
{
    /**
     * The echo server web page.
     *
     * @return \Illuminate\View\View
     */
    public function echo()
    {
        return view('echo');
    }

    /**
     * The chatroom web page.
     *
     * @return \Illuminate\View\View
     */
    public function chatroom()
    {
        return view('chatroom');
    }
}
