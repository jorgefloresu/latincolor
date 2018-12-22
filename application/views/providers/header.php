<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/css/materialize.min.css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('materialize/0.98.1/css/materialize.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('materialize/css/providers.css');?>">
<!--     <link rel="stylesheet" href="<?php echo base_url('materialize/material-icons/iconfont/material-icons.css');?>">
 -->    <title>Get Images from API</title>

  </head>
  <body>

    <header style="margin-bottom: 15px;">
      <nav class="nav-extended blue darken-3">
      <div class="nav-wrapper">
        <a href="#" class="brand-logo"><?=$provider?></a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a id="cart" href="<?php echo site_url('deposit/viewcart'); ?>"><span id="cartCount" class="new badge" style="display: none;" data-badge-caption="0"></span>
            <i class="material-icons right">shopping_cart</i></a></li>
          <li><a href="badges.html">Components</a></li>
          <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
        <ul class="side-nav" id="mobile-demo">
          <li><a href="<?php echo site_url('deposit/viewcart'); ?>">Cart</a></li>
          <li><a href="badges.html">Components</a></li>
          <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
      </div>
      <div class="nav-content">
        <ul class="tabs tabs-transparent">
          <li class="tab"><a href="#tab-search">Search</a></li>
          <li class="tab"><a href="#tab-media">Media info</a></li>
          <li class="tab"><a href="#tab-login">Session
            <span id="logged" class="new badge" data-badge-caption="logged" style="display: none;"></span></a>
          </li>
          <li class="tab"><a href="#tab-subaccounts">Subaccounts</a></li>
          <li class="tab"><a href="#tab-subscriptions">Subscriptions</a></li>
        </ul>
      </div>
    </nav>
    <div class="progress" style="margin: 0; display: none">
      <div class="indeterminate" style="width: 70%"></div>
    </div>
  </header>
