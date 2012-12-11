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

class Alanstormdotcom_Unremove_Model_Observer
{
    const ROOTNODE = 'root';

    /**
     * Unremove update node from xml
     *
     * @param Varien_Event_Observer $observer observer
     *
     * @return void
     */
    public function unremoveUpdate(Varien_Event_Observer $observer)
    {
        $update = $observer->getLayout()->getUpdate();          
        $originalUpdates = $update->asArray();         
        $update->resetUpdates();            
        
        $toUnremove  = $this->_getUnremoveNames($this->_getSimplexmlFromFragment(implode('', $originalUpdates)));

        foreach ($originalUpdates as $sXmlUpdate) {      
            $sXmlUpdate = $this->_processUnremoveNodes($sXmlUpdate, $toUnremove);
            $update->addUpdate($sXmlUpdate);
        }           
    }
    
    /**
     * Process removed nodes
     *
     * @param string $string     xml string
     * @param array  $toUnremove blocks to unremove
     *
     * @return string
     */
    protected function _processUnremoveNodes($string, $toUnremove)
    {
        $oXmlUpdate = $this->_getSimplexmlFromFragment($string);
        $nodes = $oXmlUpdate->xpath('//remove');
        foreach ($nodes as $node) {
            if (in_array($node['name'], $toUnremove)) {
                unset($node['name']);
            }               
        }

        $sXml = '';
        foreach ($oXmlUpdate->children() as $node) {
            $sXml .= $node->asXml();
        }
        return $sXml;

    }
    
    /**
     * Get unremove nodes names
     *
     * @param SimpleXMLElement $xml xml object
     *
     * @return array
     */
    protected function _getUnremoveNames($xml)
    {
        $nodes      = $xml->xpath('//unremove');
        $unremove   = array();
        foreach ($nodes as $node) {
            $unremove[] = (string) $node['name'];
        }               
        return $unremove;
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
}