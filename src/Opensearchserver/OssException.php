<?php
/*
*  This file is part of OpenSearchServer PHP Client.
*
*  Copyright (C) 2013 Emmanuel Keller / Jaeksoft
*
*  http://www.open-search-server.com
*
*  OpenSearchServer PHP Client is free software: you can redistribute it and/or modify
*  it under the terms of the GNU Lesser General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  OpenSearchServer PHP Client is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU Lesser General Public License for more details.
*
*  You should have received a copy of the GNU Lesser General Public License
*  along with OpenSearchServer PHP Client.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @file
 * Class to access OpenSearchServer API
 */

namespace Opensearchserver;

/**
 * Open Search Server Exception
 * @author pmercier <pmercier@open-search-server.com>
 * @package OpenSearchServer
 * FIXME Complete this documentation
 */
class OssException extends \RuntimeException
{
    private $status;
    protected $message;

    public function __construct($xml)
    {
        if ($xml instanceof SimpleXMLElement) {
            $xmlDoc = $xml;
        } elseif ($xml instanceof DOMDocument) {
            $xmlDoc = simplexml_import_dom($xml);
        } else {
            $previous_error_level = error_reporting(0);
            $xmlDoc = simplexml_load_string($xml);
            error_reporting($previous_error_level);

            if (!$xmlDoc) {
                throw new \RuntimeException('The provided parameter is not a valid XML data. Please use OSSAPI::isOSSError before throwing this exception.');
            }
        }

        $data = array();
        foreach ($xmlDoc->entry as $entry)
            $data[(string) $entry['key']] = (string) $entry;

        $this->status = $data['Status'];

        parent::__construct($data['Exception'], 0);

    }

    /**
     * Return the error status from the search engine
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}