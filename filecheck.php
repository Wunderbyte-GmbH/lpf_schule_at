<?php
   $VERSION = "0.1.3";
   include "config.php";
   #Bestimme Instanznamen
   $current_directory = "";
   $cur_pieces = explode("/",$_SERVER['PHP_SELF']);
   $cur_index = 1;
   if ($cur_pieces){
      $current_directory = strtolower($cur_pieces[$cur_index]);
      $scriptname = strtolower($cur_pieces[2]);
   } else {
      $current_directory = 'x';
   }

   #SEITENDOMAIN BESTIMMEN
   $SITE_DOMAIN="https://".$_SERVER['HTTP_HOST'];
   $COURSEVIEW = $SITE_DOMAIN."/".$current_directory."/course/view.php?id=";
   $BACKUPVIEW = $SITE_DOMAIN."/".$current_directory."/backup/restorefile.php?contextid=";

   $EXCLUSIVE_HTTP = "http";
   $EXCLUSIVE_DOMAIN = "onlinecampus.virtuelle-ph.at";
   $EXCLUSIVE_DIRECTORY = "vphonline";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "http";
   $EXCLUSIVE_DOMAIN = "onlinecampus-profil.virtuelle-ph.at";
   $EXCLUSIVE_DIRECTORY = "vphprofil";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "http";
   $EXCLUSIVE_DOMAIN = "epict.virtuelle-ph.at";
   $EXCLUSIVE_DIRECTORY = "vphepict";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "https";
   $EXCLUSIVE_DOMAIN = "lernplattform.kph-es.at";
   $EXCLUSIVE_DIRECTORY = "kphes";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "https";
   $EXCLUSIVE_DOMAIN = "lema.tirol";
   $EXCLUSIVE_DIRECTORY = "lema";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "https";
   $EXCLUSIVE_DOMAIN = "moodle.ph-ooe.at";
   $EXCLUSIVE_DIRECTORY = "phooe";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "http";
   $EXCLUSIVE_DOMAIN = "moodle2.ph-linz.at";
   $EXCLUSIVE_DIRECTORY = "phdl2";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "http";
   $EXCLUSIVE_DOMAIN = "iicc.at";
   $EXCLUSIVE_DIRECTORY = "iicc";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   $EXCLUSIVE_HTTP = "http";
   $EXCLUSIVE_DOMAIN = "basiskurs.digikomp.at";
   $EXCLUSIVE_DIRECTORY = "basiskurs";
   if ($_SERVER["SERVER_NAME"] == $EXCLUSIVE_DOMAIN || $_SERVER["SERVER_NAME"] == "www.".$EXCLUSIVE_DOMAIN){
      $current_directory = $EXCLUSIVE_DIRECTORY;
      $SITE_DOMAIN=$EXCLUSIVE_HTTP."://".$EXCLUSIVE_DOMAIN;
      $COURSEVIEW = $SITE_DOMAIN."/course/view.php?id=";
      $BACKUPVIEW = $SITE_DOMAIN."/backup/restorefile.php?contextid=";
   }

   #SQL Verbiindung
   $DB_HOST = $CFG->dbhost;
   $DB_NAME = $CFG->dbname;
   $DB_USER = $CFG->dbuser;
   $DB_PASS = $CFG->dbpass;

   $DATAROOT = $CFG->dataroot;

   #Pruefe Uebergabevariablen
   $MOD = "";
   $MOD = $_GET['mod'];

   function MainScreen() {
      global $current_directory, $DATAROOT, $scriptname, $DB_HOST,  $DB_NAME, $DB_USER, $DB_PASS, $COURSEVIEW, $BACKUPVIEW;
      $pdo = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME.'', $DB_USER, $DB_PASS);

      echo "<h1>Filecheck: ".$current_directory."</h1>";

      echo "<table class=\"table-status\">";
         echo "<tr>";
            echo "<th align=\"left\">Komponente</th>";
            echo "<th align=\"left\">Dateibereich</th>";
            echo "<th align=\"right\">Größe</th>";
         echo "</tr>";

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"course\" and  ".$current_directory."___files.filearea = \"legacy\";";

      $stmt = $pdo->query($sql);
      $COURSE_LEGACY_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"course\" and  ".$current_directory."___files.filearea = \"section\";";

      $stmt = $pdo->query($sql);
      $COURSE_SECTION_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"backup\" and  ".$current_directory."___files.filearea = \"course\";";

      $stmt = $pdo->query($sql);
      $BACKUP_COURSE_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"backup\" and  ".$current_directory."___files.filearea = \"automated\";";

      $stmt = $pdo->query($sql);
      $BACKUP_AUTOMATED_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"user\" and  ".$current_directory."___files.filearea = \"backup\";";

      $stmt = $pdo->query($sql);
      $USER_BACKUP_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"user\" and  ".$current_directory."___files.filearea = \"icon\";";

      $stmt = $pdo->query($sql);
      $USER_ICON_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"user\" and  ".$current_directory."___files.filearea = \"private\";";

      $stmt = $pdo->query($sql);
      $USER_PRIVATE_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"assignsubmission_file\" and  ".$current_directory."___files.filearea = \"submission_files\";";

      $stmt = $pdo->query($sql);
      $ASSIGNSUBMISSION_FILE_SUBMISSION_FILES_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"assignfeedback_file\" and  ".$current_directory."___files.filearea = \"feedback_files\";";

      $stmt = $pdo->query($sql);
      $ASSIGNFEEDBACK_FILE_FEEDBACK_FILES_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"blog\" and  ".$current_directory."___files.filearea = \"attachment\";";

      $stmt = $pdo->query($sql);
      $BLOG_ATTACHMENT_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"mod_resource\" and  ".$current_directory."___files.filearea = \"content\";";

      $stmt = $pdo->query($sql);
      $MOD_RESOURCE_CONTENT_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"mod_folder\" and  ".$current_directory."___files.filearea = \"content\";";

      $stmt = $pdo->query($sql);
      $MOD_FOLDER_CONTENT_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"mod_forum\" and  ".$current_directory."___files.filearea = \"attachment\";";

      $stmt = $pdo->query($sql);
      $MOD_FORUM_ATTACHMENT_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"core\" and  ".$current_directory."___files.filearea = \"preview\";";

      $stmt = $pdo->query($sql);
      $CORE_PREVIEW_SUM=$stmt->fetchColumn();

      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"question\" and  ".$current_directory."___files.filearea = \"questiontext\";";

      $stmt = $pdo->query($sql);
      $QUESTION_QUESTIONTEXT_SUM=$stmt->fetchColumn();


      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files;";

      $stmt = $pdo->query($sql);
      $DB_FILESIZE_SUM=$stmt->fetchColumn();

#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.filearea like \"%recyclebin%\";";
#      $stmt = $pdo->query($sql);
#      $RECYCLE_SUM=$stmt->fetchColumn();
#
#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.filearea = \"draft\";";
#      $stmt = $pdo->query($sql);
#      $DRAFT_SUM=$stmt->fetchColumn();
#
#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"backup\";";
#      $stmt = $pdo->query($sql);
#      $BACKUP_SUM=$stmt->fetchColumn();
#
#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.filearea = \"submission_files\";";
#      $stmt = $pdo->query($sql);
#      $SUBMISSION_SUM=$stmt->fetchColumn();
#
#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.filearea = \"attachment\";";
#      $stmt = $pdo->query($sql);
#      $ATTACHMENT_SUM=$stmt->fetchColumn();
#
#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.filearea = \"content\";";
#      $stmt = $pdo->query($sql);
#      $CONTENT_SUM=$stmt->fetchColumn();
#
#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.filearea = \"private\";";
#      $stmt = $pdo->query($sql);
#      $PRIVATE_SUM=$stmt->fetchColumn();

#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"course\";";
#      $stmt = $pdo->query($sql);
#      $COURSE_SUM=$stmt->fetchColumn();

#      $sql="select round(sum(filesize)/1000000, 2) as summe from ".$current_directory."___files where ".$current_directory."___files.component = \"mod_folder\" and  ".$current_directory."___files.filearea = \"content\";";
#      $stmt = $pdo->query($sql);
#      $MOD_FOLDER_CONTENT_SUM=$stmt->fetchColumn();


 
      $SUM_SUM=0;

      echo "<tr>";
         echo "<td><a href=\"?mod=course\">Course</a></td>";
         echo "<td><div class=\"tooltip\">Legacy<span class=\"tooltiptext\">Alte Kursdateien vor Moodle 2.0.</span></div></td>";
         $TABLE_VALUE = $COURSE_LEGACY_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td><a href=\"?mod=course\">Course</a></td>";
         echo "<td><div class=\"tooltip\">Section<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $COURSE_SECTION_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";


      echo "<tr>";
         echo "<td><a href=\"?mod=backup\">Backup</a></td>";
         echo "<td><div class=\"tooltip\"><a href=\"?mod=course\">Course</a><span class=\"tooltiptext\">Automatisch erstellte Kurssicherungen</span></div></td>";
         $TABLE_VALUE = $BACKUP_COURSE_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td><a href=\"?mod=backup\">Backup</a></td>";
         echo "<td><div class=\"tooltip\">Automated<span class=\"tooltiptext\">Automatisch erstellte Sicherungen</span></div></td>";
         $TABLE_VALUE = $BACKUP_AUTOMATED_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";


      echo "<tr>";
         echo "<td>User</td>";
         echo "<td><div class=\"tooltip\"><a href=\"?mod=backup\">Backup</a><span class=\"tooltiptext\">Manuell ausgeführte Kurssicherungen</span></div></td>";
         $TABLE_VALUE = $USER_BACKUP_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";
      echo "<tr>";
         echo "<td>User</td>";
         echo "<td><div class=\"tooltip\">Icon<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $USER_ICON_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>User</td>";
         echo "<td><div class=\"tooltip\">Private<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $USER_PRIVATE_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";


      echo "<tr>";
         echo "<td>Blog</td>";
         echo "<td><div class=\"tooltip\">Attachment<span class=\"tooltiptext\">Anhänge</span></div></td>";
         $TABLE_VALUE = $BLOG_ATTACHMENT_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>mod_resource</td>";
         echo "<td><div class=\"tooltip\">Content<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $MOD_RESOURCE_CONTENT_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>mod_folder</td>";
         echo "<td><div class=\"tooltip\">Content<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $MOD_FOLDER_CONTENT_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>assignsubmission_file</td>";
         echo "<td><div class=\"tooltip\">submission_file<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $ASSIGNSUBMISSION_FILE_SUBMISSION_FILES_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>assignfeedback_file</td>";
         echo "<td><div class=\"tooltip\">feedback_file<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $ASSIGNFEEDBACK_FILE_FEEDBACK_FILES_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>Core</td>";
         echo "<td><div class=\"tooltip\">Preview<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $CORE_PREVIEW_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>Question</td>";
         echo "<td><div class=\"tooltip\">Questiontext<span class=\"tooltiptext\">???</span></div></td>";
         $TABLE_VALUE = $QUESTION_QUESTIONTEXT_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";

      echo "<tr>";
         echo "<td>mod_forum</td>";
         echo "<td><div class=\"tooltip\">Attachment<span class=\"tooltiptext\">Anhänge in Forenbeiträgen</span></div></td>";
         $TABLE_VALUE = $MOD_FORUM_ATTACHMENT_SUM;
         $SUM_SUM += $TABLE_VALUE;
         if ($TABLE_VALUE < 1000){echo "<td align=\"right\">".$TABLE_VALUE." MB</td>";} else {echo "<td align=\"right\">".round($TABLE_VALUE / 1000, 2)." GB</td>";};
      echo "</tr>";


      echo "<tr>";
      echo "<td></td>";
      echo "<td/></td><td align=\"right\">__________</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td></td>";
      $TABLE_VALUE=$SUM_SUM;
      if ($TABLE_VALUE < 1000){echo "<td/><td align=\"right\"><b>".$TABLE_VALUE." MB</b></td>";} else {echo "<td/><td align=\"right\"><b>".round($TABLE_VALUE / 1000, 2)." GB</b></td>";};
      echo "</tr>";












      echo "</table>";
      
      echo "<h2>Gesamtgröße lt. Datenbank: ";
if ($DB_FILESIZE_SUM < 1000){echo "<b>".$DB_FILESIZE_SUM." MB</b></h2>";} else {echo "<b>".round($DB_FILESIZE_SUM / 1000, 2)." GB</b></h2>";};
   }

function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}

   function CourseScreen($CSORT = 3) {
      global $current_directory, $DATAROOT, $scriptname, $DB_HOST,  $DB_NAME, $DB_USER, $DB_PASS, $COURSEVIEW, $BACKUPVIEW;
      $pdo = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME.'', $DB_USER, $DB_PASS);

      echo "<h1>Kurscheck: ".$current_directory."</h1>";
      echo "<a href=\"?mod=main\"><button class=\"button\">Zur&uuml;ck</button></a>";

      echo "<table class=\"table-content\">";
         echo "<tr>";
            echo "<th align=\"left\"><a href=\"?mod=course&csort=0\">id</a></th>";
            echo "<th align=\"left\"><a href=\"?mod=course&csort=1\">fullname</a></th>";
            echo "<th align=\"left\"><a href=\"?mod=course&csort=2\">shortname</a></th>";
            echo "<th align=\"right\"><a href=\"?mod=course&csort=3\">filesize</a></th>";
	    echo "<th align=\"right\"><a href=\"?mod=course&csort=4\">created</a></th>";
            echo "<th align=\"right\"><a href=\"?mod=course&csort=5\">modified</a></th>";
            echo "<th align=\"center\"></th>";
         echo "</tr>";

         $sql="SELECT id from ".$current_directory."___course";
         $stmt = $pdo->query($sql);
	 $COURSES=array();

	 #Sammle Kurse in Array
	 while ($row = $stmt->fetch()){
            $DB_ID=$row['id'];
                $sql2="SELECT fullname, shortname, FROM_UNIXTIME(c.timecreated, '%m-%d-%Y') as created, FROM_UNIXTIME(c.timemodified, '%m-%d-%Y') as modified, ROUND(sum(filesize)/1000000, 2) as filesize FROM `".$current_directory."___files` f INNER JOIN ".$current_directory."___context cn ON cn.id = f.contextid INNER JOIN ".$current_directory."___course c ON c.id = cn.instanceid AND cn.contextlevel = 50 where c.id = ".$DB_ID.";";
		$stmt2 = $pdo->query($sql2);
		while ($row = $stmt2->fetch()){
		   $DB_COURSE_FULLNAME=$row['fullname'];
		   $DB_COURSE_SHORTNAME=$row['shortname'];
                   $DB_COURSE_FILESIZE=$row['filesize'];
                   $DB_COURSE_CREATED=$row['created'];
                   $DB_COURSE_MODIFIED=$row['modified'];

		   $COURSE_VALUES=array();
                   array_push($COURSE_VALUES, $DB_ID);
		   array_push($COURSE_VALUES, $DB_COURSE_FULLNAME);
		   array_push($COURSE_VALUES, $DB_COURSE_SHORTNAME);
		   array_push($COURSE_VALUES, $DB_COURSE_FILESIZE);
                   array_push($COURSE_VALUES, $DB_COURSE_CREATED);
                   array_push($COURSE_VALUES, $DB_COURSE_MODIFIED);
		   
		   array_push($COURSES, $COURSE_VALUES);
		}
         }
#	 #Erstelle Tabelle mit den gesammelten Kursen
		array_sort_by_column($COURSES, $CSORT, SORT_DESC);
		for ($row = 0; $row < count($COURSES); $row++) {
                   echo "<tr>";
                   echo "<td>".$COURSES[$row][0]."</td>";
                   echo "<td>".$COURSES[$row][1]."</td>";
                   echo "<td>".$COURSES[$row][2]."</td>";
                   $DB_FILESIZE = $COURSES[$row][3];
                   if ($DB_FILESIZE < 1000){echo "<td align=\"right\">".$DB_FILESIZE." MB</td>";};
                   if ($DB_FILESIZE > 1000){echo "<td align=\"right\">".round($DB_FILESIZE / 1000,2)." GB</td>";};
                   echo "<td align=\"right\">".$COURSES[$row][4]."</td>";
                   echo "<td align=\"right\">".$COURSES[$row][5]."</td>";
		   echo "<td align=\"center\"><a href=\"".$COURSEVIEW.$COURSES[$row][0]."\" target=\"_blank\">Verwalten</a></td>";
		   echo "</tr>";
                }


      echo "</table>";
   }

   function BackupScreen() {
      global $current_directory, $DATAROOT, $scriptname, $DB_HOST,  $DB_NAME, $DB_USER, $DB_PASS, $COURSEVIEW, $BACKUPVIEW;
      $pdo = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME.'', $DB_USER, $DB_PASS);

      echo "<h1>Backupcheck: ".$current_directory."</h1>";
      echo "<a href=\"?mod=main\"><button class=\"button\">Zur&uuml;ck</button></a>";

      echo "<table class=\"table-content\">";
         echo "<tr>";
            echo "<th align=\"left\">id</th>";
            echo "<th align=\"left\">filename</th>";
            echo "<th align=\"right\">filesize</th>";
            echo "<th align=\"right\">created</th>";
            echo "<th align=\"center\"></th>";
         echo "</tr>";

        $sql="SELECT id,concat(\"/\",substr(contenthash,1,2),\"/\",substr(contenthash,3,2),\"/\") as dir, contenthash as file, filename, ROUND(filesize/1000000, 2) as filesize,mimetype,FROM_UNIXTIME(timecreated, '%m-%d-%Y') as created,FROM_UNIXTIME(timemodified, '%m-%d-%Y') as modified, contextid, filearea, component FROM ".$current_directory."___files WHERE filesize > 00000000 AND component = \"backup\" ORDER BY filesize DESC;";
      $stmt = $pdo->query($sql);
      $FILESIZESUM = 0;
      while ($row = $stmt->fetch()){
        $DB_ID=$row['id'];
        $DB_FILENAME=$row['filename'];
        $DB_FILESIZE=$row['filesize'];
        $FILESIZESUM += $DB_FILESIZE;
        $DB_CREATED=$row['created'];
        $DB_FILE=$row['file'];
        $DB_DIR=$row['dir'];
        $DB_MIMETYPE=$row['mimetype'];
        $DB_CONTEXTID=$row['contextid'];
        $DB_FILEAREA=$row['filearea'];
        $DB_COMPONENT=$row['component'];
         echo "<tr>";
            echo "<td>".$DB_ID."</td>";
            echo "<td>".$DB_FILENAME."</td>";
            if ($DB_FILESIZE < 1000){echo "<td align=\"right\">".$DB_FILESIZE." MB</td>";};
	    if ($DB_FILESIZE > 1000){echo "<td align=\"right\">".round($DB_FILESIZE / 1000,2)." GB</td>";};
            echo "<td align=\"right\">".$DB_CREATED."</td>";
            echo "<td align=\"center\"><a href=\"".$BACKUPVIEW.$DB_CONTEXTID."\" target=\"_blank\">Verwalten</a></td>";
         echo "</tr>";
      }
   echo "</table>";

   echo "<h1>Summe: ".$FILESIZESUM." MB</h1>";
} 

if($SESSION->load_navigation_admin){
   echo "<style>";
      echo "h1 {font-family: arial; font-size: 25px; color: #317f34;}";
      echo "h2 {font-family: arial; font-size: 15px; color: #317f34;}";

      echo "a:link {color: #317f34;text-decoration: none;}";
      echo "a:visited {color: #317f34;text-decoration: none;}";

      echo ".table-content {font-family: arial; font-size: 13px; border-collapse: collapse; width: 100%; background-color: #dcefdc;}";
      echo ".table-content td, th {border: 0px solid #dddddd;padding: 4px; padding: 0px 60px 0px 0px;}";
      echo ".table-content tr:nth-child(even) {background-color: white;}";
      echo ".table-content tr:hover {background-color: #45a049;}";

      echo ".table-status {font-family: arial; color: #4CAF50; font-size: 15px; border-collapse: collapse;}";

      echo ".button {background-color: #4CAF50;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;margin: 4px 2px;cursor: pointer;}";
      echo "footer {font-family: Arial;font-size: 11px;color: white;text-align: right;background-color: #4CAF50; position: fixed; padding: 10px;bottom: 0;left: 0;right: 0;}";

     echo ".tooltip {position: relative;display: inline-block;border-bottom: 1px dotted black;}";
     echo ".tooltip .tooltiptext {visibility: hidden;width: 280px;background-color: #555;color: #fff;text-align: center;padding: 5px 0;border-radius: 6px;position: absolute;z-index: 1;bottom: 125%;left: 120%;margin-left: -60px;opacity: 0;transition: opacity 0.3s;}";
     echo ".tooltip .tooltiptext::after {content: \"\";position: absolute;top: 100%;left: 50%;margin-left: -5px;border-width: 5px;border-style: solid;border-color: #555 transparent transparent transparent;}";
     echo ".tooltip:hover .tooltiptext {visibility: visible;opacity: 1;}";
   echo "</style>";

   if ($MOD == "" or $MOD == "main") {
      MainScreen();
   }

   if ($MOD == "course") {
      CourseScreen($_GET['csort']);
   }

   if ($MOD == "backup") {
      BackupScreen();
   }


echo "<footer>Edugroup Moodle-Filecheck Version $VERSION (C) Education Group GmbH 2018 by M. Hauzenberger</footer>";

} else { #Kein Admin eingeloggt
   $ADMIN_PATH = $CFG->wwwroot . "/admin";
   header('Location: '.$ADMIN_PATH);
}
?>

