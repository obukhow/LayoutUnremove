<?php
/**
 * Copyright (c) 2011 Alan Storm
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Layout filter model
 *
 * @category   Alanstormdotcom
 * @package    Alanstormdotcom_Unremove
 * @subpackage Model
 * @author     Denis Obukhov <roomine@bolevar.com>
 */
class Alanstormdotcom_Unremove_Model_Layout_Filter
{
    const ROOTNODE = 'root';

    /**
     * XML nodes filters
     *
     * @var array
     */
    protected $_filters = array();

    /**
     * XML object
     *
     * @var SimpleXMLElement
     */
    protected $_xml;

    /**
     * Set filter xml object
     *
     * @param array $updates updates array
     *
     * @return Alanstormdotcom_Unremove_Model_Layout_Filter
     */
    public function setXml(Array $updates)
    {
        $this->_xml = $this->_getSimplexmlFromFragment(implode('', $updates));
        return $this;
    }
    
    /**
     * Filter update nodes
     *
     * @param string $xmlNodeString xml string
     *
     * @return string
     */
    public function filterUpdateNode($xmlNodeString)
    {
        $oXmlUpdate = $this->_getSimplexmlFromFragment($xmlNodeString);
        foreach ($this->_filters as $key => $value) {
            extract($value);
            if (!$values) {
                continue;
            }
            $nodes = $oXmlUpdate->xpath("//$key");
            foreach ($nodes as $node) {
                if (in_array($node[$attribute], $values)) {
                    unset($node[$attribute]);
                }               
            }
        }

        $sXml = '';
        foreach ($oXmlUpdate->children() as $node) {
            $sXml .= $node->asXml();
        }
        return $sXml;

    }
    
    /**
     * Add node attributes by path to filter
     *
     * @param string $path      path name
     * @param string $attribute attribute name
     *
     * @return Alanstormdotcom_Unremove_Model_Layout_Filter
     */
    public function addNodeAttributesByPathFilter($path, $attribute)
    {
        $nodes = $this->getXml()->xpath("//un$path");
        $this->_filters[$path] = array(
            'attribute' => $attribute,
            'values'    => array(),
        );
        foreach ($nodes as $node) {
            $this->_filters[$path]['values'][] = (string) $node[$attribute];
        }               
        return $this;
    }

    /**
     * Add unremove nodes names to filter
     *
     * @return Alanstormdotcom_Unremove_Model_Layout_Filter
     */
    public function addRemoveNodesFilter()
    {
        return $this->addNodeAttributesByPathFilter('remove', 'name');
    }

    /**
     * Add unaction nodes methods to filter
     *
     * @return Alanstormdotcom_Unremove_Model_Layout_Filter
     */
    public function addActionMethodsFilter()
    {
        return $this->addNodeAttributesByPathFilter('action', 'method');
    }
    
    /**
     * Create xml object from string
     *
     * @param string $fragment fragment
     *
     * @return SimpleXMLElement
     */
    protected function _getSimplexmlFromFragment($fragment)
    {
        return simplexml_load_string('<'.self::ROOTNODE.'>'.$fragment.'</'.self::ROOTNODE.'>');     
    }

    /**
     * Get layout XML object
     *
     * @return SimpleXMLElement
     */
    public function getXml()
    {
        return $this->_xml;
    }
}