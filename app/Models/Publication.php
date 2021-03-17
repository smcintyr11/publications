<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationModel extends Model {
  // Member variables
  protected $table = "Pubcliations";
  protected $primaryKey = "PubcliationID";
  protected $allowedFields = ["PrimaryTitle", "SecondaryTitle", "PublicationDate", "FiscalYearID", "Volume", "StartPage",
                              "EndPage", "ClientID", "OrganizationID", "AbstractEnglish", "AbstractFrench", "PLSEnglish",
                              "PLSFrench", "PRSEnglish", "PRSFrench", "ISBN", "AgreementNumber", "IPDNumber", "CrossReferenceNumber",
                              "ProjectCode", "ReportNumber", "ManuscriptNumber", "CostCentreID", "JournalID", "ReportTypeID",
                              "StatusID", "StatusPersonID", "EstimatedCompletionDate", "DOI", "JournalSubmissionDate",
                              "JournalAcceptanceDate", "ConferenceSubmissionDate", "ConferenceAcceptanceDate",
                              "EmbargoPeriod", "EmbargoEndDate", "WebPublicationDate", "SentToClient", "SentToClientDate",
                              "ReportFormatted", "RecordNumber"];

  // Function to get the list of People
  public function getPublicationsOnPage($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Create the query
    $db = \Config\Database::connect();
    $sql = 'SELECT cc.CostCentre, p.ProjectCode, p.IPDNumber, p.CrossReferenceNumber, p.ReportNumber, rt.ReportType,
      p.PrimaryTitle, s.Status, vpa.PublicationAuthors
      FROM (((Publications AS p LEFT JOIN CostCentres AS cc ON p.CostCentreID = cc.CostCentreID)
      LEFT JOIN ReportTypes AS rt ON p.ReportTypeID = rt.ReportTypeID)
      LEFT JOIN Statuses AS s ON p.StatusID = s.StatusID)
      LEFT JOIN vPublicationAuthors AS vpa ON p.PubcliationID = vpa.PubcliationID';

      // Determine which way  we are ordering
      if ($cur_sort == "id_asc") {
        $sql = $sql . "ORDER BY p.PublicationID";
      } elseif ($cur_sort == "id_desc") {
        $sql = $sql . "ORDER BY p.PublicationID DESC";
      } elseif ($cur_sort == "cc_asc") {
        $sql = $sql . "ORDER BY cc.CostCentre";
      } elseif ($cur_sort == "cc_desc") {
        $sql = $sql . "ORDER BY cc.CostCentre DESC";
      } elseif ($cur_sort == "pc_asc") {
        $sql = $sql . "ORDER BY p.ProjectCode";
      } elseif ($cur_sort == "pc_desc") {
        $sql = $sql . "ORDER BY p.ProjectCode DESC";
      } elseif ($cur_sort == "ipd_asc") {
        $sql = $sql . "ORDER BY p.IPDNumber";
      } elseif ($cur_sort == "ipd_desc") {
        $sql = $sql . "ORDER BY p.IPDNumber DESC";
      } elseif ($cur_sort == "rn_asc") {
        $sql = $sql . "ORDER BY p.ReportNumber";
      } elseif ($cur_sort == "rn_desc") {
        $sql = $sql . "ORDER BY p.ReportNumber DESC";
      } elseif ($cur_sort == "rt_asc") {
        $sql = $sql . "ORDER BY rt.ReportType";
      } elseif ($cur_sort == "rt_desc") {
        $sql = $sql . "ORDER BY rt.ReportType DESC";
      } elseif ($cur_sort == "xref_asc") {
        $sql = $sql . "ORDER BY p.CrossReferenceNumber";
      } elseif ($cur_sort == "xref_desc") {
        $sql = $sql . "ORDER BY p.CrossReferenceNumber DESC";
      } elseif ($cur_sort == "status_asc") {
        $sql = $sql . "ORDER BY s.Status";
      } elseif ($cur_sort == "status_desc") {
        $sql = $sql . "ORDER BY s.Status DESC";
      }

      // Determine if we are filtering
      if ($filter != '') {
        $sql = $sql . "WHERE cc.CostCentre LIKE '%" . $db->escapeLikeString($filter) . "%' ESCAPE '!'";
      }

      // Return the results
      $query = $db->query($sql);
      return $query->getResultArray();
  }

  // Function to get the list of People
  public function getPublications($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $sql = $sql . "ORDER BY p.PublicationID";
    } elseif ($cur_sort == "id_desc") {
      $sql = $sql . "ORDER BY p.PublicationID DESC";
    } elseif ($cur_sort == "cc_asc") {
      $sql = $sql . "ORDER BY cc.CostCentre";
    } elseif ($cur_sort == "cc_desc") {
      $sql = $sql . "ORDER BY cc.CostCentre DESC";
    } elseif ($cur_sort == "pc_asc") {
      $sql = $sql . "ORDER BY p.ProjectCode";
    } elseif ($cur_sort == "pc_desc") {
      $sql = $sql . "ORDER BY p.ProjectCode DESC";
    } elseif ($cur_sort == "ipd_asc") {
      $sql = $sql . "ORDER BY p.IPDNumber";
    } elseif ($cur_sort == "ipd_desc") {
      $sql = $sql . "ORDER BY p.IPDNumber DESC";
    } elseif ($cur_sort == "rn_asc") {
      $sql = $sql . "ORDER BY p.ReportNumber";
    } elseif ($cur_sort == "rn_desc") {
      $sql = $sql . "ORDER BY p.ReportNumber DESC";
    } elseif ($cur_sort == "rt_asc") {
      $sql = $sql . "ORDER BY rt.ReportType";
    } elseif ($cur_sort == "rt_desc") {
      $sql = $sql . "ORDER BY rt.ReportType DESC";
    } elseif ($cur_sort == "xref_asc") {
      $sql = $sql . "ORDER BY p.CrossReferenceNumber";
    } elseif ($cur_sort == "xref_desc") {
      $sql = $sql . "ORDER BY p.CrossReferenceNumber DESC";
    } elseif ($cur_sort == "status_asc") {
      $sql = $sql . "ORDER BY s.Status";
    } elseif ($cur_sort == "status_desc") {
      $sql = $sql . "ORDER BY s.Status DESC";
    }

    // Determine if we are filtering
    if ($filter != '') {
      $people = $this->like('LastName', $filter);
      $people = $this->orLike('FirstName', $filter);
      $people = $this->orLike('DisplayName', $filter);
      $people = $this->orLike('Organization', $filter);
    }

    // Return the People
    return $people->paginate($rows, 'default', $page);
  }
}
