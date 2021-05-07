<script type="text/javascript" src="/scripts/people.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>
  <?php
    if ($duplicate == true) {
      echo ('<div class="alert alert-danger alert-dismissible fade show" role="alert">
      That person already exists in the system.</div>');
    }
   ?>

  <!-- New Organization Modal -->
  <div class="modal fade" id="newOrganizationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">New Organization</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <?= MyFormGeneration::generateIDTextBox("newOrganization",
              null, "Organization"); ?>
            <div class="form-group row">
              <p class="mx-3">The organization you enetred does not exist in the database.</p>
              <p class="mx-3">Do you want to add this organization to the database and continue adding the person to the database?</p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnNewOrganizationSave" class="btn btn-success" onclick="addOrganization()">Yes</button>
          <button type="button" class="btn btn-info" data-dismiss="modal" id="btnCloseModal">No</button>
        </div>
      </div>
    </div>
  </div>

  <form class="form-group" action="/people/new" method="post" id="frmPerson">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("lastName",
      set_value('lastName'), "-- Enter the person's last name --", "Last Name"); ?>

    <?= MyFormGeneration::generateTextBox("firstName",
      set_value('firstName'), "-- Enter the person's first name --", "First Name"); ?>

    <?= MyFormGeneration::generateTextBox("displayName",
      set_value('displayName'), "-- How the person's name appears as an author or reviewer.  The system will try to autogenerate it, but you can edit. --", "Display Name"); ?>

    <?= MyFormGeneration::generateLookupTextBox("organization",
      set_value('organization'), "-- Enter an organization and select it from the list that appears --", "Organization",
      "organizationID", set_value('organizationID')); ?>

    <!-- Hidden button to trigger model -->
    <div style="display: none;">
      <button type="button" title="Edit Link" data-toggle="modal" data-target="#newOrganizationModal" id="btnNewOrganization" />
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit" id="btnSubmit">Create Person</button>
    <a class="btn btn-info m-1" href="/people/index/<?= $page ?>">Back to People</a>
  </form>
</div>
