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

class ImportLog extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}


	//returns array of import log records
	public function getImportLogRecords($limit){

		if ($limit != ""){
			$limitStatement = " LIMIT " . $limit;
		}else{
			$limitStatement = "";
		}


		//now formulate query
		$query = "SELECT importLogID, loginID, importDateTime dateTime, fileName, archiveFileURL, logFileURL, details
					FROM ImportLog
					ORDER BY importDateTime DESC " . $limitStatement;


		$result = $this->db->processQuery(stripslashes($query), 'assoc');


		$searchArray = array();
		$resultArray = array();

		//need to do this since it could be that there's only one result and this is how the dbservice returns result
		if (isset($result['importLogID'])){

			foreach (array_keys($result) as $attributeName) {
				$resultArray[$attributeName] = $result[$attributeName];
			}

			array_push($searchArray, $resultArray);
		}else{
			foreach ($result as $row) {
				$resultArray = array();
				foreach (array_keys($row) as $attributeName) {
					$resultArray[$attributeName] = $row[$attributeName];
				}
				array_push($searchArray, $resultArray);
			}
		}

		return $searchArray;


	}





}

?>