<?php helper('auth'); ?>

<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
          <a class="nav-link" href="<?= base_url() ?>/">Home</a>
      </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="<?= base_url() ?>/publications/index" id="navbardrop" data-toggle="dropdown">
              Publications
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="<?= base_url() ?>/publications/new">New Publication</a>
            <a class="dropdown-item" href="<?= base_url() ?>/publications/indexDetailed">Publications Dashboard</a>
            <a class="dropdown-item" href="<?= base_url() ?>/publications/index">Publications Status Dashboard</a>
            <a class="dropdown-item" href="<?= base_url() ?>/publications/myPublications">My Publications</a>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>/reports/index">Reports</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                Lookup Tables
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="<?= base_url() ?>/clients/index">Clients / Publishers</a>
                <a class="dropdown-item" href="<?= base_url() ?>/costCentres/index">Cost Centres</a>
                <a class="dropdown-item" href="<?= base_url() ?>/fiscalYears/index">Fiscal Years</a>
                <a class="dropdown-item" href="<?= base_url() ?>/journals/index">Journals</a>
                <a class="dropdown-item" href="<?= base_url() ?>/keywords/index">Keywords</a>
                <a class="dropdown-item" href="<?= base_url() ?>/linkTypes/index">Link Types</a>
                <a class="dropdown-item" href="<?= base_url() ?>/organizations/index">Organizations</a>
                <a class="dropdown-item" href="<?= base_url() ?>/people/index">People</a>
                <a class="dropdown-item" href="<?= base_url() ?>/reportTypes/index">Report Types</a>
                <a class="dropdown-item" href="<?= base_url() ?>/statuses/index">Statuses</a>
            </div>
        </li>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="#">About</a>
        </li>
        <?php
          if (logged_in() == false) {
            echo ('<a class="nav-link" href="' . base_url() . '/login">Login</a>');
          } else {
            $user = user();
            echo ('<a class="nav-link">Welcome ' . $user->firstname . "</a>");
            echo ('<a class="nav-link" href="' . base_url() . '/logout">Logout</a>');
          }
         ?>
    </ul>
</nav>
