<?php
session_start();
require_once('../directory.php');

if (!function_exists('debug')) {
  function debug($value) {
    echo '<pre>'.print_r($value, true).'</pre>';
  }
}

class CORALInstaller {

  protected $db;
  public $error;
  protected $statusNotes;
  protected $config;
  protected $updates = array(
    "1.2" => array(
      "privileges" => array("ALTER","CREATE"),
      "installedTablesCheck" => array("Version"),
      "description" => "<p>The 1.2 update includes the following</p>
      <ul>
        <li>Misc. bug fixes</li>
        <li>Migrated from deprecated mysql to mysqli functions</li>
        <li>Added form validation</li>
        <li>SUSHI improvements</li>
      </ul>
      <p>The database change will increae the number of the allowed characters in the TitleIdentifer table.</p>"
    ),
    "1.1" => array(
      "privileges" => array("ALTER","CREATE","DROP"),
      "installedTablesCheck" => array("Layout"),
      "description" => "<p>The 1.1 update to the CORAL Usage module includes a number of enhancements:</p>
      <ul>
        <li>Add support for JR1 R4 and JR1a R4, DB1, BR1, BR2</li>
        <li>Add support for SUSHI harvesting</li>
      </ul>
      <p>This upgrade will connect to MySQL and run the CORAL Usage structure changes. No changes to the configuration file are required.  Database structure changes include:</p>
    	<ul>
    		<li>Adding Sushi and layout configuration tables, and also link imports to platforms
    		<li>Numerous indexes to improve search performance</li>
    	</ul>"
    )
  );

  public function __construct() {
    $this->statusNotes = array();
    if (is_file($this->configFilePath())) {
      $this->statusNotes['config_file'] = "Configuration file found: ". $this->configFilePath();
      $this->config = new Configuration();
      $this->connect();
    } else {
      $this->statusNotes['config_file'] = "Configuration file not present: ". $this->configFilePath();
    }
  }

  public function connect($username = null, $password = null) {
    $this->error = '';
		$host = $this->config->database->host;
		if ($username === null) {
		  $username = $this->config->database->username;
		}
		if ($password === null) {
		  $password = $this->config->database->password;
	  }
		$this->db = @mysqli_connect($host, $username, $password, $this->config->database->name);
		if (!$this->db) {

		  $this->error = mysqli_error($this->db);
		}

		if ($this->error) {
      $this->statusNotes['database_connection'] = "Database connection failed: ".$this->error;
		  $this->db = null;
		} else {
      $this->statusNotes['database_connection'] = "Database connection successful";
    }
	}

	public function query($sql) {
		$result = mysqli_query($this->db, $sql);

		$this->checkForError();
		$data = array();

	 if ($result instanceof mysqli_result) {

      while ($row = mysqli_fetch_array($result)) {
        if (mysqli_affected_rows($this->db) > 1) {
          array_push($data, $row);
        } else {
          $data = $row;
        }
      }
      mysqli_free_result($result);
    } else if ($result) {
      $data = mysqli_insert_id($this->db);
    }

    return $data;
  }

  protected function checkForError() {
    if ($this->error = mysqli_error($this->db)) {
      throw new Exception("There was a problem with the database: " . $this->error);
    }
  }

	public function getDatabase() {
	  return $this->db;
	}

  public function addErrorMessage($error) {
    if (!$this->hasErrorMessages()) {
      $_SESSION['installer_error_messages'] = array();
    }
    $_SESSION['installer_error_messages'] []= $error;
  }

  public function hasErrorMessages() {
    return isset($_SESSION['installer_error_messages']);
  }

  public function displayErrorMessages() {
    if ($this->hasErrorMessages()) {
			echo "<div style='color:red'><p><b>The following errors occurred:</b></p><ul>";
			foreach ($_SESSION['installer_error_messages'] as $err) {
				echo "<li>" . $err . "</li>";
			}
			echo "</ul></div>";
			unset($_SESSION['installer_error_messages']);
		}
  }

  public function addMessage($msg) {
    if (!$this->hasMessages()) {
      $_SESSION['installer_messages'] = array();
    }
    $_SESSION['installer_messages'] []= $msg;
  }

  public function hasMessages() {
    return isset($_SESSION['installer_messages']);
  }

  public function displayMessages() {
    if ($this->hasMessages()) {
			echo "<div style='color:green'><ul>";
			foreach ($_SESSION['installer_messages'] as $msg) {
				echo "<li>" . $msg . "</li>";
			}
			echo "</ul></div>";
			unset($_SESSION['installer_messages']);
		}
  }

  public function modulePath() {
    //returns file path for this module, i.e. /coral/usage/
    $replace_path = preg_quote(DIRECTORY_SEPARATOR."install");
    return preg_replace("@$replace_path$@", "", dirname(__FILE__));
  }

  public function configFilePath() {
    return $this->modulePath().'/admin/configuration.ini';
  }

  public function isDatabaseConfigValid() {
    return $this->config && $this->db;
  }

  public function hasPermission($permission) {
    if ($this->isDatabaseConfigValid()) {
      $grants = array();
      $permission = "(ALL PRIVILEGES|".strtoupper($permission).")";
      foreach ($this->query("SHOW GRANTS FOR CURRENT_USER()") as $row) {
        $grant = $row[0];
        if (strpos(str_replace('\\', '', $grant), $this->config->database->name) || strpos($grant, "ON *.*")) {
          if (preg_match("/(GRANT|,) $permission(,| ON)/i",$grant)) {
            return true;
          }
        }
      }
    }
    return false;
  }

  public function hasPermissions($permissions) {
    foreach($permissions as $permission) {
      if (!$this->hasPermission($permission)) {
        return false;
      }
    }
    return true;
  }

  public function tableExists($table) {
    $query = "SELECT count(*) count FROM information_schema.`COLUMNS` WHERE table_schema = '" . $this->config->database->name . "' AND table_name='". $table ."'";
    foreach ($this->query($query) as $row) {
      if ($row['count'] > 0) {
        return true;
      }
    }
    return false;
  }

  public function indexExists($table, $index) {
    $result = $this->query("SHOW INDEXES FROM $table WHERE Key_name = '$index'");
    return count($result) > 0;
  }

  public function installed() {
    if ($this->isDatabaseConfigValid()) {
      $installedTablesCheck = array("Publisher","ImportLog");
      foreach ($installedTablesCheck as $table) {
        if (!$this->tableExists($table)) {
          $this->statusNotes["installed"] = "Module not installed. Could not find table: ".$table;
          return false;
        }
      }
      $this->statusNotes["installed"] = "Module already installed. Found tables: ".implode(", ", $installedTablesCheck);
      return true;
    }
    return false;
  }

  public function debuggingNotes() {
    return "<h4>Installation Debugging:</h4><p>".implode('<br/>', $this->statusNotes)."</p>";
  }

  public function getNextUpdateVersion() {
    foreach($this->updates as $version => $details) {
      if (!$this->isUpdateInstalled($version)) {
        return $version;
      }
    }
  }

  public function isUpdateReady($version) {
    return $this->installed() && $this->getNextUpdateVersion() == $version;
  }

  public function isUpdateInstalled($version) {
    if ($this->installed()) {
      $installedTablesCheck = $this->updates[$version]["installedTablesCheck"];
      if ($installedTablesCheck) {
        foreach ($installedTablesCheck as $table) {
          if (!$this->tableExists($table)) {
            $this->statusNotes["version_".$version] = "Version $version not installed. Could not find table: ".$table;
            return false;
          }
        }
        $this->statusNotes["version_".$version] = "Version $version already installed. Found tables: ".implode(", ", $installedTablesCheck);
        return true;
      }
      else
      {
        $query = "SELECT version FROM ".$this->config->database->name.".Version WHERE version = '".$version."'";
        try{
          $databaseVersion = $this->query($query);
        }
        catch (Exception $e){
          //$this->statusNotes["version_".$version."_db_error"] = $e;
        };

        if($version == $databaseVersion[0]){
          $this->statusNotes["version_".$version] = "Version $version already installed.";
          return true;
        }else{
          $this->statusNotes["version_".$version] = "Version $version not installed. Could not find the version in the Version table";
          return false;
        }  
      }
    }
    return false;
  }

  public function getUpdate($version) {
    return $this->updates[$version];
  }

  public function upToDate() {
    if (!$this->installed() || $this->getNextUpdateVersion()) {
      return false;
    } else {
      return true;
    }
  }

  public function header($title = 'CORAL Installation') {
    include('header.php');
  }

  public function footer() {
    $installer = $this;
    include('footer.php');
  }
}
?>
