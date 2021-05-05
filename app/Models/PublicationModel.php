<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationModel extends Model {
  // Member variables
  protected $table = "Publications";
  protected $primaryKey = "PublicationID";
  protected $allowedFields = ["PrimaryTitle", "SecondaryTitle", "PublicationDate", "FiscalYearID", "Volume", "StartPage",
    "EndPage", "ClientID", "OrganizationID", "AbstractEnglish", "AbstractFrench", "PLSEnglish", "PLSFrench", "PRSEnglish",
    "PRSFrench", "ISBN", "AgreementNumber", "IPDNumber", "CrossReferenceNumber", "ProjectCode", "ReportNumber", "ManuscriptNumber",
    "CostCentreID", "JournalID", "ReportTypeID", "StatusID", "StatusPersonID", "StatusDueDate", "DOI", "JournalSubmissionDate",
    "JournalAcceptanceDate", "ConferenceSubmissionDate", "ConferenceAcceptanceDate", "EmbargoPeriod", "EmbargoEndDate",
    "WebPublicationDate", "SentToClient", "SentToClientDate", "ReportFormatted", "RecordNumber"];

  /**
   * Name: getPublication
   * Purpose: Retrieves a specific Publication from the database
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getPublication(int $publicationID) {
    // Create the query
    $db = \Config\Database::connect();
    $query = $db->query('SELECT p.PublicationID, p.PrimaryTitle, p.SecondaryTitle, p.PublicationDate, p.FiscalYearID, fy.FiscalYear,
      p.Volume, p.StartPage, p.EndPage, p.ClientID, c.Client, p.OrganizationID, o.Organization, p.AbstractEnglish, p.AbstractFrench,
      p.PLSEnglish, p.PLSFrench, p.PRSEnglish, p.PRSFrench, p.ISBN, p.AgreementNumber, p.IPDNumber, p.CrossReferenceNumber,
      p.ProjectCode, p.ReportNumber, p.ManuscriptNumber, p.CostCentreID, cc.CostCentre, p.JournalID, j.Journal, p.ReportTypeID,
      CONCAT (rt.ReportType, " (", rt.Abbreviation, ")") AS ReportType, p.StatusID, s.Status, p.StatusPersonID, pe.DisplayName AS StatusPerson, p.StatusDueDate, p.DOI,
      p.JournalSubmissionDate, p.JournalAcceptanceDate, p.ConferenceSubmissionDate, p.ConferenceAcceptanceDate, p.EmbargoPeriod,
      p.EmbargoEndDate, p.WebPublicationDate, p.SentToClient, p.SentToClientDate, p.ReportFormatted, p.RecordNumber
      FROM (((((((Publications AS p LEFT JOIN FiscalYears AS fy ON p.FiscalYearID = fy.FiscalYearID)
      LEFT JOIN Clients AS c ON p.ClientID = c.ClientID)
      LEFT JOIN Organizations AS o ON p.OrganizationID = o.OrganizationID)
      LEFT JOIN CostCentres AS cc ON p.CostCentreID = cc.CostCentreID)
      LEFT JOIN Journals AS j ON p.JournalID = j.JournalID)
      LEFT JOIN ReportTypes AS rt ON p.ReportTypeID = rt.ReportTypeID)
      LEFT JOIN Statuses AS s ON p.StatusID = s.StatusID)
      LEFT JOIN vPeopleDropDown AS pe ON p.StatusPersonID = pe.PersonID
      WHERE p.PublicationID = ' . $publicationID);

    // Create the result
    foreach ($query->getResult() as $row) {
      $result = array(
        "PublicationID" => $row->PublicationID,
        "PrimaryTitle" => $row->PrimaryTitle,
        "SecondaryTitle" => $row->SecondaryTitle,
        "PublicationDate" => $row->PublicationDate,
        "FiscalYearID" => $row->FiscalYearID,
        "FiscalYear" => $row->FiscalYear,
        "Volume" => $row->Volume,
        "StartPage" => $row->StartPage,
        "EndPage" => $row->EndPage,
        "ClientID" => $row->ClientID,
        "Client" => $row->Client,
        "OrganizationID" => $row->OrganizationID,
        "Organization" => $row->Organization,
        "AbstractEnglish" => $row->AbstractEnglish,
        "AbstractFrench" => $row->AbstractFrench,
        "PLSEnglish" => $row->PLSEnglish,
        "PLSFrench" => $row->PLSFrench,
        "PRSEnglish" => $row->PRSEnglish,
        "PRSFrench" => $row->PRSFrench,
        "ISBN" => $row->ISBN,
        "AgreementNumber" => $row->AgreementNumber,
        "IPDNumber" => $row->IPDNumber,
        "CrossReferenceNumber" => $row->CrossReferenceNumber,
        "ProjectCode" => $row->ProjectCode,
        "ReportNumber" => $row->ReportNumber,
        "ManuscriptNumber" => $row->ManuscriptNumber,
        "CostCentreID" => $row->CostCentreID,
        "CostCentre" => $row->CostCentre,
        "JournalID" => $row->JournalID,
        "Journal" => $row->Journal,
        "ReportTypeID" => $row->ReportTypeID,
        "ReportType" => $row->ReportType,
        "StatusID" => $row->StatusID,
        "OriginalStatusID" => $row->StatusID,
        "StatusPersonID" => $row->StatusPersonID,
        "OriginalStatusPersonID" => $row->StatusPersonID,
        "StatusPerson" => $row->StatusPerson,
        "StatusDueDate" => $row->StatusDueDate,
        "OriginalStatusDueDate" => $row->StatusDueDate,
        "DOI" => $row->DOI,
        "JournalSubmissionDate" => $row->JournalSubmissionDate,
        "JournalAcceptanceDate" => $row->JournalAcceptanceDate,
        "ConferenceSubmissionDate" => $row->ConferenceSubmissionDate,
        "ConferenceAcceptanceDate" => $row->ConferenceAcceptanceDate,
        "EmbargoPeriod" => $row->EmbargoPeriod,
        "EmbargoEndDate" => $row->EmbargoEndDate,
        "WebPublicationDate" => $row->WebPublicationDate,
        "SentToClient" => $row->SentToClient,
        "SentToClientDate" => $row->SentToClientDate,
        "ReportFormatted" => $row->ReportFormatted,
        "RecordNumber" => $row->RecordNumber,
      );
    }

    // Return the result
    return $result;
  }

  /**
   * Name: deletePublication
   * Purpose: Deletes a specific Publication and related data from the database
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deletePublication($publicationID) {
    // Delete all related data
    $this->deletePublicationsStatuses($publicationID);
    $this->deletePublicationsAuthors($publicationID);
    $this->deletePublicationsReviewers($publicationID);
    $this->deletePublicationsKeywords($publicationID);
    $this->deletePulibcationsLinks($publicationID);
    $this->deletePublicationsComments($publicationID);

    // Delete the publication
    $this->delete($publicationID);
  }

  /**
   * Name: deletePublicationsStatuses
   * Purpose: Deletes all rows in the PublicationsStatuses table with the specified
   *  PublicationID
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to rows in the table
   *
   * Returns: None
   */
  public function deletePublicationsStatuses($publicationID) {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsStatuses');

    // Generate and execute the delete
    $builder->where('PublicationID', $publicationID);
    $builder->delete();
  }

  /**
   * Name: deletePublicationsAuthors
   * Purpose: Deletes all rows in the PublicationsAuthors table with the specified
   *  PublicationID
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to rows in the table
   *
   * Returns: None
   */
  public function deletePublicationsAuthors($publicationID) {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsAuthors');

    // Generate and execute the delete
    $builder->where('PublicationID', $publicationID);
    $builder->delete();
  }

  /**
   * Name: deletePublicationsReviewers
   * Purpose: Deletes all rows in the PublicationsReviewers table with the specified
   *  PublicationID
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to rows in the table
   *
   * Returns: None
   */
  public function deletePublicationsReviewers($publicationID) {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsReviewers');

    // Generate and execute the delete
    $builder->where('PublicationID', $publicationID);
    $builder->delete();
  }

  /**
   * Name: deletePublicationsKeywords
   * Purpose: Deletes all rows in the PublicationsReviewers table with the specified
   *  PublicationID
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to rows in the table
   *
   * Returns: None
   */
  public function deletePublicationsKeywords($publicationID) {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsKeywords');

    // Generate and execute the delete
    $builder->where('PublicationID', $publicationID);
    $builder->delete();
  }

  /**
   * Name: deletePulibcationsLinks
   * Purpose: Deletes all rows in the PublicationsLinks table with the specified
   *  PublicationID
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to rows in the table
   *
   * Returns: None
   */
  public function deletePulibcationsLinks($publicationID) {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsLinks');

    // Generate and execute the delete
    $builder->where('PublicationID', $publicationID);
    $builder->delete();
  }

  /**
   * Name: deletePublicationsComments
   * Purpose: Deletes all rows in the PublicationsComments table with the specified
   *  PublicationID
   *
   * Parameters:
   *  int $publicationID - The PublicationID that corresponds to rows in the table
   *
   * Returns: None
   */
  public function deletePublicationsComments($publicationID) {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsComments');

    // Generate and execute the delete
    $builder->where('PublicationID', $publicationID);
    $builder->delete();
  }
}
