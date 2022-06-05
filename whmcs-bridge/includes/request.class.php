<?php
class bridgeHttpRequest  {
    public $_fp;        // HTTP socket
    public $_url;        // full URL
    public $_host;        // HTTP host
    public $_protocol;    // protocol (HTTP/HTTPS)
    public $_uri;        // request URI
    public $_port;        // port
    public $_path;
    public $error=false;
    public $errno=false;
    public $post=array();	//post variables, defaults to $_POST
    public $redirect=false;
    public $forceWithRedirect=array();
    public $errors=array();
    public $countRedirects=0;
    public $sid;
    public $httpCode;
    public $repost=false;
    public $type; //content-type
    public $follow = true; //whether to follow redirect links or not
    public $noErrors = false; //whether to trigger an error in case of a curl error
    public $errorMessage;
    public $httpHeaders = array('Expect:','bridgeon: 1'); //avoid 417 errors
    public $debugFunction;
    public $time;
    public $cookieArray = array();
    public $cookieCach='';
    public $debugPrefix = '';

    // constructor
    public function __construct($url = "", $sid = "", $repost = false) {
        if (!$url) return;
        $this->sid=$sid;
        $this->_url = $url;
        $this->_scan_url();
        $this->post=$_POST;
        $this->repost=$repost;
        $this->debugPrefix = "[connect ".uniqid()."] ";
    }

    private function time($action) {
        $t=function_exists('microtime') ? 'microtime' :'time';
        if ($action=='reset') $this->time=$t(true);
        elseif ($action=='delta') return round(($t(true)-$this->time)*100,0);
    }

    private function forceWithRedirectToString($url) {
        $s='';
        if (count($this->forceWithRedirect)) {
            foreach ($this->forceWithRedirect as $n => $v) {
                if (stristr($url,$n.'=')) continue;
                if ($s) $s.='&';
                $s.=$n.'='.$v;
            }
        }
        return $s;
    }

    private function debug($type=0,$msg='',$filename="",$linenum=0) {
        if ($f=$this->debugFunction) $f($type,$this->debugPrefix.$msg,$filename,$linenum);
    }

    private function os() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') return 'WINDOWS';
        else return 'LINUX';
    }

    // scan url
    private function _scan_url() {
        $req = $this->_url;

        $pos = strpos($req, '://');
        $this->_protocol = strtolower(substr($req, 0, $pos));

        $req = substr($req, $pos+3);
        $pos = strpos($req, '/');
        if($pos === false)
            $pos = strlen($req);
        $host = substr($req, 0, $pos);

        if(strpos($host, ':') !== false)  {
            list($this->_host, $this->_port) = explode(':', $host);
        } else {
            $this->_host = $host;
            $this->_port = ($this->_protocol == 'https') ? 443 : 80;
        }

        $this->_uri = substr($req, $pos);
        if($this->_uri == '') {
            $this->_uri = '/';
        } else {
            $params=substr(strrchr($this->_uri,'/'),1);
            $this->_path=str_replace($params,'',$this->_uri);
        }
    }

    //check if server is live
    public function live() {
        //return true;
        if (ip2long($this->_host)) return true; //in case using an IP instead of a host name
        $url=$this->_host;
        if (gethostbyname($url) == $url)
            return false;
        else
            return true;
    }

    //get mime type of uploaded file
    public function mimeType($file) {
        $mime='';
        if (function_exists('finfo_open')) {
            if ($finfo = finfo_open(FILEINFO_MIME_TYPE)) {
                $mime=finfo_file($finfo, $file);
                finfo_close($finfo);
            }
        }
        if ($mime) return $mime;
        else return '';
    }

    //check if wp HTTP API is available
    public function curlInstalled() {
        if (!function_exists('wp_remote_request')) return false;
        else return true;
    }

    //check destination is reachable
    public function checkConnection() {
        $this->post['checkconnection']=1;
        $output=$this->connect($this->_protocol.'://'.$this->_host.$this->_uri);
        if ($output=='zingiri' || $output=='connected') return true;
        else return false;
    }

    //error logging
    public function error($msg) {
        $this->errorMsg=$msg;
        $this->error=true;
        //if (!$this->noErrors) trigger_error($msg,E_USER_WARNING);
        $this->debug(E_USER_WARNING,$msg);
    }

    //notification logging
    public function notify($msg) {
        $this->errorMsg=$msg;
        $this->error=true;
        if (!$this->noErrors) trigger_error($msg,E_USER_NOTICE);
        $this->debug(E_USER_NOTICE,$msg);
    }

    // download URL to string
    public function DownloadToString($withHeaders=true,$withCookies=false) {
        if ($this->_port == 80 || $this->_port == 443)
            $html = $this->connect($this->_protocol.'://'.$this->_host.$this->_uri,$withHeaders,$withCookies);
        else
            $html = $this->connect($this->_protocol.'://'.$this->_host.':'.$this->_port.$this->_uri,$withHeaders,$withCookies);

        return $html;
    }

    public function makeQueryString($params, $prefix = '', $removeFinalAmp = true) {
        $queryString = '';
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $correctKey = $prefix;
                if ('' === $prefix) {
                    $correctKey .= $key;
                } else {
                    $correctKey .= "[" . $key . "]";
                }
                if (!is_array($value) && !is_object($value)) {
                    $queryString .= urlencode($correctKey) . "="
                        . urlencode($value) . "&";
                } else {
                    $queryString .= $this->makeQueryString($value, $correctKey, false);
                }
            }
        }
        if ($removeFinalAmp === true) {
            return substr($queryString, 0, strlen($queryString) - 1);
        } else {
            return $queryString;
        }
    }

    private function generatePostArray() {
        $apost = [];
        if (count($this->post) > 0) {
            $post = "";
            $apost = array();
            $this->post = stripslashes_deep($this->post);
            foreach ($this->post as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if (is_array($v2)) {
                            foreach ($v2 as $k3 => $v3) {
                                if (is_array($v3)) {
                                    foreach ($v3 as $k4 => $v4) {
                                        $apost[$k . '[' . $k2 . ']' . '[' . $k3 . '][' . $k4 . ']'] = ($v4);
                                    }
                                } else {
                                    $apost[$k . '[' . $k2 . ']' . '[' . $k3 . ']'] = ($v3);
                                }
                            }
                        } else {
                            $apost[$k . '[' . $k2 . ']'] = ($v2);
                        }
                    }

                } else {
                    $apost[$k] = ($v);
                }
            }
        }
        return $apost;
    }

    public function connect($url, $withHeaders=true, $withCookies=false) {
        $this->time('reset');
        global $wordpressPageName;

        $newfiles = array();

        $url = str_replace('?m=DNSManagerII', '?m=DNSManager2', $url);

        // 2co/SolusVM/Quantumvault callback requires get params
        if ((stristr($url, 'solusvm') !== false || stristr($url, 'quantumvault') !== false || stristr($url, 'twocheckout')) && count($_GET) > 0) {
            $ignore = array('ccce');
            $get_params = array();
            foreach ($_GET as $k => $v) {
                if (!in_array($k, $ignore)) {
                    $get_params[$k] = $v;
                }
            }
            if (count($get_params) > 0) {
                if (stristr($url, '?') !== false) {
                    $url .= '&' . http_build_query($get_params);
                } else {
                    $url .= '?' . http_build_query($get_params);
                }
            }
        }

        $substr = stristr($url, '?', true);
        if (stristr($url, '/store/') !== false && $substr !== false && substr($substr, -1) == "/")
            $url = str_replace($substr, substr($substr, 0, -1), $url);

        $this->debug(0, 'Not cached, processing file - '.$url);

        if (function_exists('cc_whmcsbridge_sso_session'))
            cc_whmcsbridge_sso_session();
        if (session_status() == PHP_SESSION_NONE && !headers_sent())
            session_start();

        $http_args = array();

        $this->debug(0, 'HTTP Call: ' . $url . (is_array($this->post) ? ' with ' . json_encode($this->post) : ''));

        if (get_option("cc_whmcs_bridge_affiliate_id") && is_numeric(get_option("cc_whmcs_bridge_affiliate_id")) && get_option("cc_whmcs_bridge_affiliate_id") > 0) {
            $this->httpHeaders['bridgeaffiliate'] = get_option("cc_whmcs_bridge_affiliate_id");
        }
        $this->httpHeaders['bridgeon'] = 1;

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $this->httpHeaders['X-Requested-With'] = 'XMLHttpRequest';
        }

        $http_args['headers'] = $this->httpHeaders;
        $http_args['timeout'] = 60;

        if ($this->_protocol == "https") {
            $http_args['sslverify'] = false;
        }

        $cookies = [];

        if (isset($_SESSION[$this->sid]['cookieArr']) && count($_SESSION[$this->sid]['cookieArr']) > 0) {
            $cookies = $_SESSION[$this->sid]['cookieArr'];
        }

        if (!empty($cookies)) {
            //$this->debug(0, 'Cookie before:' . json_encode($cookies));
            $http_args['cookies'] = $cookies;
        }

        $_SESSION['cookieCach'] = $cookies;

        if (count($_FILES) > 0) {
            foreach ($_FILES as $name => $file) {
                if (is_array($file['tmp_name']) && count($file['tmp_name']) > 0) {
                    $c = count($file['tmp_name']);
                    for ($i = 0; $i < $c; $i++) {
                        if ($file['tmp_name'][$i]) {
                            $newfile = BLOGUPLOADDIR. $file['name'][$i];
                            copy($file['tmp_name'][$i], $newfile);
                            if (!file_exists($newfile)) {
                                $this->debug(0, 'Cant copy '.$file['tmp_name'][$i].' to '.$newfile);
                            } else {
                                $newfiles[] = [
                                    'file' => $newfile,
                                    'name' => $name.'['.$i.']'
                                ];
                            }
                        }
                    }
                } elseif ($file['tmp_name']) {
                    $newfile = BLOGUPLOADDIR. $file['name'];
                    copy($file['tmp_name'], $newfile);
                    if (!file_exists($newfile)) {
                        $this->debug(0, 'Cant copy '.$file['tmp_name'][$i].' to '.$newfile);
                    } else {
                        $newfiles[] = [
                            'file' => $newfile,
                            'name' => $name
                        ];
                    }
                }
            }
            $this->debug(0, 'There are files:  '.json_encode($newfiles));
        }

        $rawPost = file_get_contents('php://input');

        if (!empty($rawPost))
            $this->debug(0, "Raw data: ".$rawPost);

        $apost = $this->generatePostArray();

        if (!empty($newfiles)) {
            $http_args['method'] = 'POST';
            $boundary = substr(md5(time()), -24);
            $http_args['headers']['content-type'] = 'multipart/form-data; boundary='.$boundary;
            $http_args['body'] = '';

            if (!empty($apost)) {
                foreach ($apost as $k => $v) {
                    $http_args['body'] .= '--'.$boundary;
                    $http_args['body'] .= "\r\n";
                    $http_args['body'] .= 'content-disposition: form-data; name="' . $k .
                        '"' . "\r\n\r\n";
                    $http_args['body'] .= $v;
                    $http_args['body'] .= "\r\n";
                }
            }

            foreach ($newfiles as $file) {
                $http_args['body'] .= '--' . $boundary;
                $http_args['body'] .= "\r\n";
                $http_args['body'] .= 'content-disposition: form-data; name="' . $file['name'] .
                    '"; filename="' . basename( $file['file'] ) . '"' . "\r\n";
                $http_args['body'] .= 'content-type: '. $this->mimeType($file['file']) . "\r\n";
                $http_args['body'] .= "\r\n";
                $http_args['body'] .= file_get_contents( $file['file'] );
                $http_args['body'] .= "\r\n";
            }
            $http_args['body'] .= '--' . $boundary . '--';

            $this->debug(0, 'Posting with file attachment '.json_encode($newfiles));

        } else if (!empty($apost)) {
            $http_args['method'] = 'POST';

            if (stristr($url, 'clientarea.php?action=details') !== false && !isset($apost['save']) && isset($apost['firstname'], $apost['lastname'], $apost['companyname'], $apost['address1'])) {
                $apost['save'] = 'Save Changes';
                $this->debug(0, 'Safari patch for updating personal details');
            }

            $pfields = $this->makeQueryString($apost);
            $this->debug(0, 'Posting as:  ' . json_encode($pfields));

            $http_args['body'] = $pfields;
        } else if (!empty($rawPost)) {
            $http_args['method'] = 'POST';

            if (!in_array(substr($rawPost, 0, 1),  ['[', '{', '"'])) {
                parse_str($rawPost, $rawPost);
            }

            $http_args['body'] = $rawPost;

            $this->debug(0, "Posting RAW: ".$rawPost);
        } else if (strtolower($_SERVER['REQUEST_METHOD']) == "post" && strstr($url, 'viewinvoice.php') === false) {
            $http_args['method'] = 'POST';

            $this->debug(0, "HTTP Method POST 1");
        }

        // Fix legacy headers
        foreach ($http_args['headers'] as $k => $v) {
            if (is_numeric($k)) {
                unset($http_args['headers'][$k]);
                $v = explode(':', $v);
                if ($v > 1) {
                    $key = $v[0];
                    unset($v[0]);
                    $value = implode(':', $v);
                    $http_args['headers'][$key] = $value;
                }
            }
        }

        if (empty($http_args['method']))
            $http_args['method'] = 'GET';

        $this->debug(0, $http_args['method']." to {$url} with headers: ".json_encode($http_args['headers']));

        $http_args['redirection'] = 0;
        $data = wp_remote_request($url, $http_args);

        if (is_wp_error($data)) {
            $this->errno = $data->get_error_code();
            $this->error = $data->get_error_message($this->errno);
            $error_msg = 'An error has occurred: ' . $this->error;
            $this->error($this->errno . '/' . $error_msg.' ('.$url.')');
            $this->debug(0, 'HTTP Error:  '.$this->errno . '/' . $error_msg.' ('.$url.')');
            return '<body>'.$error_msg.'<br>Please try again later.</body>';
        }

        if (!empty($data)) {
            $headers = $data['headers']->getAll();

            $this->debug(0, 'Headers: '.json_encode($headers));

            $cookies = wp_remote_retrieve_cookies($data);
            if (!empty($cookies) && !is_wp_error($cookies)) {
                $_SESSION[$this->sid]['cookieArr'] = $cookies;
            } else {
                $cookies = $_SESSION['cookieCach'];
            }

            $body = $data['body'];
        } else {
            $headers = array();
            $cookies = [];
            $body = "";

            $this->error("An undefined error occurred");

            return '<body>An undefined error occurred</body>';
        }


        $foundSessId = false;
        foreach ($cookies as $ck) {
            if ($ck->name == 'PHPSESSID') {
                $foundSessId = true;
                $_SESSION[$this->sid]['sessid'] = $ck->value;
            }
        }

        if (!empty($cookies)) {
            //$this->debug(0, 'Cookie after:' . json_encode($cookies));

            if (!isset($_SESSION[$this->sid]))
                $_SESSION[$this->sid] = array();

            if (isset($_SESSION[$this->sid]['sessid'])) {
                if (!$foundSessId)
                    $cookies[] = new WP_Http_Cookie(['name' => 'PHPSESSID', 'value' => $_SESSION[$this->sid]['sessid']]);
            }

            $_SESSION[$this->sid]['cookies'] = $cookies;
        }

        //if (is_array($cookies))
            //$this->debug(0, 'Cookie after:' . json_encode($cookies));

        // remove temporary upload files
        if (count($newfiles) > 0) {
            foreach ($newfiles as $nF) {
                @unlink($nF);
            }
        }

        $this->headers = $headers;
        $this->data = $data['raw'];
        $this->cookies = $cookies;
        $this->body = $body;

        if ($headers['content-type']) {
            $this->type = $headers['content-type'];
        }

        $this->debug(0, 'Call process completed in ' . $this->time('delta') . ' microseconds');

        if ($this->follow && isset ($headers['location']) && $headers['location']) {
            $this->debug(0, 'XX: redirect to:'.json_encode($headers));
            $this->debug(0, 'XX: protocol='.$this->_protocol);
            $this->debug(0, 'XX: path='.$this->_path);

            $redir = $headers['location'];

            $main_whmcs_url = parse_url(cc_whmcs_bridge_url());
            $this->debug(0, 'S0: '.json_encode($main_whmcs_url));

            if (strstr($this->_path, '/store/order') === false && strstr($this->_path, '/password/reset/change')) {
                if ($this->os() == 'WINDOWS') {
                    if (strpos($redir, $this->_protocol . '://' . $this->_host . $this->_path) === 0) {
                        //do nothing
                    } elseif (strstr($this->_protocol . '://' . $this->_host . $redir, $this->_protocol . '://' . $this->_host . $this->_path)) {
                        $new_redir = $this->_protocol . '://' . $this->_host . $this->_path;
                        if (strstr($new_redir, $redir) === false) {
                            $new_redir .= $redir;
                        }
                        $redir = $new_redir;
                    } elseif (!strstr($redir, $this->_host)) {
                        $redir = $this->_protocol . '://' . $this->_host . $this->_path . $redir;
                    }
                } else {
                    if (strpos($redir, $this->_protocol . '://' . $this->_host . $this->_path) === 0) {
                        //do nothing
                    } elseif (strstr($this->_protocol . '://' . $this->_host . $redir, $this->_protocol . '://' . $this->_host . $this->_path)) {
                        $redir = $this->_protocol . '://' . $this->_host . $redir;
                    } elseif (((strpos($redir, 'http://') === 0) || (strpos($redir, 'https://') === 0)) && !strstr($redir, $this->_host)) {
                        $this->redirect = true;
                        return $redir;
                    } elseif (!strstr($redir, $this->_host)) {
                        $redir = $this->_protocol . '://' . $this->_host . $this->_path . $redir;
                    }
                }
            } else {
                if (substr($redir, 0, 1) != '/' && stristr($redir, ':208') === false
                    && substr($redir, 0, 4) != 'http')
                    $redir = '/' . $redir;

                $redir_parts = parse_url($redir);
                if (!empty($redir_parts['path'])) {
                    $redir_parts = pathinfo($redir_parts['path']);
                    if (!empty($redir_parts['dirname']))
                        $redir_parts = $redir_parts['dirname'];
                    else
                        $redir_parts = $redir;
                } else
                    $redir_parts = $redir;

                $this->debug(0, "Redir: ".$redir);

                if ((stristr($this->_protocol . '://' . $this->_host . $this->_path, $redir) === false
                        || (
                            stristr($redir, $main_whmcs_url['host']) === false &&
                            stristr($redir, $main_whmcs_url['path']) === false
                        )) && strstr($redir, '://') !== false
                ) {
                    // do nothing
                    $bounce = true;
                    $this->debug(0, 'S2: ' . $redir);
                } else if (stristr($redir, ':208') === false
                    && (!empty($main_whmcs_url['path']) && $main_whmcs_url['path'] != $redir_parts)
                    && stristr($redir, 'password/reset') === false
                    && strstr($redir, 'account/') === false
                    && strstr($redir, 'user/') === false
                    && strstr($redir, 'login/challenge') === false
                    && strstr($redir, 'store/') === false
                    && strstr($redir, 'clientarea.php') === false
                    && strstr($redir, 'rp=/login') === false
                    && stristr($redir, '://') === false
                ) {
                    $redir = $this->_host . $this->_path . $redir;
                    $this->debug(0, 'S3: '.$redir);
                } else if (stristr($redir, ':208') !== false) {
                    $bounce = true;
                    $this->debug(0, 'S4: ' . $redir);
                } else if ($redir == '/clientarea.php') {
                    if (empty($rawPost))
                        $bounce = true;
                    if (stristr($this->_path, '/user/accounts') !== false)
                        $redir = $this->_protocol . '://' .$this->_host .'/'. $redir;
                    else
                        $redir = $this->_protocol . '://' .$this->_host .$this->_path. $redir;
                    $this->debug(0, 'S4.1: '.$redir);
                } else {
                    $redir = $this->_host . $redir;
                    $this->debug(0, 'S5: '.$redir);
                }

                if (empty($bounce) && substr($redir, -15) != '/clientarea.php') {
                    $redir = $this->_protocol . '://' . str_replace('//', '/', $redir);
                    $this->debug(0, 'S6: '.$redir);
                }
            }
            $fwd = $this->forceWithRedirectToString($redir);
            if ($fwd) {
                if (strstr($redir, '&')) $redir .= '&';
                elseif (strstr($redir, '?')) $redir .= '&';
                else $redir .= '?';
                $redir .= $fwd;
            }
            $this->debug(0, '[3] Redirect to: ' . $redir);

            if (strstr($redir, 'viewinvoice.php') ||
                (strstr($this->_path, '/store/order') && strstr($redir, 'cart.php')) ||
                (strstr($redir, 'action=details&success')) ||
                !empty($rawPost)
            ) {
                if (empty($bounce)) {
                    $opt = 0;
                    if (strstr($redir, 'action=details&success') || (!empty($rawPost) && !strstr($redir, 'clientarea.php'))) {
                        $newRedir = cc_whmcs_bridge_parse_url($redir, true);
                        $opt = 1;
                    } else {
                        $newRedir = cc_whmcs_bridge_parse_url($redir);
                        $opt = 2;
                    }
                    if (strstr($this->_path, '/store/order') && strstr($redir, 'cart.php')) {
                        $newRedir = str_replace('/store/order', '', $newRedir);
                        $newRedir = cc_whmcs_bridge_parse_url($newRedir);
                        $opt = 3;
                    }

                    $substr = stristr($newRedir, '?', true);
                    if ($substr !== false && substr($substr, -1) != '/') {
                        $newRedir = str_replace($substr, $substr.'/', $newRedir);
                    }

                    $this->debug(0, '[XX - '.$opt.'] New Redirect: ' . $newRedir . ' (' . $redir . ')');
                } else {
                    $newRedir = $redir;
                    $redir = false;
                }

                if ($redir != $newRedir || stristr($redir, '../viewinvoice')) {
                    $newRedir = urldecode($newRedir);
                    $this->debug(0, 'Header relocation '.$newRedir);
                    header('Location:' . $newRedir);
                    die();
                }
            } else if (substr_count($redir, "knowledgebase") > 1 && isset($headers, $headers['location'])) {
                $newRedir = $headers['location'];
                $newRedir = cc_whmcs_bridge_parse_url($newRedir);

                $this->debug(0, '[XX] New Redirect: ' . $newRedir . ' (' . $redir . ')');
                header('Location:' . $newRedir);
                die();
            } else if (strstr($redir, 'cart.php?a=add&domain=register') || strstr($redir, 'cart.php?a=confproduct&i=')
                || strstr($redir, 'cart.php?a=view')
                || strstr($redir, 'cart.php?a=complete')
            ) {
                $newRedir = cc_whmcs_bridge_parse_url($redir);
                header('location: '.$newRedir);
                die();
            } else if (strstr($redir, 'cpsess') || strstr($redir, 'service-name') || stristr($redir, $main_whmcs_url['host']) === false) {
                header('location: '.$redir);
                die();
            } else if (strstr($redir, 'custom_page=reissue') ||
                strstr($redir, 'custom_page=manage_validation') || (strstr($url, 'login') !== false && !isset($this->post['bg']))
            ) {
                $newRedir =  cc_whmcs_bridge_parse_url($redir);
                if ($wordpressPageName) $p = $wordpressPageName;
                else $p = '/';

                $this->debug(0, 'Processing redirect...');

                if (strstr($url, 'login') !== false && class_exists('wpusers') && !empty($this->post['username'])) {
                    $this->debug(0, 'Logging in to WordPress with ' . $this->post['username'] . '/' . $this->post['password']);
                    $wpusers = new wpusers();
                    $wpusers->loginWpUser($this->post['username'], $this->post['password']);
                }

                cc_whmcs_bridge_home($home,$pid,false);

                if (get_option('cc_whmcs_bridge_permalinks') && function_exists('cc_whmcs_bridge_parser_with_permalinks')) {
                    if (substr($home, -1) == '/')
                        $link = substr($home, 0, -1);
                    else
                        $link = $home;
                    $f[] = '/.*\/([a-zA-Z\_]*?).php.(.*?)/';
                    $r[] = $link . '/$1?$2';
                    $f[] = "/([a-zA-Z0-9\_]*?).php.(.*?)/";
                    $r[] = $link . '/$1?$2';
                } else {
                    $f[] = '/.*\/([a-zA-Z\_]*?).php.(.*?)/';
                    $r[] = $home . '?ccce=$1&$2';
                    $f[] = "/([a-zA-Z0-9\_]*?).php.(.*?)/";
                    $r[] = $home . '?ccce=$1&$2';
                }

                $this->debug(0, 'Location [1]: '.$newRedir);

                $newRedir = preg_replace($f, $r, $newRedir, -1, $count);

                $this->debug(0, 'Location [P]: '.$newRedir);

                header('Location:' . $newRedir);

                die();
            }
            if (!$this->repost) $this->post = array();
            $this->countRedirects++;
            if ($this->countRedirects < 10) {
                return $this->connect($redir, $withHeaders, $withCookies);
            } else {
                $error_msg = 'ERROR: Too many redirects ' . $url . ' > ' . $headers['location'];
                $this->error($error_msg, E_USER_ERROR);
                return '<body>'.$error_msg.'</body>';
            }
        }

        return $body;
    }
}

if (!class_exists('zHttpRequest')) {
    class zHttpRequest extends bridgeHttpRequest {
    }
}
