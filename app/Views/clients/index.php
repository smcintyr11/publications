<div class="container-lg my-3 py-3">
    <h1><?= esc($title); ?></h1>

    <form class="form-inline" action="/clients/index/<?= $cur_sort ?>/<?= $rows ?>" method="post">
        <?= csrf_field() ?>
        <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
        <button class="btn btn-success m-1" type="submit">Search</button>
        <a class="btn btn-info m-1" href="/clients/index/id_asc/<?= $rows ?>">Reset</a>
    </form>

    <a class="btn btn-primary my-3" href="/clients/new">Create Client</a>

    <div class="table-responsive-lg">
        <table class="table table-striped table-bordered">
            <col style="width: 15%">
            <col style="width: 70%">
            <col style="width: 15%">
            <thead class="thead-light">
                <?php
                    $id_sort_param = "id_asc";
                    $client_sort_param = "client_asc";
                    if ($cur_sort == "id_asc") {
                        $id_sort_param = "id_desc";
                    } elseif ($cur_sort == "client_asc") {
                        $client_sort_param = "client_desc";
                    }
                 ?>
                <th scope="col">
                    <a class="btn btn-link" href="/clients/index/<?= $id_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Client ID</a>
                    <?php
                        if ($cur_sort == "id_asc") {
                            echo ("^");
                        } elseif ($cur_sort == "id_desc") {
                            echo ("V");
                        }
                    ?>
                </th>
                <th scope="col">
                    <a class="btn btn-link" href="/clients/index/<?= $client_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Client</a>
                    <?php
                        if ($cur_sort == "client_asc") {
                            echo ("^");
                        } elseif ($cur_sort == "client_desc") {
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

    <div class="row">
        <div class="col-lg-1 btn">
            Page:
        </div>
        <div class="col-lg-7">
            <?= $pager->makeLinks(1, 1, $count, 'bootstrap_full', 5) ?>
        </div>
        <div class="col-lg-2 btn  text-right">
            Rows per page:
        </div>
        <div class="col-lg-2">
        <a class="btn btn-link
            <?php if ($rows == 25)
            {
                echo("disabled");
            }
            ?>
            " href="/clients/index/<?= $cur_sort ?>/25/<?= $pager->getCurrentPage() ?>/<?= $filter ?>">25</a><a class="btn btn-link
            <?php if ($rows == 50)
            {
                echo("disabled");
            }
            ?>
            " href="/clients/index/<?= $cur_sort ?>/50/<?= $pager->getCurrentPage() ?>/<?= $filter ?>">50</a><a class="btn btn-link
            <?php if ($rows == 100)
            {
                echo("disabled");
            }
            ?>
            " href="/clients/index/<?= $cur_sort ?>/100/<?= $pager->getCurrentPage() ?>/<?= $filter ?>">100</a>
        </div>
    </div>
</div>
