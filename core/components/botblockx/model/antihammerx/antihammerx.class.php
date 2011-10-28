<?php

/**
 * AntiHammerX
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 *
 * @author Bob Ray <http://bobsguides.com>
 * 1/15/11
 *
 * AntiHammerX is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * AntiHammerX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * AntiHammerX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package antihammerx
 */
/**
 * MODx AntiHammerX Class
 *
 * @version Version 1.0.0 Beta-1
 *
 * @package  antihammerx
 *
 * Description
 */

/* Example Class File */
class AntiHammerX {

   /**
    * @var modx object Reference pointer to modx object
    */
    protected $modx;
    /**
     * @var array scriptProperties array
     */
    protected $props;
    /**
     * @var array Array of all TVs
     */
    protected $allTvs;
    /**
     * @var array Array of error messages
     */
    protected $errors;
    /**
     * @var modResource The current resource
     */
    protected $resource;
    /**
     * @var int ID of the resource's parent
     */
    protected $parentId;
    /**
     * @var modResource The parent object
     */
    protected $parentObj;
    /**
     * @var boolean Indicates that we're editing an existing resource (ID of resource) 
     */
    protected $existing;
    /**
     * @var boolean Indicates a repost to self
     */
    protected $isPostBack;
    /**
     * @var string Path to AntiHammerX Core
     */
    protected $corePath;
    /**
     * @var string Path to AntiHammerX assets directory
     */
    protected $assetsPath;
    /**
     * @var string URL to AntiHammerX Assets directory
     */
    protected $assetsUrl;
    /**
     * @var boolean Use alias as title
     */
    protected $aliasTitle;
    /**
     * @var boolean Clear the cache after saving doc
     */
    protected $clearcache;
    /**
     * @var string Content of optional header chunk
     */
    protected $header;
    /**
     * @var string Content of optional footer chunk
     */
    protected $footer;
    /**
     * @var int Max size of listbox TVs
     */
    protected $listboxMax;
    /**
     * @var int Max size of multi-select listbox TVs
     */
    protected $multipleListboxMax;
    /**
     * @var string prefix for placeholders
     */
    protected $prefix;
    /**
     * @var string Comma-separated list of words to remove
     */
    protected $badwords;
    /**
     * @var string Value for published resource field
     */
    protected $published;
    /**
     * @var string Value for hidemenu resource field
     */
    protected $hideMenu;
    /**
     * @var string Value for alias resource field
     */
    protected $alias;
    /**
     * @var string Value for cacheable resource field
     */
    protected $cacheable;
    /**
     * @var string Value for searchable resource field
     */
    protected $searchable;
    /**
     * @var string Value for template resource field
     */
    protected $template;
    /**
     * @var string Value for richtext resource field
     */
    protected $richtext;
    /**
     * @var array Array of Tpl chunk contents
     */
    protected $tpls;
    /**
     * @var string Comma-separated list or resource groups to
     * assign new docs to
     */
    protected $groups;
    /**
     * @var int Max length for integer input fields
     */
    protected $intMaxlength;
    /**
     * @var int Max length for text input fields
     */
    protected $textMaxlength;

    protected $config;
    /**
     * @var array configuration array
     */


    /** AntiHammerX constructor
     *
     * @access public
     * @param (reference object) $modx - modx object
     * @param (reference array) $props - scriptProperties array.
     */

    public function __construct(&$modx, &$props) {
        $this->modx =& $modx;
        $this->props =& $props;
        /* NP paths; Set the ahx. System Settings only for development */
        $this->corePath = $this->modx->getOption('ahx.core_path', null, MODX_CORE_PATH . 'components/antihammerx/');
        $this->assetsPath = $this->modx->getOption('ahx.assets_path', null, MODX_ASSETS_PATH . 'components/antihammerx/');
        $this->assetsUrl = $this->modx->getOption('ahx.assets_url', null, MODX_ASSETS_URL . 'components/antihammerx/');

    }

    public function init() {
        $this->config['data_path'] = MODX_CORE_PATH . 'components/antihammerx/data/';
        $this->config['debug_level'] = 4; /* 1 - 4, 0 for no debugging */
        $this->config['prefix'] = 'ahx_';
        $this->config['hammer_time'] = 90;
        $this->config['trigger_level'] = 5;
        $this->config['trigger_increment'] = 10;
        $this->config['initial_timeout'] = 5;
        $this->config['timeout_multiplier'] = '5';
        $this->config['auto_increment_trigger'] = false;
        $this->config['kill_level'] = 0;
        $this->config['kill_hours'] = 12;
        $this->config['log_path'] = MODX_CORE_PATH . 'components/antihammerx/log/';
        $this->config['log_violations'] = false;
        $this->config['agent_bypass_string'] = '';
        $this->config['error_email'] = $this->modx->getOption('emailsender');
        $this->config['email_errors'] = false;
        $this->config['log_ip_address'] = false;
        $this->config['hits_til_gc'] = 10000;
        $this->config['cleanup_age'] = 12;
        $this->config['contact_page_id'] = '';
        $this->config['id_prefix'] = 'ahx_';
        $this->config['total_kill'] = true; /* if true, die() if hammer_count is over limit */
        $this->config['total_kill_count'] = 10;

        $timeArray = explode(' ', microtime());
        $this->config['now'] = $timeArray[1].substr($timeArray[0], 6, -2); /* 1/100th of a second accuracy */
        settype($this->config['now'], "double"); /* make sure we're double precision */

    }
    public function getVisitorData() {
        $visitor_data = array();
              $visitor_data['remote_ip'] = $_SERVER['REMOTE_ADDR'];
              $visitor_data['user_agent'] = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : '';
              $visitor_data['referrer'] = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : '' ;
              $visitor_data['request'] = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : '';
              $visitor_data['user_accept'] = isset($_SERVER['HTTP_ACCEPT'])? $_SERVER['HTTP_ACCEPT'] : '';
              $visitor_data['user_charset'] = isset($_SERVER['HTTP_ACCEPT_CHARSET'])? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
              $visitor_data['user_encoding'] = isset($_SERVER['HTTP_ACCEPT_ENCODING'])? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';
              $visitor_data['user_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

              $visitor_data['id'] = md5(
                  $visitor_data['user_agent'] .
                  $visitor_data['user_accept'] .
                  $visitor_data['user_language'] .
                  $visitor_data['user_encoding'] .
                  $visitor_data['user_charset'] .
                  $visitor_data['remote_ip']
              );
        return $visitor_data;
    }
    public function checkUser(){
        $visitor_data = $this->getVisitorData();

        /* skip test if agent_bypass_string is set and is in the user_agent string */
        if ($this->config['agent_bypass_string'] && stristr($visitor_data['user_agent'], $this->config['agent_bypass_string']) )  {
            return '';
        }
        /* skip test if request is from our own server (second test is for IIS) */
        if ($visitor_data['remote_ip'] == $_SERVER['SERVER_ADDR'] || $visitor_data['remote_ip'] == $_SERVER['LOCAL_ADDR']) {
            if ($this->config['debug_level'] > 3) {
                $this->my_debug("Own Server");
            } else {
                return '';
           }
        }

        $dataFile = $this->config['data_path'] . $this->config['id_prefix'] . $visitor_data['id'];

        if (file_exists($dataFile)) {
            $this->config['has_file'] = true;
            $data = $this->readData($dataFile);
            /*  time since their last visit (in 1/100 of a second) */
            $st = isset($data['start_time'])? $data['start_time'] : 0;
            $hammer_rate = $this->config['now'] - $st + 1;
            if (!$data['start_time']) {
                if ($this->config['debug_level'] > 2) {
                    $this->my_debug('Start Time is empty: ' . file_get_contents($dataFile));
                }
            }
            /* $hammer_rate = $this->config['now'] - @$data['start_time'] + 1; */
        } else {
            $this->config['has_file'] = false;
            /* first offense, give them a pass and just write the data (later) */
            $hammer_rate = $this->config['hammer_time'] + 1;
            $data['start_time'] = $this->config['now'];
            $data['hammer_count'] = 1;
            if ($this->config['debug_level'] > 3) {
                $this->my_debug("No Data file (normal on first offense)");
            }
        }

        if ($this->config['debug_level'] > 1) {
            $this->my_debug('NOW: ' . $this->config['now']);
            $this->my_debug('Start_time: ' . $data['start_time']);
            $this->my_debug('Hammer_rate: ' . $hammer_rate);
            $this->my_debug('HAMMER COUNT: ' . $data['hammer_count']);
            $this->my_debug('IP: ' . $visitor_data['remote_ip']);

        }

        /* Give them a partial pass if their data is older than the kill_hours but
           hasn't been cleared by garbage collection */
        if ($this->config['has_file'] && ($hammer_rate > ($this->config['kill_hours']*60*60*100)) ) {
            if ($this->config['debug_level']) {
                $this->my_debug('Past kill_hours since last hit');
            }
            $data['start_time'] = $this->config['now'] - 1;
            $hammer_rate = $this->config['hammer_time'];
            unset($data['kill_me']);

            /* but as repeat-offenders they're one hammer away from getting whacked */
            $data['hammer_count'] = $this->config['trigger_level']-1;
        }

        /* if kill_level is set and they qualify, just die */
        if ($this->config['total_kill'] and isset($data['kill_me'])) {
            if ($this->config['debug_level']) {
                $this->my_debug('Total Kill for ip: ' . $visitor_data['remote_ip']);
            }
            die();
        }

        /* delete expired visitor data files, if it's time to do so */
        $this->collectGarbage(MODX_CORE_PATH . 'components/antihammerx/data/counter', $this->config['hits_til_gc']);



        if ($hammer_rate < $this->config['hammer_time']) {
            if ($this->config['debug_level']) {
                $this->my_debug('hammer_rate is unacceptable');
            }

            if (! isset($data['hammer_count']) && $this->config['debug_level']) {
                $this->my_debug('data[hammer_count] not set');
            }
            /* increment their hammer count */
            $data['hammer_count'] = isset($data['hammer_count'])? $data['hammer_count'] + 1 : 1;

            $data['start_time'] = $this->config['now'];

            $delay = 1;
            /* Delay goes here */

            //die ('Hammered');
            if ($this->config['total_kill'] && $data['hammer_count'] > $this->config['total_kill_count']) {
                $data['kill_me'] = true;
            }
        } else {
            $data['start_time'] = $this->config['now'];
        }


        /* write client session data.. */
        $this->writeData($dataFile, $data);
        if ($this->config['debug_level']) {
            $this->my_debug("\n");
        }
    }

    protected function forward($options) {
        if (!is_array($options)) $options = array();
        $options= array_merge(
            array(
                'response_code' => $this->getOption('delay_page_header' ,$options ,'HTTP/1.1 403 Unauthorized')
                ,'error_type' => '403'
                ,'error_header' => $this->getOption('delay_page_header', $options,'HTTP/1.1 403 Unauthorized')
                ,'error_pagetitle' => $this->getOption('delay_page_pagetitle',$options, 'Error 403: Unauthorized')
                ,'error_message' => $this->getOption('delay_page_message', $options,'<h1>Unauthorized</h1><p>You have violated the site policy by making too many page requests in too short a time. You are not authorized to view the requested content.</p>')
            ),
            $options
        );
        $this->sendForward($this->getOption('delay_page_id', $options, '401'), $options);
    }


    protected function collectGarbage($countFile, $hits_til_gc) {
        if ($hits_til_gc === 0) {
            return '';
        }
       
        if ($this->updateCount($countFile) >= $this->config['hits_til_gc']) {
            if ($this->config['debug_level']) {
                $this->my_debug('Collecting Garbage');
            }
            $file_list = array();
            if ($dir = @opendir(dirname($countFile))) {
                while (false != ($file = readdir($dir))) {
                    if ((ord($file) != 46) and strpos($file, $hits_til_gc) === 0) {
                        $file_path = dirname($countFile) . '/' . $file;

                        /* delete file if it's older than cleanup_age */
                        if (filemtime($file_path) < (time() - $this->config['cleanup_age'] * 60 * 60)) {
                            if (!unlink($file_path)) {
                                $msg = 'AntiHammerX -- could not delete '. $file_path;
                $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
                            }
                        }
                    }
                }
            }
            $this->updateCount($countFile, true);
        }
    }


    protected function updateCount($countFile, $reset=false) {

        $count = false;

        if (!file_exists($countFile) or $reset) {
            $fp = fopen($countFile, 'wb');
            fwrite ($fp, '0');
            fclose ($fp);
        }

        if (file_exists($countFile)) {
            if ($this->config['debug_level'] > 1) {
                $this->my_debug('Updating Count file');
            }
            /* read old score */
            $count = trim(file_get_contents($countFile));

            if (!$count) { $count = 0; }
            $count++;

            /* write new score */
            if (is_writable($countFile)) {
                $fp = fopen($countFile, 'wb+');
                $lock = flock($fp, LOCK_EX);
                    if ($lock) {
                        fwrite($fp, $count);
                        flock ($fp, LOCK_UN);
                    }
                    fclose($fp);
                    clearstatcache();
            } else {
                $msg = 'AntiHammerX -- '. $countFile . ' is not writable';
                $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
            }
        }
        return $count;
    }


    protected function readData($dataFile) {
        if (file_exists($dataFile)) {
            $fp = fopen($dataFile, 'rb');
            if (!$fp) {
                if ($this->config['debug_level'] > 3) {
                    die ('fopen failed');
                }
            }
            $file_contents = @fread($fp, filesize($dataFile));
            fclose($fp);
        } else {
            return false;
        }
        if ($this->config['debug_level'] > 3) {
            $this->my_debug($file_contents);
        }
        $file_contents = $this->modx->fromJSON($file_contents);
        if (is_array($file_contents)) {
            return $file_contents;
        } else {
            if ($this->config['debug_level'] > 3) {
                $this->my_debug('fromJSON failed');
            }
            return false;
        }
    }


    protected function writeData($dataFile, $fields) {
        if ($this->config['my_debug'] > 3) {
            $this->my_debug('<pre>' . print_r($fields,true) . '</pre>');
        }

        $data = $this->modx->toJSON($fields);
        if (empty($data)) {
            return false;
        }
        $fp = @fopen($dataFile, 'wb');
        if ($fp) {
            $lock = flock($fp, LOCK_EX);
            if ($lock) {
                fwrite($fp, $data);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
            clearstatcache();
            return true;
        } else {
            $msg = 'AntiHammerX -- could not open ' . $dataFile;
            $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

    }
    /* timeout protected function to get host name */
    protected function getHost($ip){
        $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
        $host = dns_get_record($ptr,DNS_PTR);
        if ($host == null) return $ip;
        else return $host[0]['target'];
    }

    public function my_debug($message, $clear = false) {
        global $modx;

        $chunk = $modx->getObject('modChunk', array('name'=>'debug'));
        if (! $chunk) {
            $chunk = $modx->newObject('modChunk', array('name'=>'debug'));
            $chunk->save();
            $chunk = $modx->getObject('modChunk', array('name'=>'debug'));
        }
        if ($clear) {
            $content = '';
        } else {
            $content = $chunk->getContent();
        }
        $content .= $message . "\n";
        $chunk->setContent($content);
        $chunk->save();
    }

} /* end class */



?>
