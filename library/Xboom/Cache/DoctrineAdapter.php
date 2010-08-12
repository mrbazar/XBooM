<?php

/**
 * An Adapter for using a Zend_Cache_Core-Instance as Query or Result-Cache
 * for Doctrine
 *
 * Offers an additional Prefix for its entries for usage within prefix-based
 * Cache-Structure (for example when using one Zend_Cache_Core for a complete
 * system)
 *
 * @author     Benjamin Steininger
 * @author     yugeon
 * @license    New BSD License
 * @category   Xboom
 * @package    Xboom_Cache
 * @todo       Add support for Tags to automatically tag all Entry made with a
 *             set of Tags provided by the constructor
 */
class Xboom_Cache_DoctrineAdapter extends Doctrine\Common\Cache\AbstractCache
{

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache = null;

    /**
     * @param string
     */
    protected $_prefix = '';

    public function __construct(Zend_Cache_Core $cache, $prefix = '')
    {
        $this->_cache = $cache;
        $this->_prefix = $prefix;
    }

    /**
     * Convert hexidecimal string to normal string
     * @param string $hexstr Hexidecimal string
     * @return string
     */
    protected function hex2str($hexstr)
    {
        $retstr = pack('H*', $hexstr);
        return $retstr;
    }

    /**
     * Convert string to hexidecimal presenter
     * @param string $string
     * @return string
     */
    protected function str2hex($string)
    {
        $hexstr = unpack('H*', $string);
        return array_shift($hexstr);
    }

    /**
     * Fetches an entry from the cache.
     *
     * @param string $id cache id The id of the cache entry to fetch.
     * @return string The cached data or FALSE, if no cache entry exists for the given id.
     */
    protected function _doFetch($id)
    {
        $hId = $this->str2hex($id);
        return $this->_cache->load($this->_prefix . $hId);
    }

    /**
     * Test if an entry exists in the cache.
     *
     * @param string $id cache id The cache id of the entry to check for.
     * @return boolean TRUE if a cache entry exists for the given cache id, FALSE otherwise.
     */
    protected function _doContains($id)
    {
        $hId = $this->str2hex($id);
        return (bool) $this->_cache->test($this->_prefix . $hId);
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id The cache id.
     * @param string $data The cache entry/data.
     * @param int $lifeTime The lifetime. If != false, sets a specific lifetime for this cache entry (null => infinite lifeTime).
     * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    protected function _doSave($id, $data, $lifeTime = false)
    {
        $hId = $this->str2hex($id);
        try
        {
            return $this->_cache->save($data, $this->_prefix . $hId, array(), $lifeTime);
        }
        catch (Zend_Cache_Exception $e)
        {
            return false;
        }
    }

    /**
     * Deletes a cache entry.
     *
     * @param string $id cache id
     * @return boolean TRUE if the cache entry was successfully deleted, FALSE otherwise.
     */
    protected function _doDelete($id)
    {
        $hId = $this->str2hex($id);
        return $this->_cache->remove($this->_prefix . $hId);
    }

    /**
     * Get an array of all the cache ids stored
     *
     * @return array $ids
     */
    public function getIds()
    {
        return array_map(array($this, 'hex2str'), $this->_cache->getIds());
    }

}