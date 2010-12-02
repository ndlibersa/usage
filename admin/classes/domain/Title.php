<?php

/*
**************************************************************************************************************************
** CORAL Usage Statistics Module v. 1.0
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/

class Title extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	protected function defineAttributes() {
		$this->addAttribute('titleID');
		$this->addAttribute('title');
	}

	//returns issn only of the first print issn found for this title
	public function getPrintISSN(){

		$query = "SELECT *
					FROM TitleISSN ti
					WHERE ti.titleID = '" . $this->titleID . "'
					AND ISSNType='print'
					ORDER BY issnType DESC
					LIMIT 1;";

		$result = $this->db->processQuery($query, 'assoc');

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['titleISSNID'])){
			$issn =  $result['issn'];
			return substr($issn, 0, 4) . "-" . substr($issn, 4, 4);
		}else{
			return;
		}


	}



	//returns issn only of the first online issn found for this title
	public function getOnlineISSN(){

		$query = "SELECT *
					FROM TitleISSN ti
					WHERE ti.titleID = '" . $this->titleID . "'
					AND ISSNType='online'
					ORDER BY issnType DESC
					LIMIT 1;";

		$result = $this->db->processQuery($query, 'assoc');

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['titleISSNID'])){
			$issn =  $result['issn'];
			return substr($issn, 0, 4) . "-" . substr($issn, 4, 4);
		}else{
			return;
		}


	}




	//find out if there is an existing issn for a title
	public function getExistingOnlineISSN($testISSN){

		$query = "SELECT distinct issn
					FROM TitleISSN ti
					WHERE ti.titleID = '" . $this->titleID . "'
					AND issn = '" . $testISSN . "'
					AND ISSNType='online'
					ORDER BY issnType DESC
					LIMIT 1;";

		$result = $this->db->processQuery($query, 'assoc');

		//only one issn will be returned
		if (isset($result['issn'])){
			return true;
		}else{
			return false;
		}


	}


	//returns array of issn objects
	public function getISSNs(){

		$query = "SELECT *
					FROM TitleISSN ti
					WHERE ti.titleID = '" . $this->titleID . "'
					ORDER BY issnType DESC;";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['titleISSNID'])){
			$object = new TitleISSN(new NamedArguments(array('primaryKey' => $result['titleISSNID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new TitleISSN(new NamedArguments(array('primaryKey' => $row['titleISSNID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}


	//returns array of title objects
	public function getRelatedTitles(){

		$query = "SELECT DISTINCT t.titleID
					FROM TitleISSN ti, Title t, TitleISSN ti2
					WHERE ti.titleID = t.titleID
					AND ti.issn = ti2.issn
					AND ti2.titleID = '" . $this->titleID . "';";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['titleID'])){
			$object = new Title(new NamedArguments(array('primaryKey' => $result['titleID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new Title(new NamedArguments(array('primaryKey' => $row['titleID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}




	//returns array of yearly stats for this title
	public function getYearlyStats($archiveInd, $year, $publisherPlatformID){

		$query = "SELECT titleID, totalCount, ytdHTMLCount, ytdPDFCount
					FROM YearlyUsageSummary
					WHERE titleID = '" . $this->titleID . "'
					AND archiveInd ='" . $archiveInd . "'
					AND year='" . $year . "'
					AND publisherPlatformID = '" . $publisherPlatformID . "';";

		$result = $this->db->processQuery($query, 'assoc');

		$allArray = array();
		$resultArray = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['titleID'])){
			foreach (array_keys($result) as $attributeName) {
				$resultArray[$attributeName] = $result[$attributeName];
			}

			array_push($allArray, $resultArray);
		}else{
			foreach ($result as $row) {
				$resultArray = array();
				foreach (array_keys($row) as $attributeName) {
					$resultArray[$attributeName] = $row[$attributeName];
				}
				array_push($allArray, $resultArray);
			}
		}

		return $allArray;
	}


	//returns array of the first listed issn objects
	public function getByTitle($journalTitle, $printISSN, $onlineISSN, $publisherPlatformID){

		//default search to print ISSN only - we're confident that's the same title
		if ($printISSN) {
			$query = "SELECT DISTINCT ti.titleID as titleID FROM TitleISSN ti, Title t WHERE t.titleID = ti.titleID AND issnType = 'print' AND issn = '" . $printISSN . "' LIMIT 1;";
		//not so confident about online issn so we also search on common platform / publisher
		}else if ((!$printISSN) && ($onlineISSN)){
			$query = "SELECT DISTINCT t.titleID as titleID FROM TitleISSN ti, Title t, MonthlyUsageSummary mus WHERE t.titleID = ti.titleID AND issnType = 'online' AND issn = '" . $onlineISSN . "' AND t.titleID = mus.titleID AND publisherPlatformID = '" . $publisherPlatformID . "' AND ucase(title) = ucase('" . $journalTitle . "') LIMIT 1;";
		//this is a title search so we're also searching on common platform / publisher
		}else if ((!$printISSN) && (!$onlineISSN)){
			$query = "SELECT DISTINCT t.titleID as titleID FROM Title t, MonthlyUsageSummary mus WHERE t.titleID = mus.titleID AND publisherPlatformID = '" . $publisherPlatformID . "' AND ucase(title) = ucase('" . $journalTitle . "') LIMIT 1;";
		}



		$result = $this->db->processQuery($query, 'assoc');

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['titleID'])){
			return $result['titleID'];
		}else{
			return false;
		}

	}




	//remove an entire month for this title/publisher
	public function deleteMonth($archiveInd, $year, $month, $publisherPlatformID){

		//now formulate query
		$query = "DELETE FROM MonthlyUsageSummary
					WHERE archiveInd = '" . $archiveInd . "'
					AND titleID = '" . $this->titleID . "'
					AND publisherPlatformID = '" . $publisherPlatformID . "'
					AND year = '"  . $year . "'
					AND month = '" . $month . "';";

		return $this->db->processQuery($query);

	}


	//remove an entire month for this title/publisher
	public function deleteYearlyStats($archiveInd, $year, $publisherPlatformID){

		//now formulate query
		$query = "DELETE FROM YearlyUsageSummary
					WHERE archiveInd = '" . $archiveInd . "'
					AND titleID = '" . $this->titleID . "'
					AND publisherPlatformID = '" . $publisherPlatformID . "'
					AND year = '"  . $year . "';";

		return $this->db->processQuery($query);

	}


	//returns usage count only
	public function getUsageCountByMonth($archiveInd, $year, $month, $publisherPlatformID){

		$query = "SELECT usageCount FROM MonthlyUsageSummary
					WHERE archiveInd = '" . $archiveInd . "'
					AND titleID = '" . $this->titleID . "'
					AND publisherPlatformID = '" . $publisherPlatformID . "'
					AND year = '"  . $year . "'
					AND month = '" . $month . "';";



		$result = $this->db->processQuery($query, 'assoc');

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['usageCount'])){
			return $result['usageCount'];
		}else{
			return false;
		}

	}





	//returns array of the first listed issn objects
	public function getTotalCountByYear($archiveInd, $year, $publisherPlatformID){

		$query = "SELECT totalCount usageCount, ytdHTMLCount, ytdPDFCount FROM YearlyUsageSummary
					WHERE archiveInd = '" . $archiveInd . "'
					AND titleID = '" . $this->titleID . "'
					AND publisherPlatformID = '" . $publisherPlatformID . "'
					AND year = '"  . $year . "';";



		$result = $this->db->processQuery($query, 'assoc');

		$resultArray = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['usageCount'])){
			 $resultArray['usageCount'] = $result['usageCount'];
			 $resultArray['ytdHTMLCount'] = $result['ytdHTMLCount'];
			 $resultArray['ytdPDFCount'] = $result['ytdPDFCount'];

			 return $resultArray;
		}else{
			return false;
		}

	}



	//returns array of yearly stats for this title
	public function get12MonthUsageCount($archiveInd, $publisherPlatformID, $yearAddWhere){

		$query = "SELECT usageCount FROM MonthlyUsageSummary
					WHERE archiveInd = '" . $archiveInd . "'
					AND titleID = '" . $this->titleID . "'
					AND publisherPlatformID = '" . $publisherPlatformID . "'
					AND "  . $yearAddWhere . ";";

		$result = $this->db->processQuery($query, 'assoc');

		$allArray = array();
		$resultArray = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['usageCount'])){
			foreach (array_keys($result) as $attributeName) {
				$resultArray[$attributeName] = $result[$attributeName];
			}

			array_push($allArray, $resultArray);
		}else{
			foreach ($result as $row) {
				$resultArray = array();
				foreach (array_keys($row) as $attributeName) {
					$resultArray[$attributeName] = $row[$attributeName];
				}
				array_push($allArray, $resultArray);
			}
		}

		return $allArray;
	}








}

?>