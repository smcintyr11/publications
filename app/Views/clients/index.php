<?php
    if (isset($_SESSION['rows']) == false) {
        
    }
 ?>
<div class="container-lg my-3 py-3">
    <h1><?= esc($title); ?></h1>


    <form class="form-inline" action="/clients/index/<?= $id_sort ?>/<?= $client_sort ?>/<?= $rows ?>/<?= $offset ?>" method="post">
        <?= csrf_field() ?>
        <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>

    <a class="btn btn-primary my-3" href="/clients/new">Create Client</a>

    <div class="table-responsive-lg">
        <table class="table table-striped table-bordered">
            <col style="width: 15%">
            <col style="width: 70%">
            <col style="width: 15%">
            <thead class="thead-light">
                <?php
                    if ($id_sort == "id_asc") {
                        $id_sort_param = "id_desc";
                    } else {
                        $id_sort_param = "id_asc";
                    }
                    if ($client_sort == "client_asc") {
                        $client_sort_param = "client_desc";
                    } else {
                        $client_sort_param = "client_asc";
                    }
                 ?>
                <th scope="col">
                    <a class="btn btn-link" href="/clients/index/<?= $id_sort_param ?>/client_none/<?= $rows ?>/<?= $offset ?>/<?= $filter ?>">Client ID</a>
                    <?php
                        if ($id_sort == "id_asc") {
                            echo ("^");
                        } elseif ($id_sort == "id_desc") {
                            echo ("V");
                        }
                    ?>
                </th>
                <th scope="col">
                    <a class="btn btn-link" href="/clients/index/id_none/<?= $client_sort_param ?>/<?= $rows ?>/<?= $offset ?>/<?= $filter ?>">Client</a>
                    <?php
                        if ($client_sort == "client_asc") {
                            echo ("^");
                        } elseif ($client_sort == "client_desc") {
                            echo ("V");
                        }
                    ?>
                </th>
                <th scope="col"></th>
            </thead>
            <tbody>
                <?php if (! empty($clients) && is_array($clients)) : ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= $client['ClientID']; ?></td>
                            <td><?= esc($client['Client']); ?></td>
                            <td><a class="btn btn-link" href="/clients/edit/<?= $client['ClientID']; ?>">Edit</a>|<a class="btn btn-link" href="/clients/delete/<?= $client['ClientID']; ?>">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
    <div class="text-right">
        Rows per page:
    <a class="btn btn-link
        <?php if ($rows == 25)
        {
            echo("disabled");
        }
        ?>
        " href="/clients/index/<?= $id_sort ?>/<?= $client_sort ?>/25/<?= $offset ?>/<?= $filter ?>">25</a><a class="btn btn-link
        <?php if ($rows == 50)
        {
            echo("disabled");
        }
        ?>
        " href="/clients/index/<?= $id_sort ?>/<?= $client_sort ?>/50/<?= $offset ?>/<?= $filter ?>">50</a><a class="btn btn-link
        <?php if ($rows == 100)
        {
            echo("disabled");
        }
        ?>
        " href="/clients/index/<?= $id_sort ?>/<?= $client_sort ?>/100/<?= $offset ?>/<?= $filter ?>">100</a>
    </div>

</div>
