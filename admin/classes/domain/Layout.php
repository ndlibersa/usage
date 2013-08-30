<?php

/*
**************************************************************************************************************************
** CORAL Usage Statistics Module v. 1.1
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

class Layout extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}



	public function getByLayoutCode($layoutCode){
		if (isset($layoutCode)) {
			$query = "SELECT * FROM `$this->tableName` WHERE `layoutCode` = '$layoutCode'";
			$result = $this->db->processQuery($query, 'assoc');

			foreach (array_keys($result) as $attributeName) {
				$this->addAttribute($attributeName);
				$this->attributes[$attributeName] = $result[$attributeName];
			}
		}
	}



	//returns array of layouts
	public function getLayouts(){

		//now formulate query
		$query = "SELECT layoutID, layoutCode, name
					FROM Layout
					ORDER BY layoutID";


		$result = $this->db->processQuery(stripslashes($query), 'assoc');


		$searchArray = array();
		$resultArray = array();

		//need to do this since it could be that there's only one result and this is how the dbservice returns result
		if (isset($result['layoutID'])){

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
