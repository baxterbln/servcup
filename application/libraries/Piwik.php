<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Piwik Class
 *
 * Library for retrieving stats from Piwik Open Source Analytics API
 * with geoip capabilities using the free MaxMind GeoLiteCity database
 *
 * @package       CodeIgniter
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Neil Baker neil@cconnect.es
 */

class Piwik
{
    private $_ci;
    private $geoip_on = FALSE;
    private $piwik_url = '';
    private $site_id = '';
    private $token = '';
    private $gi;

    function __construct()
    {
        $this->_ci =& get_instance();
        //$this->_ci->load->config('piwik');

        $this->piwik_url = get_setting('piwik_url');;
        //$this->site_id = $this->_ci->config->item('site_id');
        $this->token = get_setting('piwik_token');
        $this->geoip_on = $this->_ci->config->item('geoip_on');

        if($this->geoip_on)
        {
            $this->_ci->load->helper('geoip');
            $this->_ci->load->helper('geoipcity');
            $this->_ci->load->helper('geoipregionvars');
        }
    }

    /**
     * actions
     * Get actions (hits) for the specific time period
     *
     * @access  public
     * @param   string  $period   Time interval ('day', 'month', or 'year')
     * @param   int     $cnt      Gets the number of $period from the current period to what $cnt is set to (i.e. last 10 days by default)
     * @return  array
     */
    public function actions($period = 'day', $cnt = 10)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=VisitsSummary.getActions&idSite='.$this->site_id.'&period='.$period.'&date=last'.$cnt.'&format=JSON&token_auth='.$this->token;
        return $this->_get_decoded($url);
    }


    public function user_exist($username)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=UsersManager.userExists&userLogin='.$username.'&format=JSON&token_auth='.$this->token;
        $user = $this->_get_decoded($url);
        if(isset($user['value']) && $user['value'] == false) {
            return false;
        }else{
            return true;
        }
    }


    public function user_add($username, $password)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=UsersManager.addUser&userLogin='.$username.'&password='.$password.'&email=no-replay@localhost.de&format=JSON&token_auth='.$this->token;
        $addUser = $this->_get_decoded($url);
        if(isset($addUser['result']) && $addUser['result'] == 'success') {
            return true;
        }
        else{
            return false;
        }
    }

    public function add_site($domain)
    {
        $domainId = $this->check_siteExist($domain);
        if ($domainId != "0") {
            return $domainId;
        }
        else {
            $url = $this->piwik_url.'/index.php?module=API&method=SitesManager.addSite&token_auth='.$this->token.'&format=JSON&siteName='.$domain.'&urls='.$domain;
            $addDomain = $this->_get_decoded($url);
            if(isset($addDomain['value'])) {
                return $addDomain['value'];
            }
        }
    }

    public function delete_site($domain)
    {
        $domainId = $this->check_siteExist($domain);
        if ($domainId != "0") {
            $url = $this->piwik_url.'/index.php?module=API&method=SitesManager.deleteSite&token_auth='.$this->token.'&format=JSON&idSite='.$domainId;
            $deleteDomain = $this->_get_decoded($url);
        }
    }

    private function check_siteExist($domain)
	{
		$url = $this->piwik_url.'/index.php?module=API&method=SitesManager.getSitesIdFromSiteUrl&token_auth='.$this->token.'&format=JSON&url='.$domain;
        $checkSite = $this->_get_decoded($url);
        if(isset($checkSite[0]['idsite'])) {
            return $checkSite[0]['idsite'];
        }else{
            return "0";
        }
	}

    public function setSiteAccess($site, $username)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=UsersManager.setUserAccess&userLogin='.$username.'&access=view&idSites='.$site.'&token_auth='.$this->token.'&format=JSON';
        $addAccess = $this->_get_decoded($url);
    }

    //UsersManager.setUserAccess (userLogin, access, idSites)

    /**
     * last_visits
     * Get information about last 10 visits (ip, time, country, pages, etc.)
     *
     * @access  public
     * @return  array
     */
    public function last_visits()
    {
        $url = $this->piwik_url.'/index.php?module=API&method=Live.getLastVisits&idSite='.$this->site_id.'&format=JSON&token_auth='.$this->token;
        return $this->_get_decoded($url);
    }

    /**
     * last_visits_parsed
     * Get information about last 10 visits (ip, time, country, pages, etc.) in a formatted array with GeoIP information if enabled
     *
     * @access  public
     * @return  array
     */
    public function last_visits_parsed()
    {
        $url = $this->piwik_url.'/index.php?module=API&method=Live.getLastVisits&idSite='.$this->site_id.'&format=JSON&token_auth='.$this->token;
        $visits = $this->_get_decoded($url);

        $data = array();
        ($this->geoip_on ? $this->_geoip_open() : 0);
        foreach($visits as $v)
        {
            // Get the last array element which has information of the last page the visitor accessed
            $cnt = count($v['actionDetails']) - 1;
            $page_link = $v['actionDetails'][$cnt]['pageUrl'];
            $cnt = count($v['actionDetailsTitle']) - 1;
            $page_title = "";
            if(array_key_exists($cnt, $v['actionDetailsTitle']))
            {
                $page_title = $v['actionDetailsTitle'][$cnt]['pageTitle'];
            }

            // Get just the image names (API returns path to icons in piwik install)
            $flag = explode('/', $v['countryFlag']);
            $flag_icon = end($flag);

            $os = explode('/', $v['operatingSystemIcon']);
            $os_icon = end($os);

            $browser = explode('/', $v['browserIcon']);
            $browser_icon = end($browser);

            // Get GeoIP information if enabled
            $city = "";
            $region = "";
            $country = "";
            if($this->geoip_on)
            {
                $geoip = $this->get_geoip($v['ip'], TRUE);
                if(!empty($geoip))
                {
                    $city = $geoip['city'];
                    $region = $geoip['region'];
                    $country = $geoip['country'];
                }

            }

            $data[] = array(
              'time' => date("M j, g:i a", $v['lastActionTimestamp']),
              'title' => $page_title,
              'link' => $page_link,
              'ip_address' => $v['ip'],
              'provider' => $v['provider'],
              'country' => $v['country'],
              'country_icon' => $flag_icon,
              'os' => $v['operatingSystem'],
              'os_icon' => $os_icon,
              'browser' => $v['browser'],
              'browser_icon' => $browser_icon,
              'geo_city' => $city,
              'geo_region' => $region,
              'geo_country' => $country
            );
        }
        ($this->geoip_on ? $this->_geoip_close() : 0);
        return $data;
    }

    /**
     * page_titles
     * Get page visit information for the specific time period
     *
     * @access  public
     * @param   string  $period   Time interval ('day', 'month', or 'year')
     * @param   int     $cnt      Gets the number of $period from the current period to what $cnt is set to (i.e. last 10 days by default)
     * @return  array
     */
    public function page_titles($period = 'day', $cnt = 10)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=Actions.getPageTitles&idSite='.$this->site_id.'&period='.$period.'&date=last'.$cnt.'&format=JSON&token_auth='.$this->token;
        return $this->_get_decoded($url);
    }

    /**
     * unique_visitors
     * Get unique visitors for the specific time period
     *
     * @access  public
     * @param   string  $period   Time interval ('day', 'month', or 'year')
     * @param   int     $cnt      Gets the number of $period from the current period to what $cnt is set to (i.e. last 10 days by default)
     * @return  array
     */
    public function unique_visitors($period = 'day', $cnt = 10)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=VisitsSummary.getUniqueVisitors&idSite='.$this->site_id.'&period='.$period.'&date=last'.$cnt.'&format=JSON&token_auth='.$this->token;
        return $this->_get_decoded($url);
    }

    /**
     * visits
     * Get all visits for the specific time period
     *
     * @access  public
     * @param   string  $period   Time interval ('day', 'month', or 'year')
     * @param   int     $cnt      Gets the number of $period from the current period to what $cnt is set to (i.e. last 10 days by default)
     * @return  array
     */
    public function visits($period = 'day', $cnt = 10)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=VisitsSummary.getVisits&idSite='.$this->site_id.'&period='.$period.'&date=last'.$cnt.'&format=JSON&token_auth='.$this->token;
        return $this->_get_decoded($url);
    }

    /**
     * websites
     * Get refering websites (traffic sources) for the specific time period
     *
     * @access  public
     * @param   string  $period   Time interval ('day', 'month', or 'year')
     * @param   int     $cnt      Gets the number of $period from the current period to what $cnt is set to (i.e. last 10 days by default)
     * @return  array
     */
    public function websites($period = 'day', $cnt = 10)
    {
        $url = $this->piwik_url.'/index.php?module=API&method=Referers.getWebsites&idSite='.$this->site_id.'&period='.$period.'&date=last'.$cnt.'&format=JSON&token_auth='.$this->token;
        return $this->_get_decoded($url);
    }

    /**
     * _get_decoded
     * Gets and returns json_decoded array from the URL passed
     *
     * @access  private
     * @param   string  $url   URL to Piwik API method returning JSON
     * @return  array
     */
    private function _get_decoded($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        return $data;
    }

    // ---- GeoIP functions ---------------------------------------------------------------------- //

    /**
     * get_geoip
     * Uses GeoLiteCity.dat and geoip helpers to get GeoIP information for the IP address passed
     *
     * @access  public
     * @param   string    $ip_address     IP Address
     * @param   boolean   $conn           TRUE or FALSE - whether a connection to GeoLiteCity is already open or not
     * @return  array
     */
    public function get_geoip($ip_address, $conn = FALSE)
    {
        if($this->geoip_on)
        {
            ($conn == FALSE ? $this->_geoip_open() : 0);
            $geoip = array();
            $record = geoip_record_by_addr($this->gi, $ip_address);
            if(!empty($record))
            {
                $geoip['city'] = $record->city;
                $geoip['region'] = $record->region;
                $geoip['country'] = $record->country_code3;
            }
            ($conn == FALSE ? $this->_geoip_close() : 0);
            return $geoip;
        }
        else
        {
            show_error('You must enable GeoIP in the piwik config file to use get_geoip.');
        }
    }

    /**
     * _geoip_open
     * Opens connection to GeoLiteCity.dat
     *
     * @access  private
     * @return  void
     */
    private function _geoip_open()
    {
        $this->gi = geoip_open(APPPATH.'helpers/geoip/GeoLiteCity.dat', GEOIP_STANDARD);
    }

    /**
     * _geoip_close
     * Closes connection to GeoLiteCity.dat
     *
     * @access  private
     * @return  void
     */
    private function _geoip_close()
    {
        geoip_close($this->gi);
    }

}
// END Piwik Class

/* End of file Piwik.php */
/* Location: ./application/libraries/Piwik.php */
