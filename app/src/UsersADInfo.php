<?php
namespace App\Assets\Inc\Classes;

use App\Assets\Inc\Classes\Session\Session;

/** 
 *  13/02/2023 
 *  Description: Sends a curl request to the active directory api to get the users information
 *  @param string $accessToken - the access token to authenticate the request
 *  @param string $uri - the uri to send the request to 
 */
class UsersActiveDirectoryInformation 
{
    public $accessToken;
    public $uri;

    /** 
     *  16/02/2023      
     *  
     *  @param string $accessToken - the access token to authenticate the request
     *  @param string $uri - $_ENV['ACTIVE_DIRECTORY_URI']      
     *      
     */
    public function __construct($accessToken, $uri)
    {
        $this->accessToken = $accessToken;
        $this->uri = $uri;
        $this->get();        
    }

    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function get() 
    {
        $curlAttempts = 0;
        $curlSuccess = false;
        
        // I've put this in here, because I was getting a 401 error, and I think it was because the token was not ready yet, so I've put in a loop to try 5 times
        while($curlAttempts <= 5) 
        {
            // attempt to get a successful response from the api
            if($usersInformation = $this->getUsersInformation()) 
            {
                $curlSuccess = true;
                break;
            }
            
            sleep(1);
            $curlAttempts++;
        }

        if(!$curlSuccess) 
        {
            throw new Exception("Unable to get user information from Active Directory");
            exit;
        }

        $this->setCommonSessionVariables($usersInformation);

        return $usersInformation;
    }

    private function upperCaseFirstLetter($name) 
    {
        return ucwords(strtolower($name));
    }

    // 13/02/2023 - sets common session variables so that they are more readable and easier to use
    protected function setCommonSessionVariables($usersInformation) 
    {
        Session::set('user.personalTitle', $this->upperCaseFirstLetter($usersInformation->personaltitle[0]));
        Session::set('user.firstName',     $this->upperCaseFirstLetter($usersInformation->givenname[0]));
        Session::set('user.lastName',      $this->upperCaseFirstLetter($usersInformation->sn[0]));
        Session::set('user.email',         strtolower($usersInformation->mail[0]));
        Session::set('user.department',    $usersInformation->department[0]);
        Session::set('user.code',          $usersInformation->detattribute12[0]);
        Session::set('user.displayName',   $this->upperCaseFirstLetter($usersInformation->givenname[0]) . ' ' . $this->upperCaseFirstLetter($usersInformation->sn[0]));
        Session::set('user.phone',         $usersInformation->telephonenumber[0]);
        Session::set('user.jobTitle',      $usersInformation->title[0]);
        Session::set('user.streetAddress', $usersInformation->streetaddress[0]);
        Session::set('user.location',      $usersInformation->l[0]);
        Session::set('user.objectguid',    $usersInformation->objectguid[0]);
    }

    public function getUsersInformation()
    {
        $ch = curl_init();
        $authorization = "Authorization: Bearer ". $this->accessToken;
        
        curl_setopt($ch, CURLOPT_URL, $this->uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_POST, 1);
        // returns the result to curl_exec instead of outputting it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5000);

        $resp = curl_exec($ch);

        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($info != 200) {
            return false;
        }

        $decoded = json_decode($resp);
        
        curl_close($ch);

        return $decoded;
    }
}

