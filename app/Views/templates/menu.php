<?php helper('auth'); ?>

<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
          <a class="nav-link" href="\publications\">Home</a>
      </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
              Publications
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="publications/new">New Publication</a>
            <a class="dropdown-item" href="publications/indexDetailed">Publications Dashboard</a>
            <a class="dropdown-item" href="publications/index">Publications Status Dashboard</a>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Reports</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                Lookup Tables
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="clients/index">Clients / Publishers</a>
                <a class="dropdown-item" href="costCentres/index">Cost Centres</a>
                <a class="dropdown-item" href="fiscalYears/index">Fiscal Years</a>
                <a class="dropdown-item" href="journals/index">Journals</a>
                <a class="dropdown-item" href="keywords/index">Keywords</a>
                <a class="dropdown-item" href="linkTypes/index">Link Types</a>
                <a class="dropdown-item" href="organizations/index">Organizations</a>
                <a class="dropdown-item" href="people/index">People</a>
                <a class="dropdown-item" href="reportTypes/index">Report Types</a>
                <a class="dropdown-item" href="statuses/index">Statuses</a>
            </div>
        </li>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="#">About</a>
        </li>
        <?php
          if (logged_in() == false) {
            echo ('<a class="nav-link" href="login">Login</a>');
          } else {
            $user = user();
            echo ('<a class="nav-link">Welcome ' . $user->firstname . "</a>");
            echo ('<a class="nav-link" href="logout">Logout</a>');
          }
         ?>
    </ul>
</nav>
