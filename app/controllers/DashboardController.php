<?php

class DashboardController extends Controller
{
    public function admin()
    {
        $this->view('dashboard/admin');
    }

    public function client()
    {
        $this->view('dashboard/client');
    }

    public function staff()
    {
        $this->view('dashboard/staff');
    }
}