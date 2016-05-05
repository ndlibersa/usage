<?php
/*
**************************************************************************************************************************
** CORAL Usage Statistics Module
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


class Configuration extends DynamicObject {

	public function init(NamedArguments $arguments) {
		$arguments->setDefaultValueForArgumentName('filename', BASE_DIR . 'admin/configuration.ini');
		if ((file_exists($arguments->filename)) and (is_readable($arguments->filename))) {
			$config = parse_ini_file($arguments->filename, true);
		} else {
			die( BASE_DIR . 'admin/configuration.ini is missing or unreadable.');
		}
		foreach ($config as $section => $entries) {
			$this->$section = Utility::objectFromArray($entries);
		}
	}

}

?>