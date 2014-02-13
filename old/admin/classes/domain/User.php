<?php

/*
**************************************************************************************************************************
** CORAL Organizations Module v. 1.0
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

class User extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {
		$this->primaryKeyName = 'loginID';
	}


	//used only for allowing access to admin page
	public function isAdmin(){
		$privilege = new Privilege(new NamedArguments(array('primaryKey' => $this->privilegeID)));

		if (strtoupper($privilege->shortName) == 'ADMIN'){
			return true;
		}else{
			return false;
		}

	}

	//used for displaying add/update/delete links
	public function canEdit(){
		$privilege = new Privilege(new NamedArguments(array('primaryKey' => $this->privilegeID)));

		if ((strtoupper($privilege->shortName) == 'ADD/EDIT') || (strtoupper($privilege->shortName) == 'ADMIN')){
			return true;
		}else{
			return false;
		}

	}



	public function allAsArray() {
		$query = "SELECT * FROM User ORDER BY 1";
		$result = $this->db->processQuery($query, 'assoc');

		$resultArray = array();
		$rowArray = array();

		if (isset($result['loginID'])){
			foreach (array_keys($result) as $attributeName) {
				$rowArray[$attributeName] = $result[$attributeName];
			}
			array_push($resultArray, $rowArray);
		}else{
			foreach ($result as $row) {
				foreach (array_keys($this->attributeNames) as $attributeName) {
					$rowArray[$attributeName] = $row[$attributeName];
				}
				array_push($resultArray, $rowArray);
			}
		}

		return $resultArray;
	}
}

?>