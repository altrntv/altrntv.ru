<?php

require "rb.php";
require 'helpers.php';

R::setup( 'mysql:host=localhost;dbname=cv66842_altrntv',
    'cv66842_altrntv', 'q-a8ta0lNLKW' );

session_start();

if(!isset($_SESSION["theme"])) { $_SESSION["theme"] = "light"; }