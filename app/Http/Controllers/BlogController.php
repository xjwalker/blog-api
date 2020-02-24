<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function getDashboard()
    {
        // todo; get the three latest records from blogs table
        // todo; each blog has author, creation_date, link to user profile,
        // todo; if we have a user in the request then we load the authors(users and add editable flag)
        // todo; add pagination
    }

    public function getUserDashboard()
    {
        // todo; get all the entries for the specific user
    }
}
